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

            $extension = AccountMasterExtension::where('AccountNumber', $input['AccountNumber'])->first();
            if ($extension != null) {
                $extension->delete();

                $extension = new AccountMasterExtension;
                $extension->AccountNumber = $input['AccountNumber'];
                $extension->Item2 = $input['ServiceConnectionId'];
                $extension->save();
            } else {
                $extension = new AccountMasterExtension;
                $extension->AccountNumber = $input['AccountNumber'];
                $extension->Item2 = $input['ServiceConnectionId'];
                $extension->save();
            }

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

        $meters = DB::connection('sqlsrvbilling')
            ->table('Meter')
            ->whereRaw("MeterNumber='" . $meterAndTransformer->MeterSerialNumber . "'")
            ->first();
        
        if ($meters != null) {
            $meterOwner = AccountMaster::where('MeterNumber', $meters->MeterNumber)->first();
        } else {
            $meterOwner = null;
        }
        

        return view('/account_masters/account_migration_step_two', [
            'serviceAccount' => $serviceAccount,
            'serviceConnection' => $serviceConnection,
            'meter' => $meterAndTransformer,
            'meters' => $meters,
            'meterOwner' => $meterOwner,
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
        $x = 0;
        $len = count($accounts);
        $start = 0;
        $end = 0;
        foreach($accounts as $item) {
            array_push($existing, $item->SequenceNumber);
            if ($x == 0) {
                $start = intval($item->SequenceNumber);
            }

            if ($x == ($len-1)) {
                $end = intval($item->SequenceNumber);
            }
            $x++;
        }

        // generate ten thousand samples
        $samples = [];
        $sample = 99999;
        for ($i = $start; $i <= $end; $i++) {
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

    public function reportsNewAccounts(Request $request) {
        $from = $request['From'];
        $to = $request['To'];

        $accounts = AccountMaster::whereRaw("TRY_CAST(DateEntry AS DATE) BETWEEN '" . $from . "' AND '" . $to . "'")
            ->orderByDesc('DateEntry')
            ->get();

        return view('/account_masters/reports_new_accounts', [
            'accounts' => $accounts
        ]);
    }

    public function printNewAccounts($from, $to) {
        $accounts = AccountMaster::whereRaw("TRY_CAST(DateEntry AS DATE) BETWEEN '" . $from . "' AND '" . $to . "'")
            ->orderByDesc('DateEntry')
            ->get();

        return view('/account_masters/print_new_accounts', [
            'accounts' => $accounts,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function newEnergizedBapa() {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.id as id',
                            'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                            'CRM_ServiceConnections.Status as Status',
                            'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                            'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                            'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                            'CRM_ServiceConnections.AccountCount as AccountCount',  
                            'CRM_ServiceConnections.ConnectionApplicationType',  
                            'CRM_ServiceConnections.AccountNumber',  
                            'CRM_ServiceConnections.Sitio as Sitio', 
                            'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                            'CRM_ServiceConnectionAccountTypes.Alias',
                            'CRM_Towns.Town as Town',
                            'CRM_Barangays.Barangay as Barangay')
            ->where(function ($query) {
                                $query->where('CRM_ServiceConnections.Trash', 'No')
                                    ->orWhereNull('CRM_ServiceConnections.Trash');
                            })  
            ->whereIn('Status', ['Energized', 'Approved For Change Name'])
            ->whereRaw("CRM_ServiceConnections.created_at > '2023-02-28' AND CRM_ServiceConnections.AccountType IN " . ServiceConnections::getBapaAccountCodes())
            ->orderBy('CRM_ServiceConnections.ServiceAccountName')
            ->get();

        return view('/account_masters/new_bapa_energized', [
            'serviceConnections' => $serviceConnections,
        ]);
    }

    public function abruptIncreaseDecrease(Request $request) {
        return view('/account_masters/abrupt_increase_decrease', [
            'consumerTypes' => AccountMaster::whereNotNull('ConsumerType')->select('ConsumerType')->groupBy('ConsumerType')->get()
        ]);
    }

    public function getAbruptIncreaseDecrease(Request $request) {
        $consumerType = $request['ConsumerType'];

        $data = DB::connection("sqlsrvbilling")->select("SET NOCOUNT ON; EXEC AbruptDecreaseIncreaseAnalyzer @ConsumerType='" . $consumerType . "'");

        usort($data, function($a, $b) {
            return $a->PercentageChange < $b->PercentageChange;
        });

        $output = "";
        foreach ($data as $item) {
            $output .= "<tr>" .
                            "<td>" . $item->AccountNumber . "</td>" .
                            "<td>" . $item->ConsumerName . "</td>" .
                            "<td>" . $item->ConsumerAddress . "</td>" .
                            "<td class='text-info'>" . date('M Y', strtotime($item->BillingMonth . ' -1 month')) . "</td>" .
                            "<td class='text-right text-info'><strong>" . $item->PrevKwhUsed . "</strong></td>" .
                            "<td class='text-orange'>" . date('M Y', strtotime($item->BillingMonth)) . "</td>" .
                            "<td class='text-right text-orange'><strong>" . $item->PresentKwhUsed . "</strong></td>" .
                            "<td class='text-right " . AccountMaster::PNColors($item->DiffFromPrev) . "'><strong><i class='fas " . AccountMaster::PNIcons($item->DiffFromPrev) . " ico-tab-mini'></i>" . number_format($item->DiffFromPrev, 2) . "</strong></td>" .
                            "<td class='text-right " . AccountMaster::PNColors($item->PercentageChange) . "'><strong><i class='fas " . AccountMaster::PNIcons($item->PercentageChange) . " ico-tab-mini'></i>" . number_format($item->PercentageChange, 2) . " %</strong></td>" .
                        "</tr>";
        }

        return response()->json($output, 200);
    }

    public function getLeftAvailableAccountNumbers(Request $request) {
        $acctNoR = $request['AccountNumberSample'];

        if (strlen($acctNoR) == 10) {
            $acctNo = substr($acctNoR, 0, 6);
            $seq = substr($acctNoR, 6, 9);
            $seq = intval($seq);

            // GET ALL ACCOUNT NOS FIRST
            $accounts = AccountMaster::whereRaw("AccountNumber LIKE '" . $acctNo . "%'")->get();
            $existing = [];
            foreach($accounts as $item) {
                array_push($existing, $item->AccountNumber);
            }

            // generate ten thousand samples LEFT
            $samples = [];
            $sample = $seq > 4000 ? ($seq-4000) : 1;
            for ($i = $seq; $i >= $sample; $i--) {
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

            // generate ten thousand samples RIGHT
            $samples = [];
            $sample = ($seq+4000) > 9999 ? 9999 : ($seq+4000);
            for ($i = $seq; $i <= $sample; $i++) {
                $head = sprintf("%0004d", $i);
                array_push($samples, $acctNo . $head);
            }

            $finalData = array_diff($samples, $existing);
            $outputRight = "";
            foreach($finalData as $key => $value) {
                $outputRight .= "<tr onclick=selectAccount('" . $value . "')>" .
                    "<td>" . $value . "</td>" .
                "</tr>";
            }

            $data = [
                'left' => $output,
                'right' => $outputRight,
            ];

            return response()->json($data, 200);
        } else {
            return response()->json('Invalid', 404);
        }        
    }

    public function printSDIR($office) {
        if ($office == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->select('CRM_ServiceConnections.id as id',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',  
                                'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                'CRM_ServiceConnectionAccountTypes.Alias',
                                'CRM_ServiceConnections.AccountNumber',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                                )
                ->where(function ($query) {
                                    $query->where('CRM_ServiceConnections.Trash', 'No')
                                        ->orWhereNull('CRM_ServiceConnections.Trash');
                                })  
                ->whereIn('Status', ['Energized', 'Approved For Change Name'])
                ->whereRaw("CRM_ServiceConnections.created_at > '2023-02-28' AND CRM_ServiceConnections.AccountType NOT IN " . ServiceConnections::getBapaAccountCodes() . " AND ConnectionApplicationType NOT IN ('Rewiring')")
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->select('CRM_ServiceConnections.id as id',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',  
                                'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                'CRM_ServiceConnectionAccountTypes.Alias',
                                'CRM_ServiceConnections.AccountNumber',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                                )
                ->where(function ($query) {
                                    $query->where('CRM_ServiceConnections.Trash', 'No')
                                        ->orWhereNull('CRM_ServiceConnections.Trash');
                                })  
                ->whereIn('Status', ['Energized', 'Approved For Change Name'])
                ->whereRaw("CRM_ServiceConnections.Office='" . $office . "' AND CRM_ServiceConnections.created_at > '2023-02-28' AND CRM_ServiceConnections.AccountType NOT IN " . ServiceConnections::getBapaAccountCodes() . " AND ConnectionApplicationType NOT IN ('Rewiring')")
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        }        

        return view('/account_masters/print_sdir', [
            'serviceConnections' => $serviceConnections,
        ]);
    }
}
