<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionInspectionsRequest;
use App\Http\Requests\UpdateServiceConnectionInspectionsRequest;
use App\Repositories\ServiceConnectionInspectionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnections;
use App\Http\Controllers\ServiceConnectionsController;
use App\Models\User;
use App\Models\ServiceConnectionTimeframes;
use App\Models\ServiceConnectionPayParticulars;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\ServiceConnectionPayTransaction;
use App\Models\ServiceConnectionInspections;
use Illuminate\Support\Facades\DB;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class ServiceConnectionInspectionsController extends AppBaseController
{
    /** @var  ServiceConnectionInspectionsRepository */
    private $serviceConnectionInspectionsRepository;

    public function __construct(ServiceConnectionInspectionsRepository $serviceConnectionInspectionsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionInspectionsRepository = $serviceConnectionInspectionsRepo;
    }

    /**
     * Display a listing of the ServiceConnectionInspections.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->all();

        return view('service_connection_inspections.index')
            ->with('serviceConnectionInspections', $serviceConnectionInspections);
    }

    /**
     * Show the form for creating a new ServiceConnectionInspections.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_inspections.create');
    }

    /**
     * Store a newly created ServiceConnectionInspections in storage.
     *
     * @param CreateServiceConnectionInspectionsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionInspectionsRequest $request)
    {
        $input = $request->all();

        if ($input['id'] != null) {
            $sc = ServiceConnectionInspections::find($input['id']);

            if ($sc != null) {
                $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($sc->id);

                if (empty($serviceConnectionInspections)) {
                    Flash::error('Service Connection Inspections not found');

                    return redirect(route('serviceConnectionInspections.index'));
                }

                $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->update($request->all(), $sc->id);

                $serviceConnection = ServiceConnections::find($input['ServiceConnectionId']);

                if (floatval($serviceConnection->LoadCategory) > 15) {
                    $serviceConnection->Status = 'Forwarded To Planning';

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'Forwarded To Planning';
                    $timeFrame->Notes = 'For assigning of BoM and Staking.';
                    $timeFrame->save();       
                } else {
                    $serviceConnection->Status = 'For Inspection';

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'For Inspection';
                    $timeFrame->Notes = 'To be Inspected';
                    $timeFrame->save();
                }
                $serviceConnection->save();

                // CREATE PAYMENT TRANSACTIONS
                $paymentParticulars = ServiceConnectionPayParticulars::all();
                $subTotal = 0.0;
                $vatTotal = 0.0;
                $overAllTotal = 0.0;
                
                return redirect(route('serviceConnectionPayTransactions.create-step-four', [$input['ServiceConnectionId']]));
            } else {
                $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->create($input);

                Flash::success('Service Connection Inspections saved successfully.');

                $serviceConnection = ServiceConnections::find($input['ServiceConnectionId']);

                if (floatval($serviceConnection->LoadCategory) > 15) {
                    $serviceConnection->Status = 'Forwarded To Planning';

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'Forwarded To Planning';
                    $timeFrame->Notes = 'For assigning of BoM and Staking.';
                    $timeFrame->save();       
                } else {
                    $serviceConnection->Status = 'For Inspection';

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'For Inspection';
                    $timeFrame->Notes = 'To be Inspected';
                    $timeFrame->save();
                }
                $serviceConnection->save();

                // CREATE PAYMENT TRANSACTIONS
                $paymentParticulars = ServiceConnectionPayParticulars::all();
                $subTotal = 0.0;
                $vatTotal = 0.0;
                $overAllTotal = 0.0;
                
                return redirect(route('serviceConnectionPayTransactions.create-step-four', [$input['ServiceConnectionId']]));
            }
        } else {
            return abort('ID Not found!', 404);
        }
    }

    /**
     * Display the specified ServiceConnectionInspections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        return view('service_connection_inspections.show')->with('serviceConnectionInspections', $serviceConnectionInspections);
    }

    /**
     * Show the form for editing the specified ServiceConnectionInspections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        $inspectors = User::permission('sc verifier')->pluck('name', 'id'); // CHANGE PERMISSION TO WHATEVER VERIFIER NAME IS

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        return view('service_connection_inspections.edit', ['serviceConnectionInspections' => $serviceConnectionInspections, 'inspectors' => $inspectors]);
    }

    /**
     * Update the specified ServiceConnectionInspections in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionInspectionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionInspectionsRequest $request)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->update($request->all(), $id);

        Flash::success('Service Connection Inspections updated successfully.');

        // return redirect(route('serviceConnectionInspections.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$request['ServiceConnectionId']]);
    }

    /**
     * Remove the specified ServiceConnectionInspections from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        $this->serviceConnectionInspectionsRepository->delete($id);

        Flash::success('Service Connection Inspections deleted successfully.');

        return redirect(route('serviceConnectionInspections.index'));
    }

    public function createStepTwo($scId) {
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
                        'CRM_ServiceConnections.ElectricianAcredited',)
        ->where('CRM_ServiceConnections.id', $scId)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $inspectors = User::role('Inspector')->pluck('name', 'id'); // CHANGE PERMISSION TO WHATEVER VERIFIER NAME IS

        $serviceConnectionInspections = null;

        $pendingInspections = DB::connection('sqlsrv')
            ->table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("CRM_ServiceConnections.Status IN ('For Inspection', 'Re-Inspection') AND CRM_ServiceConnectionInspections.Inspector IS NOT NULL")
            ->select('users.name',
                DB::raw("COUNT(CRM_ServiceConnections.id) AS InspectionCount")    
            )
            ->groupBy('users.name')
            ->get();

        return view('/service_connection_inspections/create_step_two', [
            'serviceConnection' => $serviceConnection, 
            'inspectors' => $inspectors, 
            'serviceConnectionInspections' => $serviceConnectionInspections,
            'pendingInspections' => $pendingInspections,
        ]);
    }
}
