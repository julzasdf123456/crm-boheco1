<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountMasterRequest;
use App\Http\Requests\UpdateAccountMasterRequest;
use App\Repositories\AccountMasterRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnections;
use App\Models\Towns;
use App\Models\Barangays;
use App\Models\ServiceConnectionInspections;
use App\Models\ServiceConnectionAccountTypes;
use App\Models\ServiceAccounts;
use App\Models\MeterReaders;
use App\Models\Meters;
use App\Models\Bills;
use App\Models\AccountNameHistory;
use App\Models\BillingTransformers;
use App\Models\AccountMaster;
use App\Models\AccountMasterExtension;
use App\Models\ServiceConnectionMtrTrnsfrmr;
use App\Models\ServiceConnectionCrew;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Flash;
use Response;

class AccountMasterController extends AppBaseController
{
    /** @var  AccountMasterRepository */
    private $accountMasterRepository;

    public function __construct(AccountMasterRepository $accountMasterRepo)
    {
        $this->middleware('auth');
        $this->accountMasterRepository = $accountMasterRepo;
    }

    /**
     * Display a listing of the AccountMaster.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $query = $request['params'];

        if (isset($query)) {
            $serviceAccounts = DB::connection('sqlsrvbilling')->table('AccountMaster')
                ->whereRaw("ConsumerName LIKE '%" . $query . "%' OR MeterNumber LIKE '%" . $query . "%' OR AccountNumber LIKE '%" . $query . "%'")
                ->select('*')
                ->paginate(50);
        } else {
            $serviceAccounts = DB::connection('sqlsrvbilling')->table('AccountMaster')
                ->select('*')
                ->paginate(25);
        }

        return view('account_masters.index', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    /**
     * Show the form for creating a new AccountMaster.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_masters.create');
    }

    /**
     * Store a newly created AccountMaster in storage.
     *
     * @param CreateAccountMasterRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountMasterRequest $request)
    {
        $input = $request->all();

        $account = AccountMaster::find($input['AccountNumber']);
        if ($account != null) {
            Flash::error('Account Number already taken!');

            return redirect(route('accountMasters.account-migration-step-one', [$input['ServiceConnectionId']]));
        } else {
            $accountMaster = $this->accountMasterRepository->create($input);

            // Flash::success('Account Master saved successfully.');

            $extension = new AccountMasterExtension;
            $extension->AccountNumber = $input['AccountNumber'];
            $extension->Item2 = $input['ServiceConnectionId'];
            $extension->save();

            return redirect(route('accountMasters.account-migration-step-two', [$input['AccountNumber'], $input['ServiceConnectionId']]));
        }
    }

    /**
     * Display the specified AccountMaster.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        $meter = Meters::where('MeterNumber', $accountMaster->MeterNumber)->first();

        $bills = DB::connection('sqlsrvbilling')->table('Bills')
            ->leftJoin('PaidBills', function($join) {
                $join->on('Bills.AccountNumber', '=', 'PaidBills.AccountNumber')
                    ->on('Bills.ServicePeriodEnd', '=', 'PaidBills.ServicePeriodEnd');
            })
            ->select('Bills.*', 'PaidBills.ORNumber', 'PaidBills.ORDate')
            ->where('Bills.AccountNumber', $id)
            ->orderByDesc('Bills.ServicePeriodEnd')
            ->get();

        return view('account_masters.show', [
            'accountMaster' => $accountMaster,
            'meter' => $meter,
            'bills' => $bills
        ]);
    }

    /**
     * Show the form for editing the specified AccountMaster.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        return view('account_masters.edit')->with('accountMaster', $accountMaster);
    }

    /**
     * Update the specified AccountMaster in storage.
     *
     * @param int $id
     * @param UpdateAccountMasterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountMasterRequest $request)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account not found');

            return redirect(route('accountMasters.index'));
        }

        $accountMaster = $this->accountMasterRepository->update($request->all(), $id);

        $serviceConnection = ServiceConnections::find($request['ServiceConnectionId']);
        if ($serviceConnection != null) {
            $serviceConnection->Status = 'Closed';
            $serviceConnection->save();
        }

        Flash::success('Account migrated successfully!.');

        return redirect(route('serviceAccounts.pending-accounts'));
    }

    /**
     * Remove the specified AccountMaster from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        $this->accountMasterRepository->delete($id);

        Flash::success('Account Master deleted successfully.');

        return redirect(route('accountMasters.index'));
    }

    public function accountMigrationStepOne($id) {
        $serviceConnection = ServiceConnections::find($id);
        // $serviceAccount = ServiceAccounts::where('ServiceConnectionId', $id)->first();
        $serviceConnectionInspection = ServiceConnectionInspections::where('ServiceConnectionId', $id)->orderByDesc('created_at')->first();
        $towns = Towns::where('id', $serviceConnection->Town)->first();
        $barangays = Barangays::where('id', $serviceConnection->Barangay)->first();
        $crew = ServiceConnectionCrew::find($serviceConnection->StationCrewAssigned);
        $accountTypes = ServiceConnectionAccountTypes::all();

        return view('/account_masters/account_migration_step_one',
            [
                'serviceConnection' => $serviceConnection,
                'inspection' => $serviceConnectionInspection,
                'town' => $towns,
                'barangay' => $barangays,
                'accountTypes' => $accountTypes,
                'crew' => $crew,
            ] 
        );
    }

    public function accountMigrationStepTwo($acctNo, $scId) {
        $serviceAccount = AccountMaster::find($acctNo);
        $serviceConnection = ServiceConnections::find($scId);
        $meterAndTransformer = ServiceConnectionMtrTrnsfrmr::where('ServiceConnectionId', $scId)->first();

        return view('/account_masters/account_migration_step_two', [
            'serviceAccount' => $serviceAccount,
            'serviceConnection' => $serviceConnection,
            'meter' => $meterAndTransformer,
        ]);
    }

    public function accountMigrationStepThree($acctNo, $scId) {
        $serviceAccount = AccountMaster::find($acctNo);
        $serviceConnection = ServiceConnections::find($scId);
        $meterAndTransformer = ServiceConnectionMtrTrnsfrmr::where('ServiceConnectionId', $scId)->first();
        $meter = Meters::find($serviceAccount->MeterNumber);

        return view('/account_masters/account_migration_step_three', [
            'serviceAccount' => $serviceAccount,
            'serviceConnection' => $serviceConnection,
            'meterAndTransformer' => $meterAndTransformer,
            'meter' => $meter
        ]);
    }

    public function getAvailableAccountNumbers(Request $request) {
        $acctNo = $request['AccountNumberSample'];

        if (strlen($acctNo) == 6) {
            $acctNo = $acctNo;
        } else {
            $acctNo = substr($acctNo, 0, 6);
        }

        // GET ALL ACCOUNT NOS FIRST
        $accounts = AccountMaster::whereRaw("AccountNumber LIKE '" . $acctNo . "%'")->get();
        $existing = [];
        foreach($accounts as $item) {
            array_push($existing, $item->AccountNumber);
        }

        // generate ten thousand samples
        $samples = [];
        $sample = 9999;
        for ($i = 1; $i <= $sample; $i++) {
            $head = sprintf("%0004d", $i);
            array_push($samples, $acctNo . $head);
        }

        $finalData = array_diff($samples, $existing);
        $output = "";
        foreach($finalData as $key => $value) {
            $output .= "<tr onclick=selectAccount('" . $value . "')>" .
                "<td>" . $value . "</td>" .
            "</tr>";
        }
        return response()->json($output, 200);
    }

    public function getAvailableSequenceNumbers(Request $request) {
        $route = $request['Route'];

        // GET ALL ACCOUNT NOS FIRST
        $accounts = AccountMaster::whereRaw("Route='" . $route . "'")->get();
        $existing = [];
        foreach($accounts as $item) {
            array_push($existing, $item->SequenceNumber);
        }

        // generate ten thousand samples
        $samples = [];
        $sample = 99999;
        for ($i = 1; $i <= $sample; $i++) {
            array_push($samples, $i);
        }

        $finalData = array_diff($samples, $existing);
        $output = "";
        foreach($finalData as $key => $value) {
            $output .= "<tr onclick=selectRoute('" . $value . "')>" .
                "<td>" . $value . "</td>" .
            "</tr>";
        }
        return response()->json($output, 200);
    }

    public function getNeighboringByBarangay(Request $request) {
        $town = $request['Town'];
        $barangay = $request['Barangay'];

        $accounts = DB::connection('sqlsrvbilling')->table('AccountMaster')
            ->whereRaw("ConsumerAddress LIKE '%" . $barangay . "%' AND ConsumerAddress LIKE '%" . $town . "%' AND Item1 IS NOT NULL")
            ->select('Item1', 'AccountNumber', 'ConsumerName', 'SequenceNumber', 'Route')
            ->get();

        return response()->json($accounts, 200);
    }

    public function getNeighboringByAccount(Request $request) {
        $accountNumber = $request['AccountNumber'];

        $account = AccountMaster::where('AccountNumber', $accountNumber)->first();

        if ($account != null) {
            $accounts = DB::connection('sqlsrvbilling')->table('AccountMaster')
                ->whereRaw("Route='" . $account->Route . "' AND AccountNumber NOT IN ('" . $accountNumber . "') AND Item1 IS NOT NULL")
                ->select('Item1', 'AccountNumber', 'ConsumerName', 'SequenceNumber', 'Route', 'Pole')
                ->get();
        } else {
            $accounts = [];
        }

        return response()->json($accounts, 200);
    }
}
