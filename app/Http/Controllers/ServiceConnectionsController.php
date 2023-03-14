<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionsRequest;
use App\Http\Requests\UpdateServiceConnectionsRequest;
use App\Repositories\ServiceConnectionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\MemberConsumers;
use App\Models\MemberConsumerSpouse;
use App\Models\Towns;
use App\Models\ServiceConnectionAccountTypes;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionInspections;
use App\Models\ServiceConnectionMtrTrnsfrmr;
use App\Models\ServiceConnectionPayTransaction;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use App\Models\ServiceConnectionChecklistsRep;
use App\Models\ServiceConnectionChecklists;
use App\Models\ServiceConnectionCrew;
use App\Models\ServiceConnectionLgLoadInsp;
use App\Models\ServiceConnectionPayParticulars;
use App\Models\StructureAssignments;
use App\Models\Structures;
use App\Models\MaterialAssets;
use App\Models\BillsOfMaterialsSummary;
use App\Models\SpanningData;
use App\Models\PoleIndex;
use App\Models\PreDefinedMaterials;
use App\Models\PreDefinedMaterialsMatrix;
use App\Models\TransactionDetails;
use App\Models\TransactionIndex;
use App\Models\ServiceAccounts;
use App\Models\Notifiers;
use App\Models\SMSNotifications;
use App\Models\BillDeposits;
use App\Models\User;
use App\Exports\ServiceConnectionApplicationsReportExport;
use App\Exports\ServiceConnectionEnergizationReportExport;
use App\Exports\DynamicExport;
use App\Exports\SummaryReportExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Flash;
use Response;

class ServiceConnectionsController extends AppBaseController
{
    /** @var  ServiceConnectionsRepository */
    private $serviceConnectionsRepository;

    public function __construct(ServiceConnectionsRepository $serviceConnectionsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionsRepository = $serviceConnectionsRepo;
    }

    /**
     * Display a listing of the ServiceConnections.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request['params'] == null) {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',
                                'CRM_ServiceConnections.Office',
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.LoadCategory',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
                ->where(function ($query) {
                                    $query->where('CRM_ServiceConnections.Trash', 'No')
                                        ->orWhereNull('CRM_ServiceConnections.Trash');
                                })
                ->orderByDesc('CRM_ServiceConnections.created_at')
                ->paginate(16);
        } else {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',
                                'CRM_ServiceConnections.Office',
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.LoadCategory',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
                ->where(function ($query) {
                                    $query->where('CRM_ServiceConnections.Trash', 'No')
                                        ->orWhereNull('CRM_ServiceConnections.Trash');
                                })
                ->where('CRM_ServiceConnections.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                ->orWhere('CRM_ServiceConnections.Id', 'LIKE', '%' . $request['params'] . '%')
                ->orWhere('CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber', 'LIKE', '%' . $request['params'] . '%')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->paginate(16);
        }

        return view('/service_connections/search_applications', [
            'data' => $data
        ]);
    }

    public function dashboard() {
        return view('/service_connections/dashboard');
    }

    /**
     * Show the form for creating a new ServiceConnections.
     *
     * @return Response
     */
    public function create()
    {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('service_connections.create');
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }
        
    }

    /**
     * Store a newly created ServiceConnections in storage.
     *
     * @param CreateServiceConnectionsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionsRequest $request)
    {
        $input = $request->all();

        if ($input['id'] != null) {
            $sc = ServiceConnections::find($input['id']);

            if ($sc != null) {
                $serviceConnections = $this->serviceConnectionsRepository->find($sc->id);

                if (empty($serviceConnections)) {
                    Flash::error('Service Connections not found');
        
                    return redirect(route('serviceConnections.index'));
                }
        
                $serviceConnections = $this->serviceConnectionsRepository->update($request->all(), $sc->id);

                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $input['id'];
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = $sc->Status;
                $timeFrame->Notes = 'Data Updated';
                $timeFrame->save();

                
                // SEND SMS
                if ($sc->ContactNumber != null) {
                    if (strlen($sc->ContactNumber) > 10) {
                        $msg = "Hello " . $sc->ServiceAccountName . ", \nYour BOHECO I application for Service Connection with application no. " . $sc->id . " has been received and will be processed shortly. " .
                            "You will receive several SMS notifications in the future regarding the progress of your application. \nHave a great day!";
                        SMSNotifications::createFreshSms($sc->ContactNumber, $msg, 'SERVICE CONNECTIONS', $sc->id);
                    }
                }                
                
                // CREATE FOLDER FIRST
                // if (!file_exists('/CRM_FILES//' . $input['id'])) {
                //     mkdir('/CRM_FILES//' . $input['id'], 0777, true);
                // }

                Flash::success('Service Connections saved successfully.');

                // return redirect(route('serviceConnectionInspections.create-step-two', [$input['id']]));
                return redirect(route('serviceConnections.assess-checklists', [$input['id']]));
            } else {
                $serviceConnections = $this->serviceConnectionsRepository->create($input);

                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $input['id'];
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = 'Received';
                $timeFrame->save();

                // SEND SMS
                if ($input['ContactNumber'] != null) {
                    if (strlen($input['ContactNumber']) > 10) {
                        $msg = "Hello " . $serviceConnections->ServiceAccountName . ", \nYour BOHECO I application for Service Connection with application no. " . $input['id'] . " has been received and will be processed shortly. " .
                            "You will receive several SMS notifications in the future regarding the progress of your application. \nHave a great day!";
                        SMSNotifications::createFreshSms($input['ContactNumber'], $msg, 'SERVICE CONNECTIONS', $input['id']);
                    }                    
                }   

                // CREATE FOLDER FIRST
                if (!file_exists('/CRM_FILES//' . $input['id'])) {
                    mkdir('/CRM_FILES//' . $input['id'], 0777, true);
                }

                Flash::success('Service Connections saved successfully.');

                // return redirect(route('serviceConnectionInspections.create-step-two', [$input['id']]));
                return redirect(route('serviceConnections.assess-checklists', [$input['id']]));
            }
        } else {
            return abort('ID Not found!', 404);
        }
    }

    /**
     * Display the specified ServiceConnections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.LongSpan', 
                        'CRM_ServiceConnections.AccountType AS AccountTypeRaw', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.ORDate', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnectionCrew.Members as Members',
                        'CRM_ServiceConnections.ElectricianId',
                        'CRM_ServiceConnections.ElectricianName',
                        'CRM_ServiceConnections.ElectricianAddress',
                        'CRM_ServiceConnections.ElectricianContactNo',
                        'CRM_ServiceConnections.ElectricianAcredited',
                        'CRM_ServiceConnections.LinemanCrewExecuted',)
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        
        $serviceConnectionInspections = ServiceConnectionInspections::where('ServiceConnectionId', $id)
                                ->orderByDesc('created_at')
                                ->first();

        $serviceConnectionMeter = ServiceConnectionMtrTrnsfrmr::where('ServiceConnectionId', $id)->first();

        $serviceConnectionTransactions = ServiceConnectionPayTransaction::where('ServiceConnectionId', $id)->first();

        $laborWiringCharges = DB::table('CRM_ServiceConnectionMaterialPayables')
            ->leftJoin('CRM_ServiceConnectionMaterialPayments', 'CRM_ServiceConnectionMaterialPayables.id', '=', 'CRM_ServiceConnectionMaterialPayments.Material')
            ->where('CRM_ServiceConnectionMaterialPayments.ServiceConnectionId', $id)
            ->select('CRM_ServiceConnectionMaterialPayables.Material',
                'CRM_ServiceConnectionMaterialPayables.Rate',
                'CRM_ServiceConnectionMaterialPayments.Quantity',
                'CRM_ServiceConnectionMaterialPayments.Vat',
                'CRM_ServiceConnectionMaterialPayments.Total'
            )
            ->get();

        $billDeposit = BillDeposits::where('ServiceConnectionId', $id)
            ->first();

        $materialPayments = DB::table('CRM_ServiceConnectionMaterialPayments')
                    ->leftJoin('CRM_ServiceConnectionMaterialPayables', 'CRM_ServiceConnectionMaterialPayments.Material', '=', 'CRM_ServiceConnectionMaterialPayables.id')
                    ->select('CRM_ServiceConnectionMaterialPayments.id',
                            'CRM_ServiceConnectionMaterialPayments.Quantity',
                            'CRM_ServiceConnectionMaterialPayments.Vat',
                            'CRM_ServiceConnectionMaterialPayments.Total',
                            'CRM_ServiceConnectionMaterialPayables.Material',
                            'CRM_ServiceConnectionMaterialPayables.Rate',)
                    ->where('CRM_ServiceConnectionMaterialPayments.ServiceConnectionId', $id)
                    ->get();

        $particularPayments = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
                    ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
                    ->select('CRM_ServiceConnectionParticularPaymentsTransactions.id',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Vat',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Total',
                            'CRM_ServiceConnectionPaymentParticulars.Particular')
                    ->where('CRM_ServiceConnectionParticularPaymentsTransactions.ServiceConnectionId', $id)
                    ->get();

        $totalTransactions = ServiceConnectionTotalPayments::where('ServiceConnectionId', $id)->first();

        $timeFrame = DB::table('CRM_ServiceConnectionTimeframes')
                ->leftJoin('users', 'CRM_ServiceConnectionTimeframes.UserId', '=', 'users.id')
                ->select('CRM_ServiceConnectionTimeframes.id',
                        'CRM_ServiceConnectionTimeframes.Status',
                        'CRM_ServiceConnectionTimeframes.created_at',
                        'CRM_ServiceConnectionTimeframes.ServiceConnectionId',
                        'CRM_ServiceConnectionTimeframes.UserId',
                        'CRM_ServiceConnectionTimeframes.Notes',
                        'users.name')
                ->where('CRM_ServiceConnectionTimeframes.ServiceConnectionId', $id)
                ->orderByDesc('created_at')
                ->get();

        $billOfMaterialsSummary = BillsOfMaterialsSummary::where('ServiceConnectionId', $id)->first();

        $structures = DB::table('CRM_StructureAssignments')
            ->leftJoin('CRM_Structures', 'CRM_StructureAssignments.StructureId', '=', 'CRM_Structures.Data')
            ->select('CRM_Structures.id as id',
                    'CRM_StructureAssignments.StructureId',
                    DB::raw('SUM(CAST(CRM_StructureAssignments.Quantity AS Integer)) AS Quantity'))
            ->where('ServiceConnectionId', $id)
            ->groupBy('CRM_Structures.id', 'CRM_StructureAssignments.StructureId')
            ->get();

        $conAss = DB::table('CRM_StructureAssignments')
            ->where('ServiceConnectionId', $id)
            ->select('ConAssGrouping', 'StructureId', 'Quantity', 'Type')
            ->groupBy('StructureId', 'ConAssGrouping', 'Quantity', 'Type')
            ->orderBy('ConAssGrouping')
            ->get();

        $materials = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_BillOfMaterialsMatrix.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS Cost'))
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $id)
            // ->where('CRM_BillOfMaterialsMatrix.StructureType', 'A_DT')
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get();
        
        $poles = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),)
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $id)
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'POLE')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get();

        $transformers = DB::table('CRM_TransformersAssignedMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_TransformersAssignedMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->select('CRM_MaterialAssets.id',
                        'CRM_TransformersAssignedMatrix.id as TransformerId',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        'CRM_TransformersAssignedMatrix.Quantity',
                        'CRM_TransformersAssignedMatrix.Type')
                ->where('CRM_TransformersAssignedMatrix.ServiceConnectionId', $id)
                ->get();

        if (empty($serviceConnections)) {
            Flash::error('Service Connections not found');

            return redirect(route('serviceConnections.index'));
        }

        if ($serviceConnections->AccountTypeRaw==ServiceConnections::getResidentialId()) {
            $serviceConnectionChecklistsRep = ServiceConnectionChecklistsRep::where('Notes', 'RESIDENTIAL')->get();
        } else {
            if (floatval($serviceConnections->LoadCategory) > 225) {
                $serviceConnectionChecklistsRep = ServiceConnectionChecklistsRep::where('Notes', 'NON-RESIDENTIAL ABOVE 5kVA')->get();
            } else {
                $serviceConnectionChecklistsRep = ServiceConnectionChecklistsRep::where('Notes', 'NON-RESIDENTIAL BELOW 5kVA')->get();
            }
        }
        
        $serviceConnectionChecklists = ServiceConnectionChecklists::where('ServiceConnectionId', $id)->pluck('ChecklistId')->all();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['view membership', 'sc view', 'Super Admin'])) {
            return view('service_connections.show', ['serviceConnections' => $serviceConnections, 
                'serviceConnectionInspections' => $serviceConnectionInspections, 
                'serviceConnectionMeter' => $serviceConnectionMeter, 
                'serviceConnectionTransactions' => $serviceConnectionTransactions,
                'materialPayments' => $materialPayments,
                'particularPayments' => $particularPayments,
                'totalTransactions' => $totalTransactions,
                'timeFrame' => $timeFrame,
                'serviceConnectionChecklistsRep' => $serviceConnectionChecklistsRep,
                'serviceConnectionChecklists' => $serviceConnectionChecklists,
                'billOfMaterialsSummary' => $billOfMaterialsSummary,
                'structures' => $structures,
                'conAss' => $conAss,
                'materials' => $materials,
                'poles' => $poles,
                'transformers' => $transformers,
                'laborWiringCharges' => $laborWiringCharges,
                'billDeposit' => $billDeposit,
            ]);
        } else {
            return abort(403, "You're not authorized to view a service connection application.");
        }        
    }

    /**
     * Show the form for editing the specified ServiceConnections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        $cond = 'edit';

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $memberConsumer = null;

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->get();

        if (empty($serviceConnections)) {
            Flash::error('Service Connections not found');

            return redirect(route('serviceConnections.index'));
        }

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'Super Admin'])) {
            return view('service_connections.edit', ['serviceConnections' => $serviceConnections, 'cond' => $cond, 'towns' => $towns, 'memberConsumer' => $memberConsumer, 'accountTypes' => $accountTypes, 'crew' => $crew]);
        } else {
            return abort(403, "You're not authorized to update a service connection application.");
        }         
    }

    /**
     * Update the specified ServiceConnections in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionsRequest $request)
    {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        if (empty($serviceConnections)) {
            Flash::error('Service Connections not found');

            return redirect(route('serviceConnections.index'));
        }

        $serviceConnections = $this->serviceConnectionsRepository->update($request->all(), $id);

        Flash::success('Service Connections updated successfully.');

        // return redirect(route('serviceConnections.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$id]);
    }

    /**
     * Remove the specified ServiceConnections from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            if (empty($serviceConnections)) {
                Flash::error('Service Connections not found');

                return redirect(route('serviceConnections.index'));
            }

            $this->serviceConnectionsRepository->delete($id);

            Flash::success('Service Connections deleted successfully.');

            return redirect(route('serviceConnections.index'));
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }          
    }

    public function selectMembership(Request $request) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            $search = $request['search'];
            
            if ($search != null) {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->select('CRM_MemberConsumers.Id as ConsumerId',
                                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                                    'CRM_MemberConsumers.FirstName as FirstName', 
                                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                                    'CRM_MemberConsumers.LastName as LastName', 
                                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                    'CRM_MemberConsumers.Suffix as Suffix', 
                                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                    'CRM_MemberConsumers.Notes as Notes', 
                                    'CRM_MemberConsumers.Gender as Gender', 
                                    'CRM_MemberConsumers.Sitio as Sitio', 
                                    'CRM_MemberConsumers.Office', 
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->whereRaw("(LastName LIKE '%" . $search . "%' OR FirstName LIKE '%" . $search . "%' OR MiddleName LIKE '%" . $search . "%' OR 
                        OrganizationName LIKE '%" . $search . "%' OR CRM_MemberConsumers.Id LIKE '%" . $search . "%')")
                    ->paginate(25);
            } else {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->select('CRM_MemberConsumers.Id as ConsumerId',
                                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                                    'CRM_MemberConsumers.FirstName as FirstName', 
                                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                                    'CRM_MemberConsumers.LastName as LastName', 
                                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                    'CRM_MemberConsumers.Suffix as Suffix', 
                                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                    'CRM_MemberConsumers.Notes as Notes', 
                                    'CRM_MemberConsumers.Gender as Gender', 
                                    'CRM_MemberConsumers.Sitio as Sitio', 
                                    'CRM_MemberConsumers.Office', 
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')                                    
                    ->whereRaw("CRM_MemberConsumers.Notes IS NULL OR CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT')")
                    ->orderByDesc('CRM_MemberConsumers.created_at')
                    ->paginate(25);
            }

            return view('/service_connections/selectmembership', [
                'data' => $data
            ]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }
    }

    public function selectApplicationType($consumerId) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/select_application_type', ['consumerId' => $consumerId]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }        
    }

    public function relayApplicationType($consumerId, Request $request) {
        return redirect(route('serviceConnections.create_new', [$consumerId, $request['type']]));
    }

    public function fetchmemberconsumer(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if ($query != '' ) {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->select('CRM_MemberConsumers.Id as ConsumerId',
                                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                                    'CRM_MemberConsumers.FirstName as FirstName', 
                                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                                    'CRM_MemberConsumers.LastName as LastName', 
                                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                    'CRM_MemberConsumers.Suffix as Suffix', 
                                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                                    'CRM_MemberConsumers.Barangay as Barangay', 
                                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                    'CRM_MemberConsumers.Notes as Notes', 
                                    'CRM_MemberConsumers.Gender as Gender', 
                                    'CRM_MemberConsumers.Sitio as Sitio', 
                                    'CRM_MemberConsumers.Office', 
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_MemberConsumers.LastName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.Id', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.OrganizationName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.MiddleName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.FirstName', 'LIKE', '%' . $query . '%')                    
                    ->whereRaw("CRM_MemberConsumers.Notes IS NULL OR CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT')")
                    ->orderBy('CRM_MemberConsumers.FirstName')
                    ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->select('CRM_MemberConsumers.Id as ConsumerId',
                                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                                    'CRM_MemberConsumers.FirstName as FirstName', 
                                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                                    'CRM_MemberConsumers.LastName as LastName', 
                                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                    'CRM_MemberConsumers.Suffix as Suffix', 
                                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                                    'CRM_MemberConsumers.Barangay as Barangay', 
                                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                    'CRM_MemberConsumers.Notes as Notes', 
                                    'CRM_MemberConsumers.Gender as Gender', 
                                    'CRM_MemberConsumers.Sitio as Sitio', 
                                    'CRM_MemberConsumers.Office', 
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')                                    
                    ->whereRaw("CRM_MemberConsumers.Notes IS NULL OR CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT')")
                    ->orderByDesc('CRM_MemberConsumers.created_at')
                    ->take(10)
                    ->get();
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                $output = '';
                foreach ($data as $row) {

                    $output .= '
                        <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div>
                                                <h4>' . MemberConsumers::serializeMemberName($row) . '</h4>
                                                <p class="text-muted" style="margin-bottom: 0;">ID: ' . $row->ConsumerId . '</p>
                                                <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                <a href="' . route('serviceConnections.create_new', [$row->ConsumerId]) . '" class="btn btn-sm btn-primary" style="margin-top: 5px;">Proceed</a>
                                            </div>     
                                        </div> 

                                        <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                            <div>
                                                <p class="text-muted" style="margin-bottom: 0;">Birthdate: <strong>' . date('F d, Y', strtotime($row->Birthdate)) . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Contact No: <strong>' . $row->ContactNumbers . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Email Add: <strong>' . $row->EmailAddress . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Membership Type: <strong>' . $row->Type . '</strong></p>
                                            </div>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    ';
                }                
            } else {
                $output = '
                    <p class="text-center">No data found.</p>';
            }

            $data = [
                'table_data' => $output
            ];

            echo json_encode($data);
        }
    }

    public function createNew($consumerId) {
        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $memberConsumer = DB::table('CRM_MemberConsumers')
                            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                            ->select('CRM_MemberConsumers.Id as ConsumerId',
                                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                                    'CRM_MemberConsumers.FirstName as FirstName', 
                                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                                    'CRM_MemberConsumers.LastName as LastName', 
                                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                    'CRM_MemberConsumers.Suffix as Suffix', 
                                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                                    'CRM_MemberConsumers.Barangay as Barangay', 
                                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                    'CRM_MemberConsumers.Notes as Notes', 
                                    'CRM_MemberConsumers.Gender as Gender', 
                                    'CRM_MemberConsumers.Sitio as Sitio',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Towns.id as TownId',
                                    'CRM_Barangays.Barangay as Barangay',
                                    'CRM_Barangays.id as BarangayId')
                            ->where('CRM_MemberConsumers.Id', $consumerId)
                            ->first();

        $cond = 'new';

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/create_new', [
                'memberConsumer' => $memberConsumer, 
                'cond' => $cond, 
                'towns' => $towns, 
                'accountTypes' => $accountTypes, 
                'crew' => $crew]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }        
    }

    public function fetchserviceconnections(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if ($query != '' ) {
                $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->select('CRM_ServiceConnections.id as ConsumerId',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnections.LoadCategory',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_ServiceConnections.Trash', 'No')
                                            ->orWhereNull('CRM_ServiceConnections.Trash');
                                    })
                    ->where('CRM_ServiceConnections.ServiceAccountName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_ServiceConnections.Id', 'LIKE', '%' . $query . '%')
                    
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->select('CRM_ServiceConnections.id as ConsumerId',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnections.LoadCategory',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_ServiceConnections.Trash', 'No')
                                            ->orWhereNull('CRM_ServiceConnections.Trash');
                                    })
                    ->orderByDesc('CRM_ServiceConnections.created_at')
                    ->take(10)
                    ->get();
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                $output = '';
                foreach ($data as $row) {
                    if ($row->LoadCategory == 'above 5kVa') {
                        $output .= '
                            <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div>
                                                    <h4>' .$row->ServiceAccountName . '</h4>
                                                    <p class="text-muted" style="margin-bottom: 0;">Acount Number: ' . $row->ConsumerId . '</p>
                                                    <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                    <a href="' . route('serviceConnections.show', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="View"><i class="fas fa-eye"></i></a>
                                                    <a href="' . route('serviceConnections.edit', [$row->ConsumerId]) . '" class="text-warning" style="margin-top: 5px; padding: 8px;" title="Edit"><i class="fas fa-pen"></i></a>
                                                </div>     
                                            </div> 

                                            <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #f44336; padding-left: 15px;">
                                                <div>
                                                    <p class="text-muted" style="margin-bottom: 0;">Date of Application: <strong>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">AccountCount: <strong>' . $row->AccountCount . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                </div>     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        ';
                    } else {
                        $output .= '
                            <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div>
                                                    <h4>' .$row->ServiceAccountName . '</h4>
                                                    <p class="text-muted" style="margin-bottom: 0;">Acount Number: ' . $row->ConsumerId . '</p>
                                                    <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                    <a href="' . route('serviceConnections.show', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="View"><i class="fas fa-eye"></i></a>
                                                    <a href="' . route('serviceConnections.edit', [$row->ConsumerId]) . '" class="text-warning" style="margin-top: 5px; padding: 8px;" title="Edit"><i class="fas fa-pen"></i></a>
                                                </div>     
                                            </div> 

                                            <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                                <div>
                                                    <p class="text-muted" style="margin-bottom: 0;">Date of Application: <strong>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">AccountCount: <strong>' . $row->AccountCount . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                </div>     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        ';
                    }                    
                }                
            } else {
                $output = '<p class="text-center">No data found.</p>';
            }

            $data = [
                'table_data' => $output
            ];

            echo json_encode($data);
        }
    }

    public function assessChecklists($id) {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        if ($serviceConnections->AccountType==ServiceConnections::getResidentialId()) {
            $checklist = ServiceConnectionChecklistsRep::where('Notes', 'RESIDENTIAL')->get();
        } else {
            if (floatval($serviceConnections->LoadCategory) > 225) {
                $checklist = ServiceConnectionChecklistsRep::where('Notes', 'NON-RESIDENTIAL ABOVE 5kVA')->get();
            } else {
                $checklist = ServiceConnectionChecklistsRep::where('Notes', 'NON-RESIDENTIAL BELOW 5kVA')->get();
            }
        }
        
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/assess_checklists', ['serviceConnections' => $serviceConnections, 'checklist' => $checklist]);
        } else {
            return abort(403, "You're not authorized to create/update a service connection application.");
        }        
    }

    public function updateChecklists($id) {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        if ($serviceConnections->AccountType==ServiceConnections::getResidentialId()) {
            $checklist = ServiceConnectionChecklistsRep::where('Notes', 'RESIDENTIAL')->get();
        } else {
            if (floatval($serviceConnections->LoadCategory) > 225) {
                $checklist = ServiceConnectionChecklistsRep::where('Notes', 'NON-RESIDENTIAL ABOVE 5kVA')->get();
            } else {
                $checklist = ServiceConnectionChecklistsRep::where('Notes', 'NON-RESIDENTIAL BELOW 5kVA')->get();
            }
        }

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'Super Admin'])) {
            return view('/service_connections/update_checklists', ['serviceConnections' => $serviceConnections, 'checklist' => $checklist]);
        } else {
            return abort(403, "You're not authorized to create/update a service connection application.");
        }         
    }

    public function moveToTrash($id) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            $serviceConnections->Trash = 'Yes';
    
            $serviceConnections->save();
    
            return redirect(route('serviceConnections.index'));
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }          
    }

    public function trash() {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            return view('/service_connections/trash');
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }         
    }

    public function fetchserviceconnectiontrash(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if ($query != '' ) {
                $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                    ->select('CRM_ServiceConnections.id as ConsumerId',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_ServiceConnections.Trash', 'Yes')
                    ->where('CRM_ServiceConnections.ServiceAccountName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_ServiceConnections.Id', 'LIKE', '%' . $query . '%')                    
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                    ->select('CRM_ServiceConnections.id as ConsumerId',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_ServiceConnections.Trash', 'Yes')
                    ->orderByDesc('CRM_ServiceConnections.created_at')
                    ->take(25)
                    ->get();
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                $output = '';
                foreach ($data as $row) {

                    $output .= '
                        <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div>
                                                <h4>' .$row->ServiceAccountName . '</h4>
                                                <p class="text-muted" style="margin-bottom: 0;">Acount Number: ' . $row->ConsumerId . '</p>
                                                <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                <a href="' . route('serviceConnections.restore', [$row->ConsumerId]) . '" class="btn btn-xs btn-primary" title="Restore">
                                                    <i class="fas fa-recycle ico-tab-mini"></i> Restore
                                                </a>
                                            </div>     
                                        </div> 

                                        <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                            <div>
                                                <p class="text-muted" style="margin-bottom: 0;">Date of Application: <strong>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">AccountCount: <strong>' . $row->AccountCount . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Account Type: <strong>' . $row->AccountType . '</strong></p>
                                            </div>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    ';
                }                
            } else {
                $output = '<p class="text-center">No data found.</p>';
            }

            $data = [
                'table_data' => $output
            ];

            echo json_encode($data);
        }
    }

    public function restore($id) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            $serviceConnections->Trash = null;

            $serviceConnections->save();

            return redirect(route('serviceConnections.trash'));
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }         
    }

    public function energization() {
        if (Auth::user()->hasAnyPermission(['sc update energization', 'sc update', 'Super Admin'])) {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')                        
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->select('CRM_ServiceConnections.id as id',
                                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                        'CRM_ServiceConnections.Status as Status',
                                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                        'CRM_ServiceConnections.AccountCount as AccountCount',  
                                        'CRM_ServiceConnections.Sitio as Sitio', 
                                        'CRM_Towns.Town as Town',
                                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                        'CRM_ServiceConnections.EnergizationOrderIssued as EnergizationOrderIssued', 
                                        'CRM_ServiceConnections.StationCrewAssigned as StationCrewAssigned',
                                        'CRM_ServiceConnectionCrew.StationName as StationName',
                                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                                        'CRM_ServiceConnectionCrew.Members as Members',
                                        'CRM_ServiceConnections.Status', 
                                        'CRM_Barangays.Barangay as Barangay')
                        ->whereNotNull('CRM_ServiceConnections.ORNumber')
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Approved', 'Not Energized', 'Downloaded By Crew')")
                        ->whereIn('CRM_ServiceConnections.id', DB::table('CRM_ServiceConnectionMeterAndTransformer')->pluck('ServiceConnectionId'))
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();

            $crew = ServiceConnectionCrew::orderBy('StationName')->get();

            return view('/service_connections/energization', ['serviceConnections' => $serviceConnections, 'crew' => $crew]);
        } else {
            abort(403, 'Access denied');
        }      
    }

    public function changeStationCrew(Request $request) {
        $serviceConnection = ServiceConnections::find($request['id']);

        $serviceConnection->StationCrewAssigned = $request['StationCrewAssigned'];
        $serviceConnection->DateTimeOfEnergizationIssue = date('Y-m-d H:i:s');

        $serviceConnection->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $request['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Station Crew Re-assigned';
        $timeFrame->Notes = 'From ' . $request['FromStationCrewName'] . ' to ' . $request['ToStationCrewName'];
        $timeFrame->save();

        return response()->json([ 'success' => true ], 200);       
    }

    public function updateEnergizationStatus(Request $request) {
        if (request()->ajax()) {
            $serviceConnection = ServiceConnections::find($request['id']);

            $serviceConnection->Status = $request['Status'];
            $serviceConnection->DateTimeOfEnergization = $request['EnergizationDate'];
            $serviceConnection->DateTimeLinemenArrived = $request['ArrivalDate'];

            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $request['id'];
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = $request['Status'];
            $timeFrame->Notes = 'Crew arrived at ' . date('F d, Y h:i:s A', strtotime($request['ArrivalDate'])) . '<br>' . 'Performed energization attempt at ' . date('F d, Y h:i:s A', strtotime($request['EnergizationDate'])) . '<br>' . $request['Reason'];
            $timeFrame->save();

            return response()->json([ 'success' => true ]);
        }
    }

    public function printOrder($id) {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.BuildingType',
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.ORDate as ORDate', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.ElectricianName', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType')
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $checklists = DB::table('CRM_ServiceConnectionChecklist')
            ->leftJoin('CRM_ServiceConnectionChecklistRepository', 'CRM_ServiceConnectionChecklist.ChecklistId', '=', 'CRM_ServiceConnectionChecklistRepository.id')
            ->where('ServiceConnectionId', $serviceConnections->id)
            ->select('CRM_ServiceConnectionChecklistRepository.Checklist')
            ->get();

        $serviceConnectionInspections = ServiceConnectionInspections::where('ServiceConnectionId', $id)
                                ->orderByDesc('created_at')
                                ->first();

        $serviceConnectionMeter = ServiceConnectionMtrTrnsfrmr::where('ServiceConnectionId', $id)->first();

        $received = DB::table('CRM_ServiceConnectionTimeframes')
            ->leftJoin('users', 'CRM_ServiceConnectionTimeframes.UserId', '=', 'users.id')
            ->where('ServiceConnectionId', $id)
            ->where('Status', 'Received')
            ->select('users.name')
            ->orderByDesc('CRM_ServiceConnectionTimeframes.created_at')
            ->first();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $id;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Energization Order Issued';
        $timeFrame->save();

        // UPDATE ENERGIZATION COLUMN IN SEVICE CONNECTIONS;
        $scUpdate = ServiceConnections::find($id);
        $scUpdate->EnergizationOrderIssued = 'Yes';
        $scUpdate->DateTimeOfEnergizationIssue = date('Y-m-d H:i:s');
        $scUpdate->save();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc update energization', 'sc create', 'Super Admin'])) {
            return view('/service_connections/print_order', [
                'serviceConnection' => $serviceConnections, 
                'serviceConnectionInspections' => $serviceConnectionInspections,
                'serviceConnectionMeter' => $serviceConnectionMeter,
                'received' => $received,
                'checklists' => $checklists,
            ]);
        } else {
            return abort(403, "You're not authorized to print an energization order.");
        }         
    }

    public function largeLoadInspections() {
        $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')                    
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')  
                    ->where('CRM_ServiceConnections.Status', 'Forwarded To Planning')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.Status as Status', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.LongSpan as LongSpan', 
                        'CRM_Barangays.Barangay as Barangay')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/large_load_inspections', ['serviceConnections' => $serviceConnections, 'accountTypes' => $accountTypes]);
        } else {
            return abort(403, "You're not authorized to view power load inspections.");
        }           
    }

    public function largeLoadInspectionUpdate(Request $request) {
        if ($request->ajax()) {
            // ADD INSPECTION DATA
            $largeLoadInspections = new ServiceConnectionLgLoadInsp;

            $largeLoadInspections->id = IDGenerator::generateID();
            $largeLoadInspections->ServiceConnectionId = $request['ServiceConnectionId'];
            $largeLoadInspections->Assessment = $request['Assessment'];
            $largeLoadInspections->DateOfInspection = $request['DateOfInspection'];
            $largeLoadInspections->Notes = $request['Notes'];
            $largeLoadInspections->Options = $request['Options'];

            $largeLoadInspections->save();

            // UPDATE SERVICE CONNECTION STATUS
            $serviceConnection = ServiceConnections::find($request['ServiceConnectionId']);

            $serviceConnection->Status = 'For BoM';
            $serviceConnection->AccountType = $request['AccountType'];

            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = $request['Assessment'];
            $timeFrame->Notes = '(Power load inspection) See inspection log # <a href="' . route('serviceConnectionLgLoadInsps.show', [$largeLoadInspections->id]) . '">' . $largeLoadInspections->id . '</a> for further details';
            $timeFrame->save();

            return response()->json([ 'success' => true ]);
        }
    }

    public function bomIndex() {
        $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')                    
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')  
                    ->leftJoin('CRM_LargeLoadInspections', 'CRM_ServiceConnections.id', '=', 'CRM_LargeLoadInspections.ServiceConnectionId')
                    ->where('CRM_ServiceConnections.Status', 'For BoM')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.Status as Status', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_LargeLoadInspections.Options')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/bom_index', ['serviceConnections' => $serviceConnections]);
        } else {
            return abort(403, "You're not authorized to view power load inspections.");
        }         
    }

    public function bomAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $materials = MaterialAssets::orderBy('Description')->get();

        $structures = Structures::orderBy('Data')->get();

        $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_BillOfMaterialsMatrix.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->whereNull('CRM_BillOfMaterialsMatrix.StructureType')
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 

        $structuresAssigned = StructureAssignments::where('ServiceConnectionId', $scId)
            ->whereNotIn('ConAssGrouping', ['9', '1', '3'])            
            ->orderBy('StructureId')->get();
            
        
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/bom_assigning', ['serviceConnection' => $serviceConnection, 
                'structuresAssigned' => $structuresAssigned, 
                'billOfMaterials' => $billOfMaterials,
                'materials' => $materials,
                'structures' => $structures,
            ]);
        } else {
            return abort(403, "You're not authorized to update power load inspections.");
        }         
    }

    public function forwardToTransformerAssigning($scId) {
        $serviceConnection = ServiceConnections::find($scId);

        $serviceConnection->Status = 'For Transformer and Pole Assigning';

        $serviceConnection->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $scId;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Bill of Materials Assigned';
        $timeFrame->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $scId;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'For Transformer and Pole Assigning';
        $timeFrame->save();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return redirect(route('serviceConnections.transformer-assigning', [$scId]));
        } else {
            return abort(403, "You're not authorized to update power load inspections.");
        } 
    }

    public function transformerIndex() { 
        $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')                    
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')  
                    ->where('CRM_ServiceConnections.Status', 'For Transformer and Pole Assigning')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.Status as Status', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_Barangays.Barangay as Barangay')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/transformer_index', ['serviceConnections' => $serviceConnections]);
        } else {
            return abort(403, "You're not authorized to update view load inspections.");
        }         
    }

    public function transformerAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $transformerIndex = DB::table('CRM_TransformerIndex')
            ->leftJoin('CRM_MaterialAssets', 'CRM_TransformerIndex.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialAssets.*',
                    'CRM_TransformerIndex.id as IndexId')
            ->get();

        $transformerMatrix = DB::table('CRM_TransformersAssignedMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_TransformersAssignedMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_TransformersAssignedMatrix.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_TransformersAssignedMatrix.Quantity')
            ->where('CRM_TransformersAssignedMatrix.ServiceConnectionId', $scId)
            ->get();

        $structureBrackets = Structures::where('Type', 'A_DT')->get();

        $bracketsAssigned = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_MaterialAssets.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'A_DT')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get(); 

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/transformer_assigning', ['serviceConnection' => $serviceConnection, 'transformerIndex' => $transformerIndex, 'transformerMatrix' => $transformerMatrix, 'structureBrackets' => $structureBrackets, 'bracketsAssigned' => $bracketsAssigned]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function poleAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $poleIndex = DB::table('CRM_PoleIndex')
            ->leftJoin('CRM_MaterialAssets', 'CRM_PoleIndex.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialAssets.*',
                    'CRM_PoleIndex.id as IndexId',
                    'CRM_PoleIndex.Type as Type')
            ->get();

        $poleAssigned = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')  
            ->select('CRM_BillOfMaterialsMatrix.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_BillOfMaterialsMatrix.Quantity')
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->where('CRM_BillOfMaterialsMatrix.StructureType', 'POLE')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 


        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/pole_assigning', ['serviceConnection' => $serviceConnection, 'poleIndex' => $poleIndex, 'poleAssigned' => $poleAssigned]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function quotationSummary($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnections.TemporaryDurationInMonths as TemporaryDurationInMonths',
                        'CRM_ServiceConnectionCrew.Members as Members')
        ->where('CRM_ServiceConnections.id', $scId)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $materials = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_BillOfMaterialsMatrix.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS Cost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                // ->where('CRM_BillOfMaterialsMatrix.StructureType', 'A_DT')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get();
                
        $poles = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),)
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'POLE')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get();

        $transformers = DB::table('CRM_TransformersAssignedMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_TransformersAssignedMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->select('CRM_MaterialAssets.id',
                        'CRM_TransformersAssignedMatrix.id as TransformerId',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        'CRM_TransformersAssignedMatrix.Quantity',
                        'CRM_TransformersAssignedMatrix.Type')
                ->where('CRM_TransformersAssignedMatrix.ServiceConnectionId', $scId)
                ->get();

        $structures = DB::table('CRM_StructureAssignments')
            ->leftJoin('CRM_Structures', 'CRM_StructureAssignments.StructureId', '=', 'CRM_Structures.Data')
            ->select('CRM_Structures.id as id',
                    'CRM_StructureAssignments.StructureId',
                    DB::raw('SUM(CAST(CRM_StructureAssignments.Quantity AS Integer)) AS Quantity'))
            ->where('ServiceConnectionId', $scId)
            ->groupBy('CRM_Structures.id', 'CRM_StructureAssignments.StructureId')
            ->get();

        $conAss = DB::table('CRM_StructureAssignments')
            ->where('ServiceConnectionId', $scId)
            ->select('ConAssGrouping', 'StructureId', 'Quantity', 'Type')
            ->groupBy('StructureId', 'ConAssGrouping', 'Quantity', 'Type')
            ->orderBy('ConAssGrouping')
            ->get();

        $billOfMaterialsSummary = BillsOfMaterialsSummary::where('ServiceConnectionId', $scId)->first();
        if ($billOfMaterialsSummary == null) {
            $billOfMaterialsSummary = new BillsOfMaterialsSummary;
            $billOfMaterialsSummary->id = IDGenerator::generateID();
            $billOfMaterialsSummary->ServiceConnectionId = $scId;
            $billOfMaterialsSummary->TransformerLaborCostPercentage = '0.035';
            $billOfMaterialsSummary->MaterialLaborCostPercentage = '0.35';
            $billOfMaterialsSummary->HandlingCostPercentage = '0.30';
            $billOfMaterialsSummary->MonthDuration = $serviceConnection != null && $serviceConnection->TemporaryDurationInMonths != null ? $serviceConnection->TemporaryDurationInMonths : '';

            // CALCULATE SUB-TOTAL
            $subTtl = 0.0;
            foreach($materials as $items) { // materials total
                $subTtl += floatval($items->Cost);
            }
            foreach($transformers as $items) { 
                if ($items->Type != 'Transformer') {
                    $subTtl += floatval($items->Quantity) * floatval($items->Amount);
                }
            }

            // transformer sub total
            $transSoloTtl = 0.0;
            foreach($transformers as $items) { 
                if ($items->Type == 'Transformer') {
                    $transSoloTtl += floatval($items->Quantity) * floatval($items->Amount);
                }                
            }

            // sub total
            $billOfMaterialsSummary->SubTotal = $subTtl + $transSoloTtl;

            // transformer total
            $billOfMaterialsSummary->TransformerTotal = $transSoloTtl;

            // transformer labor cost
            $billOfMaterialsSummary->TransformerLaborCost = $transSoloTtl * floatval($billOfMaterialsSummary->TransformerLaborCostPercentage);

            // materials labor cost            
            $billOfMaterialsSummary->MaterialLaborCost = $subTtl * floatval($billOfMaterialsSummary->MaterialLaborCostPercentage);

            // total labor cost except handling (just transformer and material labor costs)
            $billOfMaterialsSummary->LaborCost = floatval($billOfMaterialsSummary->TransformerLaborCost) + floatval($billOfMaterialsSummary->MaterialLaborCost);

            // handling labor cost            
            $billOfMaterialsSummary->HandlingCost = floatval($billOfMaterialsSummary->LaborCost) * floatval($billOfMaterialsSummary->HandlingCostPercentage);

            // overall total
            $billOfMaterialsSummary->Total = floatval($billOfMaterialsSummary->SubTotal) +
                                            floatval($billOfMaterialsSummary->HandlingCost) +
                                            floatval($billOfMaterialsSummary->LaborCost);
            
            // total vat
            $vatAmount = floatval($billOfMaterialsSummary->Total) * BillsOfMaterialsSummary::getVat();

            $billOfMaterialsSummary->Total = $billOfMaterialsSummary->Total + $vatAmount;

            $billOfMaterialsSummary->TotalVAT = $vatAmount;
            
            $billOfMaterialsSummary->save();
        } else {
            $billOfMaterialsSummary->MonthDuration = $serviceConnection->TemporaryDurationInMonths;

            // CALCULATE SUB-TOTAL
            $subTtl = 0.0;
            foreach($materials as $items) { // materials total
                $subTtl += floatval($items->Cost);
            }
            foreach($transformers as $items) { 
                if ($items->Type != 'Transformer') {
                    $subTtl += floatval($items->Quantity) * floatval($items->Amount);
                }
            }

            // transformer sub total
            $transSoloTtl = 0.0;
            foreach($transformers as $items) { 
                if ($items->Type == 'Transformer') {
                    $transSoloTtl += floatval($items->Quantity) * floatval($items->Amount);
                }                
            }

            // sub total
            $billOfMaterialsSummary->SubTotal = $subTtl + $transSoloTtl;

            // materials labor cost            
            $billOfMaterialsSummary->MaterialLaborCost = $subTtl * floatval($billOfMaterialsSummary->MaterialLaborCostPercentage);

            if ($billOfMaterialsSummary->ExcludeTransformerLaborCost == null) {
                $billOfMaterialsSummary->TransformerLaborCost = $transSoloTtl * floatval($billOfMaterialsSummary->TransformerLaborCostPercentage);
                $billOfMaterialsSummary->LaborCost = floatval($billOfMaterialsSummary->TransformerLaborCost) + floatval($billOfMaterialsSummary->MaterialLaborCost);
            } else {
                if ($billOfMaterialsSummary->ExcludeTransformerLaborCost == 'Yes') {
                    $billOfMaterialsSummary->TransformerLaborCost = null;
                    $billOfMaterialsSummary->LaborCost = $billOfMaterialsSummary->MaterialLaborCost;
                } else {
                    $billOfMaterialsSummary->TransformerLaborCost = $transSoloTtl * floatval($billOfMaterialsSummary->TransformerLaborCostPercentage);
                    $billOfMaterialsSummary->LaborCost = floatval($billOfMaterialsSummary->TransformerLaborCost) + floatval($billOfMaterialsSummary->MaterialLaborCost);
                }
            }

            // handling labor cost            
            $billOfMaterialsSummary->HandlingCost = floatval($billOfMaterialsSummary->LaborCost) * floatval($billOfMaterialsSummary->HandlingCostPercentage);

            // overall total
            $billOfMaterialsSummary->Total = floatval($billOfMaterialsSummary->SubTotal) +
                                            floatval($billOfMaterialsSummary->HandlingCost) +
                                            floatval($billOfMaterialsSummary->LaborCost);

            // total vat
            $vatAmount = floatval($billOfMaterialsSummary->Total) * BillsOfMaterialsSummary::getVat();

            $billOfMaterialsSummary->Total = $billOfMaterialsSummary->Total + $vatAmount;

            $billOfMaterialsSummary->TotalVAT = $vatAmount;

            $billOfMaterialsSummary->save();
        }

        return view('/service_connections/quotation_summary', [
                'serviceConnection' => $serviceConnection, 
                'materials' => $materials, 
                'transformers' => $transformers, 
                'billOfMaterialsSummary' => $billOfMaterialsSummary,
                'structures' => $structures,
                'conAss' => $conAss,
                'poles' => $poles,
            ]
        );
    }

    public function spanningAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->where('CRM_BillOfMaterialsMatrix.StructureType', 'SPANNING')
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 
        
        $spanningData = SpanningData::where('ServiceConnectionId', $scId)->first();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/spanning_assigning', [
                'serviceConnection' => $serviceConnection,
                'billOfMaterials' => $billOfMaterials,
                'spanningData' => $spanningData
            ]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function meteringEquipmentAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $specialEquipmentIndex = DB::table('CRM_SpecialEquipmentMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_SpecialEquipmentMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialAssets.*',
                    'CRM_SpecialEquipmentMaterials.id as IndexId')
            ->get();

        $equipmentAssigned = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')  
            ->select('CRM_BillOfMaterialsMatrix.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_BillOfMaterialsMatrix.Quantity')
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->where('CRM_BillOfMaterialsMatrix.StructureType', 'SPEC_EQUIP')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 


        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/metering_equipment_assigning', [
                'serviceConnection' => $serviceConnection,
                'specialEquipmentIndex' => $specialEquipmentIndex,
                'equipmentAssigned' => $equipmentAssigned,
            ]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }          
    }

    public function forwardToVerification($scId) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            $serviceConnection = ServiceConnections::find($scId);
            $serviceConnection->Status = 'For Inspection';
            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $scId;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Forwarded for Verfication';
            $timeFrame->Notes = 'Forwarded to ISD for Verfication';
            $timeFrame->save();

            return redirect(route('serviceConnections.show', [$scId]));
        } else {
            return abort(403, "You're not authorized to forward an application.");
        }        
    }

    public function largeLoadPredefinedMaterials($scId, $options) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')            
            ->leftJoin('CRM_LargeLoadInspections', 'CRM_ServiceConnections.id', '=', 'CRM_LargeLoadInspections.ServiceConnectionId')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_ServiceConnections.AccountApplicationType',
                    'CRM_ServiceConnections.TemporaryDurationInMonths',
                    'CRM_LargeLoadInspections.Options')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $materials = MaterialAssets::orderBy('Description')->get();

        if ($serviceConnection->AccountApplicationType == 'Temporary' && $serviceConnection->Options == 'Transformer Only') {
            $preDefMaterials = DB::table('CRM_PreDefinedMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_PreDefinedMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->where('CRM_PreDefinedMaterials.Options', $options)
            ->where('CRM_PreDefinedMaterials.ApplicationType', $serviceConnection->AccountApplicationType)
            ->select('CRM_PreDefinedMaterials.id',
                    'CRM_PreDefinedMaterials.NEACode',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_PreDefinedMaterials.Quantity',
                    'CRM_PreDefinedMaterials.LaborPercentage',
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2)) * 0.15 * ' . floatval($serviceConnection->TemporaryDurationInMonths) . ') AS Cost'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.LaborPercentage AS DECIMAL(9,4))) AS LaborCost'))
            ->get();
        } else {
            $preDefMaterials = DB::table('CRM_PreDefinedMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_PreDefinedMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->where('CRM_PreDefinedMaterials.Options', $options)
            ->where('CRM_PreDefinedMaterials.ApplicationType', $serviceConnection->AccountApplicationType)
            ->select('CRM_PreDefinedMaterials.id',
                    'CRM_PreDefinedMaterials.NEACode',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_PreDefinedMaterials.Quantity',
                    'CRM_PreDefinedMaterials.LaborPercentage',
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2))) AS Cost'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.LaborPercentage AS DECIMAL(9,4))) AS LaborCost'))
            ->get();
        }

        if ($preDefMaterials != null) {
            $preDef = PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)
                            ->get();
            
            if (count($preDef) < 1) {
                // SAVE PRE DEFINED MATERIALS
                foreach($preDefMaterials as $item) {
                    $preDef = PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)
                                ->where('NEACode', $item->NEACode)
                                ->first();
                    if ($preDef == null) {
                        $preDef = new PreDefinedMaterialsMatrix;
                        $preDef->id = IDGenerator::generateID();
                        $preDef->ServiceConnectionId = $scId;
                        $preDef->NEACode = $item->NEACode;
                        $preDef->Description = $item->Description;
                        $preDef->Quantity = $item->Quantity;
                        $preDef->Options = $options;
                        $preDef->ApplicationType = $serviceConnection->AccountApplicationType;
                        $preDef->Cost = $item->Cost;
                        $preDef->LaborCost = $item->LaborCost;
                        $preDef->Amount = $item->Amount;
                        $preDef->LaborPercentage = $item->LaborPercentage;
                        $preDef->save();
                    } else {
                        $preDef->ServiceConnectionId = $scId;
                        $preDef->NEACode = $item->NEACode;
                        $preDef->Description = $item->Description;
                        $preDef->Quantity = $item->Quantity;
                        $preDef->Options = $options;
                        $preDef->ApplicationType = $serviceConnection->AccountApplicationType;
                        $preDef->Cost = $item->Cost;
                        $preDef->LaborCost = $item->LaborCost;
                        $preDef->Amount = $item->Amount;
                        $preDef->LaborPercentage = $item->LaborPercentage;
                        $preDef->save();
                    }                
                }
            }            
        }

        $preDef = PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)
                            ->get();


        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/largeload_predefined_materials', 
            [
                'serviceConnection' => $serviceConnection,
                'materials' => $materials,
                'preDefMaterials' => $preDefMaterials,
                'preDef' => $preDef,
            ]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function fleetMonitor() {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc view', 'sc powerload view', 'view membership', 'view metering data', 'Super Admin'])) {
            return view('/service_connections/fleet_monitor');
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function dailyMonitor() {
        return view('/service_connections/daily_monitor');
    }

    public function fetchDailyMonitorApplicationsData(Request $request) {
        // if ($request->ajax()) {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->select('CRM_ServiceConnections.id as id',
                            'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                            'CRM_ServiceConnections.DateOfApplication as DateOfApplication',
                            'CRM_ServiceConnections.Sitio as Sitio', 
                            'CRM_Towns.Town as Town',
                            'CRM_Barangays.Barangay as Barangay')
                ->where('CRM_ServiceConnections.DateOfApplication', $request['DateOfApplication'])
                ->where(function ($query) {
                    $query->where('CRM_ServiceConnections.Trash', 'No')
                        ->orWhereNull('CRM_ServiceConnections.Trash');
                })
                ->get();
                
                if (count($serviceConnections) > 0) {
                    $output = '';
                    $count = 1;
                    foreach ($serviceConnections as $row) {    
                        $output .= '
                            <tr>
                                <td>' . $count . '</td>
                                <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                                <td>' . $row->ServiceAccountName . '</td>
                                <td>' . ServiceConnections::getAddress($row) . '</td>
                            </tr>   
                        ';
                        $count++;
                    }                
                } else {
                    $output = '';
                }

            return response()->json($output, 200);
        // }
    }

    public function fetchDailyMonitorEnergizedData(Request $request) {
        // if ($request->ajax()) {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->select('CRM_ServiceConnections.id as id',
                            'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                            'CRM_ServiceConnections.Sitio as Sitio', 
                            'CRM_Towns.Town as Town',
                            'CRM_Barangays.Barangay as Barangay')
                ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['DateOfEnergization'] . ' 00:01:00', $request['DateOfEnergization'] . ' 23:59:00'])
                ->where(function ($query) {
                    $query->where('CRM_ServiceConnections.Trash', 'No')
                        ->orWhereNull('CRM_ServiceConnections.Trash');
                })
                ->get();
                
                if (count($serviceConnections) > 0) {
                    $output = '';
                    $count = 1;
                    foreach ($serviceConnections as $row) {    
                        $output .= '
                            <tr>
                                <td>' . $count . '</td>
                                <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                                <td>' . $row->ServiceAccountName . '</td>
                                <td>' . ServiceConnections::getAddress($row) . '</td>
                            </tr>   
                        ';
                        $count++;
                    }                
                } else {
                    $output = '';
                }

            return response()->json($output, 200);
        // }
    }

    public function applicationsReport() {
        return view('/service_connections/applications_report');
    }

    public function fetchApplicationsReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Office', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        }
        
            
        if (count($serviceConnections) > 0) {
            $output = '';
            $count = 1;
            foreach ($serviceConnections as $row) {    
                $output .= '
                    <tr>
                        <td>' . $count . '</td>
                        <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                        <td>' . $row->ServiceAccountName . '</td>
                        <td>' . ServiceConnections::getAddress($row) . '</td>
                        <td>' . $row->Office . '</td>
                        <td>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</td>
                    </tr>   
                ';
                $count++;
            }                
        } else {
            $output = '';
        }

        return response()->json($output, 200);
    }

    public function downloadApplicationsReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select(DB::raw("CONCAT(CRM_ServiceConnections.id, ' ') as id"),
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_Towns.Town as Town',                        
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select("CONCAT('''', CRM_ServiceConnections.id) as id",
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Office', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        }

        $export = new ServiceConnectionApplicationsReportExport($serviceConnections->toArray());

        return Excel::download($export, 'Applications-Report.xlsx');
    }

    public function energizationReport() {
        return view('/service_connections/energization_report');
    }

    public function fetchEnergizationReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Office', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        }
        
            
        if (count($serviceConnections) > 0) {
            $output = '';
            $count = 1;
            foreach ($serviceConnections as $row) {    
                $output .= '
                    <tr>
                        <td>' . $count . '</td>
                        <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                        <td>' . $row->ServiceAccountName . '</td>
                        <td>' . ServiceConnections::getAddress($row) . '</td>
                        <td>' . $row->Office . '</td>
                        <td>' . date('F d, Y @ h:i:s A', strtotime($row->DateTimeOfEnergization)) . '</td>
                    </tr>   
                ';
                $count++;
            }                
        } else {
            $output = '';
        }

        return response()->json($output, 200);
    }

    public function downloadEnergizationReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select(DB::raw("CONCAT(CRM_ServiceConnections.id, ' ') as id"),
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select(DB::raw("CONCAT(CRM_ServiceConnections.id, ' ') as id"),
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio',
                        'CRM_Barangays.Barangay as Barangay', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Office', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        }

        $export = new ServiceConnectionEnergizationReportExport($serviceConnections->toArray());

        return Excel::download($export, 'Energization-Report.xlsx');
    }

    public function fetchApplicationCountViaStatus(Request $request) {
        $startDate = date('Y-m-d', strtotime('first day of this month'));
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->select(DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . $startDate . "' AND '" . date('Y-m-d', strtotime($startDate . ' +1 month')) . "') AS 'ApplicationOne'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "' AND '" . $startDate . "') AS 'ApplicationTwo'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "') AS 'ApplicationThree'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "') AS 'ApplicationFour'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "') AS 'ApplicationFive'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-5 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "') AS 'ApplicationSix'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . $startDate . "' AND '" . date('Y-m-d', strtotime($startDate . ' +1 month')) . "') AS 'EnergizationOne'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "' AND '" . $startDate . "') AS 'EnergizationTwo'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "') AS 'EnergizationThree'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "') AS 'EnergizationFour'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "') AS 'EnergizationFive'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-5 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "') AS 'EnergizationSix'"),)
            ->limit(1)
            ->get();
    
        return response()->json($serviceConnections, 200);
    }

    public function printServiceConnectionApplication($id) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.TypeOfOccupancy',
                        'CRM_ServiceConnections.ResidenceNumber',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnectionCrew.Members as Members')
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        if ($serviceConnection != null) {
            $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as Id',
                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                    'CRM_MemberConsumers.FirstName as FirstName', 
                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                    'CRM_MemberConsumers.LastName as LastName', 
                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                    'CRM_MemberConsumers.Suffix as Suffix', 
                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                    'CRM_MemberConsumers.Barangay as Barangay', 
                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                    'CRM_MemberConsumers.OrganizationRepresentative', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $serviceConnection->MemberConsumerId)
            ->first();

            $transactionIndex = TransactionIndex::where('ServiceConnectionId', $id)->first();

            if ($transactionIndex != null) {
                $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndex->id)->get();
            } else {
                $transactionDetails = null;
            }

            if ($memberConsumer != null) {
                $spouse = MemberConsumerSpouse::where('MemberConsumerId', $serviceConnection->MemberConsumerId)->first();
            } else {
                $spouse = null;
            }

            return view('/service_connections/print_service_connection_application', [
                'serviceConnection' => $serviceConnection,
                'memberConsumer' => $memberConsumer,
                'spouse' => $spouse,
                'transactionIndex' => $transactionIndex,
                'transactionDetails' => $transactionDetails,
            ]);
        } else {
            return abort(404, 'Service connection application not found!');
        }
    }  
    
    public function printServiceConnectionContract($id) { 
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->join('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.TypeOfOccupancy',
                        'CRM_ServiceConnections.ResidenceNumber',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnectionCrew.Members as Members')
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        if ($serviceConnection != null) {
            $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as Id',
                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                    'CRM_MemberConsumers.FirstName as FirstName', 
                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                    'CRM_MemberConsumers.LastName as LastName', 
                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                    'CRM_MemberConsumers.Suffix as Suffix', 
                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                    'CRM_MemberConsumers.Barangay as Barangay', 
                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                    'CRM_MemberConsumers.OrganizationRepresentative', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $serviceConnection->MemberConsumerId)
            ->first();

            if ($memberConsumer != null) {
                $spouse = MemberConsumerSpouse::where('MemberConsumerId', $serviceConnection->MemberConsumerId)->first();
            } else {
                $spouse = null;
            }

            return view('/service_connections/print_service_connection_contract', [
                'serviceConnection' => $serviceConnection,
                'memberConsumer' => $memberConsumer,
                'spouse' => $spouse,
            ]);
        } else {
            return abort(404, 'Service connection application not found!');
        }
    }

    public function relocationSearch(Request $request) {
        if ($request['params'] == null) {
            $serviceAccounts = DB::connection('sqlsrvbilling')->table('AccountMaster')
                        ->select('AccountMaster.*')
                        ->orderBy('AccountNumber')
                        ->paginate(20);
        } else {
            $serviceAccounts = DB::connection('sqlsrvbilling')->table('AccountMaster')
                        ->select('AccountMaster.*')
                        ->where('ConsumerName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('AccountNumber', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('MeterNumber', 'LIKE', '%' . $request['params'] . '%')
                        ->orderBy('AccountNumber')
                        ->paginate(20);
        }     

        return view('/service_connections/relocation_search', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function createRelocation($id) {
        $account = ServiceAccounts::find($id);

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        return view('/service_connections/create_relocation', [
            'account' => $account,
            'towns' => $towns,
            'accountTypes' => $accountTypes,
            'crew' => $crew,
        ]);
    }

    public function changeNameSearch(Request $request) {
        if ($request['params'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        } else {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        }     

        return view('/service_connections/change_name_search', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function createChangeName($id) {
        $account = ServiceAccounts::find($id);

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        return view('/service_connections/create_change_name', [
            'account' => $account,
            'towns' => $towns,
            'accountTypes' => $accountTypes,
            'crew' => $crew,
        ]);
    }

    public function storeChangeName(CreateServiceConnectionsRequest $request) {
        $input = $request->all();

        $serviceConnections = $this->serviceConnectionsRepository->create($input);

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $input['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Received';
        $timeFrame->save();

        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateIDandRandString();
        $timeFrame->ServiceConnectionId = $input['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Forwarded to Teller For Payment';
        $timeFrame->save();

        // CREATE PAYMENT TRANSACTIONS
        $paymentParticulars = ServiceConnectionPayParticulars::all();
        $subTotal = 0.0;
        $vatTotal = 0.0;
        $overAllTotal = 0.0;
        $totalTransactions = new ServiceConnectionTotalPayments;
        $totalTransactions->id = IDGenerator::generateIDandRandString();
        $totalTransactions->ServiceConnectionId = $input['id'];
        $totalTransactions->SubTotal = $subTotal;
        $totalTransactions->TotalVat = $vatTotal;
        $totalTransactions->Total = $overAllTotal;
        $totalTransactions->save();
        

        Flash::success('Service Connections saved successfully.');

        // return redirect(route('serviceConnectionInspections.create-step-two', [$input['id']]));
        return redirect(route('serviceConnections.show', [$input['id']]));
    }

    public function approveForChangeName($id) {
        $serviceConnection = ServiceConnections::find($id);

        if ($serviceConnection != null) {
            $serviceConnection->Status = 'Approved For Change Name';
            $serviceConnection->DateTimeOfEnergization = date('Y-m-d H:i:s');
            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $serviceConnection->id;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Approved For Change Name';
            $timeFrame->save();
        }

        return redirect(route('serviceConnections.show', [$serviceConnection->id]));
    }

    public function bypassApproveInspection($inspectionId) {
        $inspection = ServiceConnectionInspections::find($inspectionId);

        if ($inspection != null) {
            $inspection->Status = 'Approved';
            $inspection->save();

            $sc = ServiceConnections::find($inspection->ServiceConnectionId);

            if ($sc != null) {
                $sc->Status = 'Approved';
                $sc->save();

                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $sc->id;
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = 'Approved';
                $timeFrame->Notes = 'Bypassed Approval';
                $timeFrame->save();
            }

            return redirect(route('serviceConnections.show', [$inspection->ServiceConnectionId]));
        } else {
            return abort('Inspection not found!', 404);
        }
    }

    public function saveElectricianInfo(Request $request) {
        $id = $request['id'];

        $serviceConnection = ServiceConnections::find($id);

        if ($serviceConnection != null) {
            if ($request['ElectricianAcredited'] == 'Yes') {
                $serviceConnection->ElectricianId = $request['ElectricianId'];
            } else {
                $serviceConnection->ElectricianId = null;                
            }
            
            $serviceConnection->ElectricianName = $request['ElectricianName'];
            $serviceConnection->ElectricianAddress = $request['ElectricianAddress'];
            $serviceConnection->ElectricianAcredited = $request['ElectricianAcredited'];
            $serviceConnection->ElectricianContactNo = $request['ElectricianContactNo'];
            $serviceConnection->save();
        }

        return response()->json('ok', 200);
    }

    public function printInvoice($id) {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.BuildingType', 
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.LongSpan', 
                        'CRM_ServiceConnections.AccountType AS AccountTypeRaw', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnectionCrew.Members as Members',
                        'CRM_ServiceConnections.ElectricianId',
                        'CRM_ServiceConnections.ElectricianName',
                        'CRM_ServiceConnections.ElectricianAddress',
                        'CRM_ServiceConnections.ElectricianContactNo',
                        'CRM_ServiceConnections.ElectricianAcredited',)
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $laborWiringCharges = DB::table('CRM_ServiceConnectionMaterialPayables')
            ->leftJoin('CRM_ServiceConnectionMaterialPayments', 'CRM_ServiceConnectionMaterialPayables.id', '=', 'CRM_ServiceConnectionMaterialPayments.Material')
            ->where('CRM_ServiceConnectionMaterialPayments.ServiceConnectionId', $id)
            ->select('CRM_ServiceConnectionMaterialPayables.Material',
                'CRM_ServiceConnectionMaterialPayables.Rate',
                'CRM_ServiceConnectionMaterialPayments.Quantity',
                'CRM_ServiceConnectionMaterialPayments.Vat',
                'CRM_ServiceConnectionMaterialPayments.Total'
            )
            ->get();

        $billDeposit = BillDeposits::where('ServiceConnectionId', $id)
            ->first();

        $totalTransactions = ServiceConnectionTotalPayments::where('ServiceConnectionId', $id)->first();

        $particularPayments = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
                    ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
                    ->select('CRM_ServiceConnectionParticularPaymentsTransactions.id',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Vat',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Total',
                            'CRM_ServiceConnectionPaymentParticulars.Particular')
                    ->where('CRM_ServiceConnectionParticularPaymentsTransactions.ServiceConnectionId', $id)
                    ->get();

        return view('/service_connections/print_invoice', [
            'serviceConnections' => $serviceConnections,
            'laborWiringCharges' => $laborWiringCharges,
            'billDeposit' => $billDeposit,
            'totalTransactions' => $totalTransactions,
            'particularPayments' => $particularPayments,
        ]);
    }

    public function inspectionFullReport() {
        return view('/service_connections/inspection_report_full', [
            'inspectors' => User::role('Inspector')->get(),
        ]);
    }

    public function getInspectionSummaryData(Request $request) {
        $month = $request['ServicePeriod'];
        $from = date('Y-m-d', strtotime($month));
        $to = date('Y-m-d', strtotime($from . ' +1 month'));

        $summary = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->select('users.name', 'users.id',
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND b.ConnectionApplicationType NOT IN('Relocation') AND a.Status='FOR INSPECTION' AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS ForInspection"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND b.ConnectionApplicationType NOT IN('Relocation') AND a.Status='Approved' AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS ApprovedThisMonth"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND b.ConnectionApplicationType NOT IN('Relocation') AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Total"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND b.ConnectionApplicationType NOT IN('Relocation') AND a.Inspector=users.id AND (a.created_at BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime('tomorrow')) . "')) AS Today"),
                DB::raw("(SELECT AVG(DATEDIFF(day, b.DateOfApplication, a.DateOfVerification)) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND b.ConnectionApplicationType NOT IN('Relocation') AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS AverageHours"),
            )
            ->groupBy('users.name', 'users.id')
            ->orderBy('users.name')
            ->get();

        $days = round((strtotime($to) - strtotime($from)) / (60 * 60 * 24));

        $output = "";
        foreach($summary as $item) {
            if ($item->name != null) {
                $output .= "<tr>
                                <td>" . $item->name . "</td>
                                <td class='text-right'>" . $item->Today . "</td>
                                <td class='text-right'>" . $item->ForInspection . "</td>
                                <td class='text-right'>" . $item->ApprovedThisMonth . "</td>
                                <th class='text-right text-success'>" . $item->Total . "</th>
                                <td class='text-right'>" . $days . "</td>
                                <th class='text-right text-primary'>" . ($item->Total != null && intval($item->Total) > 0 ? round(intval($item->Total)/$days, 2) : 0) . "</th>
                            </tr>";
            }            
        }

        return response()->json($output, 200);
    }

    public function getInspectionSummaryDataCalendar(Request $request) {
        $month = $request['ServicePeriod'];
        $from = date('Y-m-d', strtotime($month));
        $to = date('Y-m-d', strtotime($from . ' +1 month'));
        $inspector = $request['Inspector'];

        $data = DB::table('CRM_ServiceConnectionInspections')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->whereRaw("Inspector='" . $inspector . "'")
            ->select(
                DB::raw("TRY_CAST(DateOfVerification AS DATE) AS DateOfVerification"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND b.ConnectionApplicationType NOT IN('Relocation') 
                    AND TRY_CAST(a.DateOfVerification AS DATE)=TRY_CAST(CRM_ServiceConnectionInspections.DateOfVerification AS DATE) AND a.Inspector='" . $inspector . "') AS Count"),
            )
            ->groupBy(DB::raw("TRY_CAST(DateOfVerification AS DATE)"))
            ->orderBy(DB::raw("TRY_CAST(DateOfVerification AS DATE)"))
            ->get();

        return response()->json($data, 200);
    }

    public function energizationPerBarangay(Request $request) {
        $month = isset($request['Month']) ? $request['Month'] : '01';
        $year = isset($request['Year']) ? $request['Year'] : '1991';

        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $data = DB::table('CRM_Barangays')            
            ->leftJoin('CRM_Towns', 'CRM_Barangays.TownId', '=', 'CRM_Towns.id')
            ->select(
                'CRM_Barangays.Barangay', 
                'CRM_Towns.Town',
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization IS NOT NULL AND 
                    (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND 
                    (Trash IS NULL OR Trash='No') AND Barangay=CRM_Barangays.id) AS ConsumerCount"),
            )
            ->orderBy('CRM_Towns.Town')
            ->orderBy('CRM_Barangays.Barangay')
            ->get();

        return view('/service_connections/energization_per_barangay', [
            'data' => $data,
        ]);
    } 

    public function downloadEnergizationPerBarangay($month, $year) {
        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $data = DB::table('CRM_Barangays')            
            ->leftJoin('CRM_Towns', 'CRM_Barangays.TownId', '=', 'CRM_Towns.id')
            ->select(
                'CRM_Barangays.Barangay', 
                'CRM_Towns.Town',
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization IS NOT NULL AND 
                    (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND 
                    (Trash IS NULL OR Trash='No') AND Barangay=CRM_Barangays.id) AS ConsumerCount"),
            )
            ->orderBy('CRM_Towns.Town')
            ->orderBy('CRM_Barangays.Barangay')
            ->get();

        $headers = [
            'Barangay',
            'Town',
            'ConsumerCount',
        ];

        $export = new DynamicExport($data->toArray(), $headers, null, 'Energization Report per Barangay for ' . date('F Y', strtotime($year . '-' . $month . '-01')));

        return Excel::download($export, 'Energization-Report-per-Barangay.xlsx');
    }

    public function crewAssigning(Request $request) {
        $office = isset($request['Office']) ? $request['Office'] : env('APP_LOCATION');

        $data = DB::table('CRM_ServiceConnections')                
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')   
            ->whereRaw("StationCrewAssigned IS NULL AND CRM_ServiceConnections.Status NOT IN('Energized', 'Closed') AND (Trash IS NULL OR Trash='No') AND Office='" . $office . "'") 
            ->whereRaw("CRM_ServiceConnections.created_at > '2023-02-28' AND ConnectionApplicationType NOT IN ('Relocation')")
            ->select(
                'CRM_ServiceConnections.id',
                'ServiceAccountName',
                'Sitio',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'DateOfApplication',
                'CRM_ServiceConnections.Status',
                'CRM_ServiceConnectionAccountTypes.AccountType',
                'Office',
                'LoadCategory')
            ->orderBy('DateOfApplication')
            ->get();     
            
        $crews = ServiceConnectionCrew::orderBy('StationName')->get();

        return view('/service_connections/crew_assigning', [
            'data' => $data,
            'crew' => $crews,
        ]);
    }

    public function assignCrew(Request $request) {
        $serviceConnection = ServiceConnections::find($request['id']);

        $serviceConnection->StationCrewAssigned = $request['StationCrewAssigned'];

        $serviceConnection->save();
        
        $crew = ServiceConnectionCrew::find($request['StationCrewAssigned']);

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $request['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Station Crew Assigned';
        if ($crew != null) {
            $timeFrame->Notes = 'Application assigned to crew ' . $crew->StationName;
        }        
        $timeFrame->save();

        return response()->json('ok', 200);       
    }

    public function energizationPerTown(Request $request) {
        $month = isset($request['Month']) ? $request['Month'] : '01';
        $year = isset($request['Year']) ? $request['Year'] : '1991';

        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $data = DB::table('CRM_Towns')    
            ->select(
                'CRM_Towns.Town',
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization IS NOT NULL AND 
                    (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND 
                    (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id) AS ConsumerCount"),
            )
            ->orderBy('CRM_Towns.Town')
            ->get();

        return view('/service_connections/energization_per_town', [
            'data' => $data,
        ]);
    }

    public function downloadEnergizationPerTown($month, $year) {
        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $data = DB::table('CRM_Towns')    
            ->select(
                'CRM_Towns.Town',
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization IS NOT NULL AND 
                    (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND 
                    (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id) AS ConsumerCount"),
            )
            ->orderBy('CRM_Towns.Town')
            ->get();

        $headers = [
            'Town',
            'ConsumerCount',
        ];

        $export = new DynamicExport($data->toArray(), $headers, null, 'Energization Report per Town for ' . date('F Y', strtotime($year . '-' . $month . '-01')));

        return Excel::download($export, 'Energization-Report-per-Town.xlsx');
    }

    public function meterInstallation(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $office = $request['Office'];

        if ($office == 'All') {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')                
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(Trash IS NULL OR Trash='No') AND (CRM_ServiceConnectionMeterAndTransformer.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnections.id',
                    'ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'DateTimeOfEnergization',
                    'MeterSerialNumber',
                    'MeterBrand',
                    'MeterSealNumber',
                    'TypeOfMetering')
                ->orderBy('CRM_ServiceConnectionMeterAndTransformer.created_at')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')                
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(Trash IS NULL OR Trash='No') AND Office='" . $office . "' AND (CRM_ServiceConnectionMeterAndTransformer.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnections.id',
                    'ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'DateTimeOfEnergization',
                    'MeterSerialNumber',
                    'MeterBrand',
                    'MeterSealNumber',
                    'TypeOfMetering')
                ->orderBy('CRM_ServiceConnectionMeterAndTransformer.created_at')
                ->get();
        }

        return view('/service_connections/meter_installations', [
            'data' => $data,
        ]);
    }

    public function downloadMeterInstallation($from, $to, $office) {
        if ($office == 'All') {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')                
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(Trash IS NULL OR Trash='No') AND (CRM_ServiceConnectionMeterAndTransformer.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnections.id',
                    'ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'DateTimeOfEnergization',
                    'MeterSerialNumber',
                    'MeterBrand',
                    'MeterSealNumber',
                    'TypeOfMetering')
                ->orderBy('CRM_ServiceConnectionMeterAndTransformer.created_at')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')                
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(Trash IS NULL OR Trash='No') AND Office='" . $office . "' AND (CRM_ServiceConnectionMeterAndTransformer.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnections.id',
                    'ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'DateTimeOfEnergization',
                    'MeterSerialNumber',
                    'MeterBrand',
                    'MeterSealNumber',
                    'TypeOfMetering')
                ->orderBy('CRM_ServiceConnectionMeterAndTransformer.created_at')
                ->get();
        }

        $arr = [];
        $rows = count($data);
        $i=1;
        foreach($data as $item) {
            array_push($arr, [
                'No' => $i,
                'DateAssigned' => date('M d, Y h:m:s A', strtotime($item->created_at)),
                'TurnOn' => $item->id,
                'Name' => $item->ServiceAccountName,
                'Barangay' => $item->Barangay,
                'Town' => $item->Town,
                'DateEnergized' => date('M d, Y h:m:s A', strtotime($item->DateTimeOfEnergization)),
                'MeterNo' => $item->MeterSerialNumber,
                'Brand' => $item->MeterBrand,
                'Seal' => $item->MeterSealNumber,
                'Type' => $item->TypeOfMetering,
            ]);
            $i++;
        }

        $headers = [
            'No',
            "Date of Meter Assigning",
            "Turn On ID",
            "Consumer Name",
            "Barangay",
            "Town",
            "Date Energized",
            "Meter Number",
            "Meter Brand",
            "Meter Seal",
            "Metering Type",
        ];

        $styles = [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            7 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            'A7:K' . ($rows + 7) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ]
            ]
        ];

        $export = new DynamicExport($arr, $headers, $styles, 'METER INSTALLATION REPORT FROM ' . date('M d, Y', strtotime($from)) . ' TO ' . date('M d, Y', strtotime($to)));

        return Excel::download($export, 'Meter-Installation-Report.xlsx');
    }
    
    public function updateStatus(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        ServiceConnections::where('id', $id)
            ->update(['Status' => $status]);

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $id;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = $status;
        $timeFrame->Notes = 'Status updated manually';
        $timeFrame->save();

        return response()->json('ok', 200);
    }

    public function updateOR(Request $request) {
        $id = $request['id'];
        $or = $request['ORNumber'];
        $orDate = $request['ORDate'];

        ServiceConnections::where('id', $id)
            ->update(['ORNumber' => $or, 'ORDate' => $orDate]);


        return response()->json('ok', 200);
    }

    public function summaryReport(Request $request) {
        $month = $request['Month'];
        $year = $request['Year'];
        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));
        $prevMonthPeriod = date('Y-m-01', strtotime($from . ' -1 month'));

        if (isset($month)) {
            $data = DB::table('CRM_Towns')
                ->select(
                    'id',
                    'Town',
                    DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town=CRM_Towns.id AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No')) AS TotalApplicants"),
                    DB::raw("(SELECT COUNT(i.id) FROM CRM_ServiceConnections s LEFT JOIN CRM_ServiceConnectionInspections i ON s.id=i.ServiceConnectionId 
                        WHERE s.Town=CRM_Towns.id AND (s.DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (s.Trash IS NULL OR s.Trash='No') AND i.Status='Approved') AS ApprovedThisMonth"),
                    DB::raw("(SELECT COUNT(i.id) FROM CRM_ServiceConnections s LEFT JOIN CRM_ServiceConnectionInspections i ON s.id=i.ServiceConnectionId 
                        WHERE s.Town=CRM_Towns.id AND (s.DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (s.Trash IS NULL OR s.Trash='No') AND i.Status='FOR INSPECTION') AS ForInspectionThisMonth"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town=CRM_Towns.id AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Status IN ('Energized', 'Closed')) AS ExecutedThisMonth"),
                    DB::raw("(SELECT COUNT(i.id) FROM CRM_ServiceConnections s LEFT JOIN CRM_ServiceConnectionInspections i ON s.id=i.ServiceConnectionId 
                        WHERE s.Town=CRM_Towns.id AND i.DateOfVerification IS NOT NULL AND (TRY_CAST(i.DateOfVerification AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND (s.Trash IS NULL OR s.Trash='No') AND i.Status='Approved') AS TotalInspections"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town=CRM_Towns.id AND (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Status IN ('Energized', 'Closed')) AS TotalEnergizations"),
                )
                ->orderBy('Town')
                ->get();

            $summaryData = DB::table('CRM_Towns')
                    ->select(
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE EnergizationOrderIssued='Yes' AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Office='MAIN OFFICE') AS EOIssuedMain"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE EnergizationOrderIssued='Yes' AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Office='SUB-OFFICE') AS EOIssuedSub"),                        
                    )
                    ->first();

            $billsSummary = DB::connection('sqlsrvbilling')
                    ->table('Bills')
                    ->select(
                        DB::connection('sqlsrvbilling')->raw("(SELECT COUNT(AccountNumber) FROM Bills WHERE ServicePeriodEnd='" . $prevMonthPeriod . "') AS PrevMonthBillsTotal"),
                        DB::raw("'" . $prevMonthPeriod . "' AS PrevMonth"),
                        DB::connection('sqlsrvbilling')->raw("(SELECT COUNT(AccountNumber) FROM Bills WHERE ServicePeriodEnd='" . $from . "') AS BillsTotalAsOf"),
                        DB::raw("'" . $from . "' AS CurrentMonth"),
                    )
                    ->first();
        } else {
            $data = [];
            $summaryData = null;
            $billsSummary = null;
        }

        return view('/service_connections/summary_report', [
            'data' => $data,
            'summaryData' => $summaryData,
            'billsSummary' => $billsSummary,
        ]);
    }

    public function downloadSummaryReport($month, $year) {
        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));
        $prevMonthPeriod = date('Y-m-01', strtotime($from . ' -1 month'));

        if (isset($month)) {
            $data = DB::table('CRM_Towns')
                ->select(
                    'Town',
                    DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town=CRM_Towns.id AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No')) AS TotalApplicants"),
                    DB::raw("(SELECT COUNT(i.id) FROM CRM_ServiceConnections s LEFT JOIN CRM_ServiceConnectionInspections i ON s.id=i.ServiceConnectionId 
                        WHERE s.Town=CRM_Towns.id AND (s.DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (s.Trash IS NULL OR s.Trash='No') AND i.Status='Approved') AS ApprovedThisMonth"),
                    DB::raw("(SELECT COUNT(i.id) FROM CRM_ServiceConnections s LEFT JOIN CRM_ServiceConnectionInspections i ON s.id=i.ServiceConnectionId 
                        WHERE s.Town=CRM_Towns.id AND (s.DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (s.Trash IS NULL OR s.Trash='No') AND i.Status='FOR INSPECTION') AS ForInspectionThisMonth"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town=CRM_Towns.id AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Status IN ('Energized', 'Closed')) AS ExecutedThisMonth"),
                    DB::raw("(SELECT COUNT(i.id) FROM CRM_ServiceConnections s LEFT JOIN CRM_ServiceConnectionInspections i ON s.id=i.ServiceConnectionId 
                        WHERE s.Town=CRM_Towns.id AND i.DateOfVerification IS NOT NULL AND (TRY_CAST(i.DateOfVerification AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND (s.Trash IS NULL OR s.Trash='No') AND i.Status='Approved') AS TotalInspections"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town=CRM_Towns.id AND (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Status IN ('Energized', 'Closed')) AS TotalEnergizations"),
                )
                ->orderBy('Town')
                ->get();

            $summaryData = DB::table('CRM_Towns')
                    ->select(
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE EnergizationOrderIssued='Yes' AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Office='MAIN OFFICE') AS EOIssuedMain"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE EnergizationOrderIssued='Yes' AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND (Trash IS NULL OR Trash='No') AND Office='SUB-OFFICE') AS EOIssuedSub"),                        
                    )
                    ->first();

            $billsSummary = DB::connection('sqlsrvbilling')
                    ->table('Bills')
                    ->select(
                        DB::connection('sqlsrvbilling')->raw("(SELECT COUNT(AccountNumber) FROM Bills WHERE ServicePeriodEnd='" . $prevMonthPeriod . "') AS PrevMonthBillsTotal"),
                        DB::raw("'" . $prevMonthPeriod . "' AS PrevMonth"),
                        DB::connection('sqlsrvbilling')->raw("(SELECT COUNT(AccountNumber) FROM Bills WHERE ServicePeriodEnd='" . $from . "') AS BillsTotalAsOf"),
                        DB::raw("'" . $from . "' AS CurrentMonth"),
                    )
                    ->first();
        } else {
            $data = [];
            $summaryData = null;
            $billsSummary = null;
        }


        $applicants = 0;
        $approvedThisMonth = 0;
        $inspectionThisMonth = 0;
        $executedThisMonth = 0;
        $inspectionsTotal = 0;
        $energizationsTotal = 0;
        foreach ($data as $item) {
            $applicants += intval($item->TotalApplicants);
            $approvedThisMonth += intval($item->ApprovedThisMonth);
            $inspectionThisMonth += intval($item->ForInspectionThisMonth);
            $executedThisMonth += intval($item->ExecutedThisMonth);
            $inspectionsTotal += intval($item->TotalInspections);
            $energizationsTotal += intval($item->TotalEnergizations);
        }

        $data = $data->toArray();

        array_push($data, [
            "Town" => 'TOTAL',
            "TotalApplicants" => $applicants,
            "ApprovedThisMonth" => $approvedThisMonth,
            "ForInspectionThisMonth" => $inspectionThisMonth,
            "ExecutedThisMonth" => $executedThisMonth,
            "TotalInspections" => $inspectionsTotal,
            "TotalEnergizations" => $energizationsTotal,
        ]);

        $export = new SummaryReportExport($data, $from, $summaryData, $billsSummary);

        $monthSpelled = date('F-Y', strtotime($from));

        return Excel::download($export, 'Housewiring-SummaryReport-' . $monthSpelled . '.xlsx');
    }
}
