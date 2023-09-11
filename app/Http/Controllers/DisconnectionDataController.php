<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDisconnectionDataRequest;
use App\Http\Requests\UpdateDisconnectionDataRequest;
use App\Repositories\DisconnectionDataRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\DisconnectionSchedules;
use App\Models\DisconnectionData;
use App\Models\PaidBills;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class DisconnectionDataController extends AppBaseController
{
    /** @var  DisconnectionDataRepository */
    private $disconnectionDataRepository;

    public function __construct(DisconnectionDataRepository $disconnectionDataRepo)
    {
        $this->middleware('auth');
        $this->disconnectionDataRepository = $disconnectionDataRepo;
    }

    /**
     * Display a listing of the DisconnectionData.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('disconnection_datas.index');
    }

    /**
     * Show the form for creating a new DisconnectionData.
     *
     * @return Response
     */
    public function create()
    {
        return view('disconnection_datas.create');
    }

    /**
     * Store a newly created DisconnectionData in storage.
     *
     * @param CreateDisconnectionDataRequest $request
     *
     * @return Response
     */
    public function store(CreateDisconnectionDataRequest $request)
    {
        $input = $request->all();

        $disconnectionData = $this->disconnectionDataRepository->create($input);

        Flash::success('Disconnection Data saved successfully.');

        return redirect(route('disconnectionDatas.index'));
    }

    /**
     * Display the specified DisconnectionData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        return view('disconnection_datas.show')->with('disconnectionData', $disconnectionData);
    }

    /**
     * Show the form for editing the specified DisconnectionData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        return view('disconnection_datas.edit')->with('disconnectionData', $disconnectionData);
    }

    /**
     * Update the specified DisconnectionData in storage.
     *
     * @param int $id
     * @param UpdateDisconnectionDataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDisconnectionDataRequest $request)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        $disconnectionData = $this->disconnectionDataRepository->update($request->all(), $id);

        Flash::success('Disconnection Data updated successfully.');

        return redirect(route('disconnectionDatas.index'));
    }

    /**
     * Remove the specified DisconnectionData from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        $this->disconnectionDataRepository->delete($id);

        Flash::success('Disconnection Data deleted successfully.');

        return redirect(route('disconnectionDatas.index'));
    }

    public function discoTellerModule(Request $request) {
        return view('/disconnection_datas/disco_teller_module', [

        ]);
    }

    public function discoTellerModuleView($disconnectorName, $disconnectionDate) {
        $disconnectorName = urldecode($disconnectorName);
        $disconnectionDate = date('Y-m-d', strtotime($disconnectionDate));

        $data = DB::connection("sqlsrvbilling")
            ->table("DisconnectionData")
            ->leftJoin("PaidBills", function($join) {
                $join->on("DisconnectionData.AccountNumber", "=", "PaidBills.AccountNumber")
                    ->on("DisconnectionData.ServicePeriodEnd", "=", "PaidBills.ServicePeriodEnd");
            })
            ->whereRaw("TRY_CAST(DisconnectionData.DisconnectionDate AS DATE)='" . $disconnectionDate . "' AND PaidBills.Teller='" . $disconnectorName . "' AND PaidAmount > 0")
            ->select(
                "DisconnectionData.*",
                "PaidBills.Teller",
                'PaidBills.ORNumber AS PORNumber',
                'PaidBills.ORDate AS PORDate',
            )
            ->orderBy("DisconnectionData.ConsumerName")
            ->get();

        $groupedData = DB::connection("sqlsrvbilling")
            ->table("DisconnectionData")
            ->whereRaw("TRY_CAST(DisconnectionData.DisconnectionDate AS DATE)='" . $disconnectionDate . "' AND DisconnectionData.DisconnectorName='" . $disconnectorName . "' AND PaidAmount > 0")
            ->select(
                "DisconnectionData.AccountNumber"
            )
            ->groupBy("DisconnectionData.AccountNumber")
            ->get();

        $doublePayments = DB::connection("sqlsrvbilling")
            ->table("DisconnectionData")
            ->leftJoin("PaidBills", function($join) {
                $join->on("DisconnectionData.AccountNumber", "=", "PaidBills.AccountNumber")
                    ->on("DisconnectionData.ServicePeriodEnd", "=", "PaidBills.ServicePeriodEnd");
            })
            ->whereRaw("TRY_CAST(DisconnectionData.DisconnectionDate AS DATE)='" . $disconnectionDate . "' AND DisconnectionData.DisconnectorName='" . $disconnectorName . "' AND PaidAmount > 0 AND PaymentNotes='DOUBLE PAYMENT'")
            ->select(
                "DisconnectionData.*",
                "PaidBills.Teller",
                "PaidBills.NetAmount AS AmountPaid",
                "PaidBills.PostingDate AS DatePaid",
            )
            ->orderBy("DisconnectionData.ConsumerName")
            ->get();

        return view('/disconnection_datas/disco_teller_module_view', [
            'name' => $disconnectorName,
            'date' => $disconnectionDate,
            'data' => $data,
            'groupedData' => $groupedData,
            'doublePayments' => $doublePayments,
        ]);
    }

    public function postPayments(Request $request) {
        $disconnectorName = $request['DisconnectorName'];
        $disconnectionDate = $request['DisconnectionDate'];
        $orNumber = $request['ORNumber'];
        $orDate = $request['ORDate'];

        PaidBills::where('Teller', $disconnectorName)
            ->whereRaw("TRY_CAST(PostingDate AS DATE)='" . $disconnectionDate . "' AND ORNumber IS NULL")
            ->update(['ORNumber' => $orNumber, 'ORDate' => $orDate]);

        DisconnectionData::where('DisconnectorName', $disconnectorName)
            ->whereRaw("TRY_CAST(DisconnectionDate AS DATE)='" . $disconnectionDate . "' AND ORNumber IS NULL")
            ->update(['ORNumber' => $orNumber, 'ORDate' => $orDate]);

        return response()->json('ok', 200);
    }

    public function printDoublePayments($disconnectorName, $disconnectionDate) {
        $disconnectorName = urldecode($disconnectorName);
        $disconnectionDate = date('Y-m-d', strtotime($disconnectionDate));

        $doublePayments = DB::connection("sqlsrvbilling")
            ->table("DisconnectionData")
            ->leftJoin("PaidBills", function($join) {
                $join->on("DisconnectionData.AccountNumber", "=", "PaidBills.AccountNumber")
                    ->on("DisconnectionData.ServicePeriodEnd", "=", "PaidBills.ServicePeriodEnd");
            })
            ->whereRaw("TRY_CAST(DisconnectionData.DisconnectionDate AS DATE)='" . $disconnectionDate . "' AND DisconnectionData.DisconnectorName='" . $disconnectorName . "' AND PaidAmount > 0 AND PaymentNotes='DOUBLE PAYMENT'")
            ->select(
                "DisconnectionData.*",
                "PaidBills.Teller",
                "PaidBills.NetAmount AS AmountPaid",
                "PaidBills.PostingDate AS DatePaid",
            )
            ->orderBy("DisconnectionData.ConsumerName")
            ->get();

        return view('/disconnection_datas/print_double_payments', [
            'name' => $disconnectorName,
            'date' => $disconnectionDate,
            'doublePayments' => $doublePayments,
        ]);
    }

    public function getMonthlyCollectionGraph(Request $request) {   
        $year = $request['Year'];

        $data = DB::connection('sqlsrvbilling')
            ->table('PaidBills')
            ->select(
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-01-01' AND '" . date('Y-m-d', strtotime("first day of February " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS January"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-02-01' AND '" . date('Y-m-d', strtotime("first day of March " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS February"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-03-01' AND '" . date('Y-m-d', strtotime("first day of April " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS March"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-04-01' AND '" . date('Y-m-d', strtotime("first day of May " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS April"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-05-01' AND '" . date('Y-m-d', strtotime("first day of June " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS May"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-06-01' AND '" . date('Y-m-d', strtotime("first day of July " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS June"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-07-01' AND '" . date('Y-m-d', strtotime("first day of August " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS July"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-08-01' AND '" . date('Y-m-d', strtotime("first day of September " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS August"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-09-01' AND '" . date('Y-m-d', strtotime("first day of October " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS September"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-10-01' AND '" . date('Y-m-d', strtotime("first day of November " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS October"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-11-01' AND '" . date('Y-m-d', strtotime("first day of December " . $year)) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS November"),
                DB::raw("(SELECT SUM(NetAmount) FROM PaidBills WHERE (PostingDate BETWEEN '" . $year . "-12-01' AND '" . date('Y-m-d', strtotime("first day of January " . (intval($year) + 1))) . "') AND Teller IN (" . DisconnectionData::getDisconnectorNames() . ")) AS December"),
            )
            ->first();

        return response()->json($data, 200);
    }
}
