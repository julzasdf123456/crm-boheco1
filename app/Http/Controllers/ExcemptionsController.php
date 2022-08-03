<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExcemptionsRequest;
use App\Http\Requests\UpdateExcemptionsRequest;
use App\Repositories\ExcemptionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Excemptions;
use App\Models\Rates;
use App\Models\IDGenerator;
use App\Models\MeterReaders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class ExcemptionsController extends AppBaseController
{
    /** @var  ExcemptionsRepository */
    private $excemptionsRepository;

    public function __construct(ExcemptionsRepository $excemptionsRepo)
    {
        $this->middleware('auth');
        $this->excemptionsRepository = $excemptionsRepo;
    }

    /**
     * Display a listing of the Excemptions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // $excemptions = Excemptions::select('ServicePeriod')
        //     ->groupBy('ServicePeriod')
        //     ->orderByDesc('ServicePeriod')
        //     ->get();

        // return view('excemptions.index', [
        //     'excemptions' => $excemptions
        // ]);
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        return view('/excemptions/new_excemptions', [
            'latestRate' => $latestRate
        ]);
    }

    /**
     * Show the form for creating a new Excemptions.
     *
     * @return Response
     */
    public function create()
    {
        return view('excemptions.create');
    }

    /**
     * Store a newly created Excemptions in storage.
     *
     * @param CreateExcemptionsRequest $request
     *
     * @return Response
     */
    public function store(CreateExcemptionsRequest $request)
    {
        $input = $request->all();

        $excemptions = $this->excemptionsRepository->create($input);

        Flash::success('Excemptions saved successfully.');

        return redirect(route('excemptions.index'));
    }

    /**
     * Display the specified Excemptions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        return view('excemptions.show')->with('excemptions', $excemptions);
    }

    /**
     * Show the form for editing the specified Excemptions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        return view('excemptions.edit')->with('excemptions', $excemptions);
    }

    /**
     * Update the specified Excemptions in storage.
     *
     * @param int $id
     * @param UpdateExcemptionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExcemptionsRequest $request)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        $excemptions = $this->excemptionsRepository->update($request->all(), $id);

        Flash::success('Excemptions updated successfully.');

        return redirect(route('excemptions.index'));
    }

    /**
     * Remove the specified Excemptions from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        $this->excemptionsRepository->delete($id);

        Flash::success('Excemptions deleted successfully.');

        return redirect(route('excemptions.index'));
    }

    public function newExcemption(Request $request) {
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        return view('/excemptions/new_excemptions', [
            'latestRate' => $latestRate
        ]);
    }

    public function searchAccountExcemption(Request $request) {
        $period = $request['ServicePeriod'];
        $accountNo = $request['AccountNumber'];

        $data = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_ServiceAccounts.OldAccountNo LIKE '%" . $accountNo . "%' AND Billing_Bills.ServicePeriod='" . $period . "'")
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "')")
            ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
            ->select('Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_Bills.id',
                'Billing_Bills.AccountNumber',
                'Billing_Bills.NetAmount',
                'Billing_Bills.KwhUsed')
            ->get();

        $output = "";
        foreach($data as $item) {
            $output .= "<tr id='" . $item->AccountNumber . "'>
                            <td>" . $item->OldAccountNo . "</td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td class='text-right'>" . number_format($item->NetAmount, 2) . "</td>
                            <td class='text-right'>
                                <button onclick=addExcemption('" . $item->AccountNumber . "') class='btn btn-primary btn-xs'><i class='fas fa-plus ico-tab-mini'></i> Add</button>
                            </td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function addExcemption(Request $request) {
        $period = $request['ServicePeriod'];
        $accountNo = $request['AccountNumber'];

        $excemption = new Excemptions;
        $excemption->id = IDGenerator::generateIDandRandString();
        $excemption->AccountNumber = $accountNo;
        $excemption->ServicePeriod = $period;
        $excemption->save();

        return response()->json($excemption, 200);
    }

    public function getExcemptionsAjax(Request $request) {
        $period = $request['ServicePeriod'];

        $data = DB::table('Billing_Excemptions')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Excemptions.ServicePeriod='" . $period . "'")
            ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
            ->select('Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_Excemptions.id')
            ->get();

        $output = "";
        foreach($data as $item) {
            $output .= "<tr>
                        <td>" . $item->OldAccountNo . "</td>
                        <td>" . $item->ServiceAccountName . "</td>
                        <td class='text-right'>
                            <button onclick=removeExcemption('" . $item->id . "') class='btn btn-danger btn-xs'><i class='fas fa-trash ico-tab-mini'></i> Remove</button>
                        </td>
                    </tr>";
        }

        return response()->json($output, 200);
    }

    public function removeExcemption(Request $request) {
        Excemptions::find($request['id'])->delete();

        return response()->json('ok', 200);
    }

    public function printExcemptions($period) {
        $data = DB::table('Billing_Excemptions')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Excemptions.ServicePeriod='" . $period . "'")
            ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
            ->select('Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_Excemptions.id')
            ->get();

        return view('/excemptions/print_excemptions', [
            'data' => $data,
            'period' => $period
        ]);
    }
}
