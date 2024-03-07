<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionMtrTrnsfrmrRequest;
use App\Http\Requests\UpdateServiceConnectionMtrTrnsfrmrRequest;
use App\Repositories\ServiceConnectionMtrTrnsfrmrRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnections;
use App\Http\Controllers\ServiceConnectionsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use App\Models\Notifiers;
use App\Models\SMSNotifications;
use Flash;
use Response;

class ServiceConnectionMtrTrnsfrmrController extends AppBaseController
{
    /** @var  ServiceConnectionMtrTrnsfrmrRepository */
    private $serviceConnectionMtrTrnsfrmrRepository;

    public function __construct(ServiceConnectionMtrTrnsfrmrRepository $serviceConnectionMtrTrnsfrmrRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionMtrTrnsfrmrRepository = $serviceConnectionMtrTrnsfrmrRepo;
    }

    /**
     * Display a listing of the ServiceConnectionMtrTrnsfrmr.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionMtrTrnsfrmrs = $this->serviceConnectionMtrTrnsfrmrRepository->all();

        return view('service_connection_mtr_trnsfrmrs.index')
            ->with('serviceConnectionMtrTrnsfrmrs', $serviceConnectionMtrTrnsfrmrs);
    }

    /**
     * Show the form for creating a new ServiceConnectionMtrTrnsfrmr.
     *
     * @return Response
     */
    public function create()
    {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'create metering data', 'Super Admin'])) {
            return view('service_connection_mtr_trnsfrmrs.create');
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }  
    }

    /**
     * Store a newly created ServiceConnectionMtrTrnsfrmr in storage.
     *
     * @param CreateServiceConnectionMtrTrnsfrmrRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionMtrTrnsfrmrRequest $request)
    {
        $input = $request->all();

        $serviceConnectionMtrTrnsfrmr = $this->serviceConnectionMtrTrnsfrmrRepository->create($input);

        $serviceConnection = ServiceConnections::find($input['ServiceConnectionId']);

        Flash::success('Service Connection Mtr Trnsfrmr saved successfully.');

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Meter and Transformer Assigned';
        $timeFrame->save();

        // SEND SMS
        if ($serviceConnection->ContactNumber != null) {
            if (strlen($serviceConnection->ContactNumber) > 10) {
                $msg = "Hello " . $serviceConnection->ServiceAccountName . ", \nThis is to inform you that your BOHECO I Service Connection Application with application no. " . $serviceConnection->id .
                    " has been assigned with a new electric meter with the following details: \n\n" .
                    "Brand: " . $serviceConnectionMtrTrnsfrmr->MeterBrand . "\n" . 
                    "Serial Number: " . $serviceConnectionMtrTrnsfrmr->MeterSerialNumber . "\n\n" .
                    "\nHave a great day!";
                SMSNotifications::createFreshSms($serviceConnection->ContactNumber, $msg, 'SERVICE CONNECTIONS - METER', $serviceConnection->id);
            }
        }  

        // return redirect()->action([ServiceConnectionsController::class, 'show'], [$input['ServiceConnectionId']]);        
        // return redirect(route('serviceConnectionPayTransactions.create-step-four', [$input['ServiceConnectionId']]));
        return redirect(route('serviceConnectionMtrTrnsfrmrs.assigning'));
    }

    /**
     * Display the specified ServiceConnectionMtrTrnsfrmr.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionMtrTrnsfrmr = $this->serviceConnectionMtrTrnsfrmrRepository->find($id);

        if (empty($serviceConnectionMtrTrnsfrmr)) {
            Flash::error('Service Connection Mtr Trnsfrmr not found');

            return redirect(route('serviceConnectionMtrTrnsfrmrs.index'));
        }

        return view('service_connection_mtr_trnsfrmrs.show')->with('serviceConnectionMtrTrnsfrmr', $serviceConnectionMtrTrnsfrmr);
    }

    /**
     * Show the form for editing the specified ServiceConnectionMtrTrnsfrmr.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionMtrTrnsfrmr = $this->serviceConnectionMtrTrnsfrmrRepository->find($id);

        if (empty($serviceConnectionMtrTrnsfrmr)) {
            Flash::error('Service Connection Mtr Trnsfrmr not found');

            return redirect(route('serviceConnectionMtrTrnsfrmrs.index'));
        }

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'update metering data', 'Super Admin'])) {
            return view('service_connection_mtr_trnsfrmrs.edit')->with('serviceConnectionMtrTrnsfrmr', $serviceConnectionMtrTrnsfrmr);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        } 
        
    }

    /**
     * Update the specified ServiceConnectionMtrTrnsfrmr in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionMtrTrnsfrmrRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionMtrTrnsfrmrRequest $request)
    {
        $serviceConnectionMtrTrnsfrmr = $this->serviceConnectionMtrTrnsfrmrRepository->find($id);

        if (empty($serviceConnectionMtrTrnsfrmr)) {
            Flash::error('Service Connection Mtr Trnsfrmr not found');

            return redirect(route('serviceConnectionMtrTrnsfrmrs.index'));
        }

        $serviceConnectionMtrTrnsfrmr = $this->serviceConnectionMtrTrnsfrmrRepository->update($request->all(), $id);

        Flash::success('Service Connection Mtr Trnsfrmr updated successfully.');

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Meter and Transformer Updated';
        $timeFrame->save();

        // return redirect(route('serviceConnectionMtrTrnsfrmrs.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$request['ServiceConnectionId']]);
    }

    /**
     * Remove the specified ServiceConnectionMtrTrnsfrmr from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionMtrTrnsfrmr = $this->serviceConnectionMtrTrnsfrmrRepository->find($id);

        if (empty($serviceConnectionMtrTrnsfrmr)) {
            Flash::error('Service Connection Mtr Trnsfrmr not found');

            return redirect(route('serviceConnectionMtrTrnsfrmrs.index'));
        }

        $this->serviceConnectionMtrTrnsfrmrRepository->delete($id);

        Flash::success('Service Connection Mtr Trnsfrmr deleted successfully.');

        return redirect(route('serviceConnectionMtrTrnsfrmrs.index'));
    }

    public function createStepThree($scId) {
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
        ->where('CRM_ServiceConnections.id', $scId)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $serviceConnectionMtrTrnsfrmr = null;

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'create metering data', 'Super Admin'])) {
            return view('/service_connection_mtr_trnsfrmrs/create_step_three', ['serviceConnection' => $serviceConnection, 'serviceConnectionMtrTrnsfrmr' => $serviceConnectionMtrTrnsfrmr]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }         
    }

    public function assigning(Request $request) {
        $options = $request['Options'];
        if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Metering Personnel'])) {
            if ($options == 'All') {
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
                                        'CRM_ServiceConnections.Sitio as Sitio', 
                                        'CRM_ServiceConnections.ORNumber', 
                                        'CRM_ServiceConnections.ORDate', 
                                        'CRM_Towns.Town as Town',
                                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                        'CRM_Barangays.Barangay as Barangay')
                        ->whereRaw("CRM_ServiceConnections.id NOT IN (SELECT ServiceConnectionId FROM CRM_ServiceConnectionMeterAndTransformer) AND CRM_ServiceConnections.created_at > '2023-02-28' AND ConnectionApplicationType NOT IN ('Relocation')")
                        ->where(function ($query) {
                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                ->orWhereNull('CRM_ServiceConnections.Trash');
                        })
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            } else {
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
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_ServiceConnections.ORNumber', 
                                'CRM_ServiceConnections.ORDate', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                'CRM_Barangays.Barangay as Barangay')
                ->whereRaw("CRM_ServiceConnections.ORNumber IS NOT NULL AND CRM_ServiceConnections.id NOT IN (SELECT ServiceConnectionId FROM CRM_ServiceConnectionMeterAndTransformer) AND CRM_ServiceConnections.created_at > '2023-02-28' AND ConnectionApplicationType NOT IN ('Relocation')")
                ->where(function ($query) {
                    $query->where('CRM_ServiceConnections.Trash', 'No')
                        ->orWhereNull('CRM_ServiceConnections.Trash');
                })
                ->orderByDesc('CRM_ServiceConnections.ORDate')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
            }

            return view('/service_connection_mtr_trnsfrmrs/assigning', ['serviceConnections' => $serviceConnections]);
        } else {
            abort(403, 'Access denied');
        }        
    }
}
