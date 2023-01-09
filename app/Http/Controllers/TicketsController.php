<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketsRequest;
use App\Http\Requests\UpdateTicketsRequest;
use App\Repositories\TicketsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Towns;
use App\Models\ServiceConnectionCrew;
use App\Models\Barangays;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Models\IDGenerator;
use App\Models\Readings;
use App\Models\ServiceAccounts;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionInspections;
use App\Models\BillingMeters;
use App\Models\DisconnectionHistory;
use App\Models\AccountMaster;
use App\Models\User;
use App\Exports\TicketSummaryReportDownloadExport;
use App\Exports\KPSTicketsExport;
use App\Exports\DynamicExport;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class TicketsController extends AppBaseController
{
    /** @var  TicketsRepository */
    private $ticketsRepository;

    public function __construct(TicketsRepository $ticketsRepo)
    {
        $this->middleware('auth');
        $this->ticketsRepository = $ticketsRepo;
    }

    /**
     * Display a listing of the Tickets.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tickets = $this->ticketsRepository->all();

        return view('tickets.index')
            ->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a new Tickets.
     *
     * @return Response
     */
    public function create()
    {
        if(Auth::user()->hasAnyPermission(['tickets create', 'Super Admin'])) {
            return view('tickets.create');
        } else {
            return abort(403, "You're not authorized to create a ticket.");
        }
        
    }

    /**
     * Store a newly created Tickets in storage.
     *
     * @param CreateTicketsRequest $request
     *
     * @return Response
     */
    public function store(CreateTicketsRequest $request)
    {
        $input = $request->all();

        $tickets = $this->ticketsRepository->create($input);

        // FILTER METER RELATED TICKETS
        // $ticket = DB::table('CRM_TicketsRepository')
        //     ->where('id', $tickets->Ticket)
        //     ->whereIn('ParentTicket', ['1668541254365', '1668541254387', '1668541254387', '1668541254422', '1668541254427']) // Mother Meter, KWH Meter, KWH Meter Transfer, Disconnection, Reconnection
        //     ->first(); 
            
        // if ($ticket != null) {
            
        // }
        // SAVE METER INFO
        $accountMeterInfo = DB::connection('sqlsrvbilling')
            ->table('AccountMaster')
            ->where('AccountNumber', $tickets->AccountNumber)
            ->select('*')
            ->first();
        
        if ($accountMeterInfo != null) {
            $tickets->CurrentMeterNo = $accountMeterInfo->MeterNumber;
            // EDIT LATER
            $tickets->GeoLocation = $accountMeterInfo->Item1;
            $tickets->save();
        }

        Flash::success('Tickets saved successfully.');

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID();
        $ticketLog->TicketId = $tickets->id;
        $ticketLog->Log = "Received";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        return redirect(route('tickets.print-ticket', [$tickets->id]));
    }

    /**
     * Display the specified Tickets.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tickets = DB::table('CRM_Tickets')
                ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->where('CRM_Tickets.id', $id)
                ->select('CRM_Tickets.id',
                    'CRM_Tickets.AccountNumber',
                    'CRM_Tickets.ConsumerName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_Tickets.Sitio',
                    'CRM_TicketsRepository.ParentTicket',
                    'CRM_TicketsRepository.Name as Ticket',
                    'CRM_TicketsRepository.Type as TicketType',
                    'CRM_Tickets.Reason',
                    'CRM_Tickets.ContactNumber',
                    'CRM_Tickets.ReportedBy',
                    'CRM_Tickets.PoleNumber',
                    'CRM_Tickets.ORNumber',
                    'CRM_Tickets.ORDate',
                    'CRM_Tickets.GeoLocation',
                    'CRM_Tickets.Neighbor1',
                    'CRM_Tickets.Neighbor2',
                    'CRM_Tickets.Notes',
                    'CRM_Tickets.Status',
                    'CRM_Tickets.DateTimeDownloaded',
                    'CRM_Tickets.DateTimeLinemanArrived',
                    'CRM_Tickets.DateTimeLinemanExecuted',
                    'CRM_Tickets.UserId',
                    'CRM_Tickets.Office',  
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_Tickets.created_at',
                    'CRM_Tickets.updated_at',
                    'CRM_Tickets.Trash',
                    'CRM_Tickets.ServiceConnectionId',
                    'CRM_Tickets.InspectionId',  
                    )
                ->first();

        $ticketLogs = DB::table('CRM_TicketLogs')
            ->leftJoin('users', 'CRM_TicketLogs.UserId', '=', 'users.id')
            ->where('TicketId', $id)
            ->select('CRM_TicketLogs.*', 'users.name')
            ->orderByDesc('created_at')
            ->get();

        if ($tickets->AccountNumber != null) {
            $history = DB::table('CRM_Tickets')
                ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->where('CRM_Tickets.AccountNumber', $tickets->AccountNumber)
                ->where('CRM_Tickets.id', '!=', $id)
                ->where(function ($query) {
                        $query->where('CRM_Tickets.Trash', 'No')
                            ->orWhereNull('CRM_Tickets.Trash');
                    })
                ->select('CRM_Tickets.id',
                    'CRM_Tickets.AccountNumber',
                    'CRM_Tickets.ConsumerName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_Tickets.Sitio',
                    'CRM_TicketsRepository.ParentTicket',
                    'CRM_TicketsRepository.Name as Ticket',
                    'CRM_TicketsRepository.Type as TicketType',
                    'CRM_Tickets.Reason',
                    'CRM_Tickets.ContactNumber',
                    'CRM_Tickets.ReportedBy',
                    'CRM_Tickets.ORNumber',
                    'CRM_Tickets.ORDate',
                    'CRM_Tickets.GeoLocation',
                    'CRM_Tickets.Neighbor1',
                    'CRM_Tickets.Neighbor2',
                    'CRM_Tickets.Notes',
                    'CRM_Tickets.Status',
                    'CRM_Tickets.DateTimeDownloaded',
                    'CRM_Tickets.DateTimeLinemanArrived',
                    'CRM_Tickets.DateTimeLinemanExecuted',
                    'CRM_Tickets.UserId',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_Tickets.created_at',
                    'CRM_Tickets.updated_at',
                    'CRM_Tickets.Trash')
                ->orderByDesc('CRM_Tickets.created_at')
                ->get();
        } else {
            $history = null;
        }

        if ($tickets->InspectionId != null) {
            $inspections = ServiceConnectionInspections::find($tickets->InspectionId);
        } else {
            $inspections = null;
        }

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        return view('tickets.show', [
            'tickets' => $tickets, 
            'ticketLogs' => $ticketLogs,
            'history' => $history,
            'serviceConnectionInspections' => $inspections,
        ]);
    }

    /**
     * Show the form for editing the specified Tickets.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tickets = $this->ticketsRepository->find($id);
        $cond = 'edit';

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        // TICKETS MATRIX
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->orderBy('Name')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        if(Auth::user()->hasAnyPermission(['tickets create', 'ticket update', 'Super Admin'])) {
            $serviceAccount = null;
            return view('tickets.edit', [
                'tickets' => $tickets, 
                'towns' => $towns,
                'parentTickets' => $parentTickets,
                'crew' => $crew,
                'cond' => $cond,
                'left' => $left = null,
                'right' => $right = null,
                'serviceAccount' => $serviceAccount,
            ]);
        } else {
            return abort(403, "You're not authorized to update a ticket.");
        }        
    }

    /**
     * Update the specified Tickets in storage.
     *
     * @param int $id
     * @param UpdateTicketsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicketsRequest $request)
    {
        $tickets = $this->ticketsRepository->find($id);

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        $tickets = $this->ticketsRepository->update($request->all(), $id);

        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID();
        $ticketLog->TicketId = $id;
        $ticketLog->Log = "Ticket Updated";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        Flash::success('Tickets updated successfully.');

        return redirect(route('tickets.index'));
    }

    /**
     * Remove the specified Tickets from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        if(Auth::user()->hasAnyPermission(['tickets create', 'Super Admin'])) {
            $tickets = $this->ticketsRepository->find($id);

            if (empty($tickets)) {
                Flash::error('Tickets not found');

                return redirect(route('tickets.index'));
            }

            $tickets->Trash = 'Yes';
            $tickets->UserId = Auth::id();
            $tickets->save();
            // $this->ticketsRepository->delete($id);

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateID();
            $ticketLog->TicketId = $id;
            $ticketLog->Log = "Ticket Moved to Trash";
            $ticketLog->UserId = Auth::id();
            $ticketLog->save();

            Flash::success('Tickets deleted successfully.');

            return redirect(route('tickets.index'));
        } else {
            return abort(403, "You're not authorized to delete a ticket.");
        }        
    }

    public function fetchTickets(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if ($query != '' ) {
                $data = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->leftJoin('Billing_ServiceAccounts', 'CRM_Tickets.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'CRM_Tickets.Office',  
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->where('CRM_Tickets.id', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_Tickets.ConsumerName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_Tickets.AccountNumber', 'LIKE', '%' . $query . '%')   
                    ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $query . '%')                  
                    ->orderBy('CRM_Tickets.ConsumerName')
                    ->get();
            } else {
                $data = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->leftJoin('Billing_ServiceAccounts', 'CRM_Tickets.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'CRM_Tickets.Office',  
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->orderByDesc('CRM_Tickets.created_at')
                    ->take(15)
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
                                                <h4>' .$row->ConsumerName . '</h4>
                                                <p class="text-muted" style="margin-bottom: 0;">Acount Number: ' . ($row->OldAccountNo != null ? $row->OldAccountNo : '') . '</p>
                                                <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                <a href="' . route('tickets.show', [$row->id]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="' . route('tickets.edit', [$row->id]) . '" class="text-warning" style="margin-top: 5px; padding: 8px;" title="Edit"><i class="fas fa-pen"></i></a>
                                            </div>     
                                        </div> 

                                        <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                            <div>
                                                <p class="text-muted" style="margin-bottom: 0;">Ticket: <strong>' . $row->Ticket . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Ticket Filed at: <strong>' . date('F d, Y', strtotime($row->created_at)) . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Office: <strong>' . $row->Office . '</strong></p>
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

    public function createSelect(Request $request) {
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

        return view('/tickets/create_select', [
            'serviceAccounts' => $serviceAccounts,
        ]);
    }

    public function getCreateAjax(Request $request) {
        if ($request->ajax()) {
            if ($request['params'] == null && $request['oldacctno'] == null) {
                $serviceAccounts = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay')
                            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                            ->take(25)
                            ->get();
            } elseif ($request['params'] == null && $request['oldacctno'] != null) {
                $serviceAccounts = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay')
                            // ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                            // ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                            ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['oldacctno'] . '%')
                            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                            ->get();
            } else if ($request['params'] != null && $request['oldacctno'] == null) {
                $serviceAccounts = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay')
                    ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                    ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                    // ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['oldacctno'] . '%')
                    ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                    ->get();
            } else {
                $serviceAccounts = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay')
                            ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                            ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                            ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                            ->get();
            }

            $output = "";

            foreach($serviceAccounts as $item) {
                $output .= '<tr>' .
                        '<td>' . $item->OldAccountNo . '</td>' .
                        '<td>' . $item->ServiceAccountName . '</td>' .
                        '<td>' . $item->Barangay . ', ' . $item->Town . '</td>' .
                        '<td>' . 
                            '<a href="' . route("tickets.create-new", [$item->id]) . '"><i class="fas fa-arrow-alt-circle-right"></i></a>' .
                        '</td>' .
                    '</tr>';
            }
            
            return response()->json($output, 200);
        }
    }

    public function createNew($id) { // id is account number
        if ($id != null) {
            $serviceAccount = DB::connection('sqlsrvbilling')
                ->table('AccountMaster')
                ->where('AccountNumber', $id)
                ->select('AccountMaster.*')
                ->first();

            if ($serviceAccount != null) {
                $left = AccountMaster::where('Route', $serviceAccount->Route)
                    ->whereRaw("SequenceNumber < " . $serviceAccount->SequenceNumber)
                    ->orderByDesc('SequenceNumber')
                    ->first();

                $right = AccountMaster::where('Route', $serviceAccount->Route)
                    ->whereRaw("SequenceNumber > " . $serviceAccount->SequenceNumber)
                    ->orderBy('SequenceNumber')
                    ->first();
            } else {
                $left = null;

                $right = null;
            }
        } else {
            $serviceAccount = null;
            $left = null;
            $right = null;
        }

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        // TICKETS MATRIX
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->whereNotIn('id', ['1668541254405', '1668541254392'])->orderBy('Name')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        $history = DB::table('CRM_Tickets')
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                        ->where('CRM_Tickets.AccountNumber', $id)
                        ->select('CRM_Tickets.ConsumerName', 
                            'CRM_Tickets.id',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay',
                            'CRM_TicketsRepository.Name',
                            'CRM_TicketsRepository.ParentTicket',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.Reason',
                            'CRM_Tickets.Status',)
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();

        $cond = 'new';

        $tickets = null;

        return view('tickets.create',   [
            'serviceAccount' => $serviceAccount,
            'towns' => $towns,
            'parentTickets' => $parentTickets,
            'crew' => $crew,
            'history' => $history,
            'cond' => $cond,
            'left' => $left,
            'right' => $right,
            'tickets' => $tickets,
        ]);
    }

    public function printTicket($id) {
        $tickets = DB::table('CRM_Tickets')
                ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->leftJoin('users', 'CRM_Tickets.UserId', '=', 'users.id')
                ->where('CRM_Tickets.id', $id)
                ->select('CRM_Tickets.id',
                    'CRM_Tickets.AccountNumber',
                    'CRM_Tickets.ConsumerName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_Tickets.Sitio',
                    'CRM_TicketsRepository.ParentTicket',
                    'CRM_TicketsRepository.Name as Ticket',
                    'CRM_TicketsRepository.Type as TicketType',
                    'CRM_Tickets.Reason',
                    'CRM_Tickets.ContactNumber',
                    'CRM_Tickets.ReportedBy',
                    'CRM_Tickets.ORNumber',
                    'CRM_Tickets.ORDate',
                    'CRM_Tickets.GeoLocation',
                    'CRM_Tickets.PoleNumber',
                    'CRM_Tickets.Neighbor1',
                    'CRM_Tickets.Neighbor2',
                    'CRM_Tickets.Notes',
                    'CRM_Tickets.Status',
                    'CRM_Tickets.DateTimeDownloaded',
                    'CRM_Tickets.CurrentMeterNo',
                    'CRM_Tickets.DateTimeLinemanArrived',
                    'CRM_Tickets.DateTimeLinemanExecuted',
                    'CRM_Tickets.UserId',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_Tickets.created_at',
                    'CRM_Tickets.updated_at',
                    'CRM_Tickets.Trash',
                    'users.name')
                ->first();

        if ($tickets->AccountNumber != null) {
            $account = ServiceAccounts::find($tickets->AccountNumber);
        } else {
            $account = null;
        }

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        return view('/tickets/print_ticket', [
            'tickets' => $tickets,
            'account' => $account
        ]);
    }

    public function trash() {
        $tickets = DB::table('CRM_Tickets')
            ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->where('CRM_Tickets.Trash', 'Yes')
            ->select('CRM_Tickets.id',
                'CRM_Tickets.AccountNumber',
                'CRM_Tickets.ConsumerName',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_Tickets.Sitio',
                'CRM_TicketsRepository.ParentTicket',
                'CRM_TicketsRepository.Name as Ticket',
                'CRM_TicketsRepository.Type as TicketType',
                'CRM_Tickets.Reason',
                'CRM_Tickets.ContactNumber',
                'CRM_Tickets.ReportedBy',
                'CRM_Tickets.ORNumber',
                'CRM_Tickets.ORDate',
                'CRM_Tickets.GeoLocation',
                'CRM_Tickets.Neighbor1',
                'CRM_Tickets.Neighbor2',
                'CRM_Tickets.Notes',
                'CRM_Tickets.Status',
                'CRM_Tickets.DateTimeDownloaded',
                'CRM_Tickets.DateTimeLinemanArrived',
                'CRM_Tickets.DateTimeLinemanExecuted',
                'CRM_Tickets.UserId',
                'CRM_ServiceConnectionCrew.StationName',
                'CRM_Tickets.created_at',
                'CRM_Tickets.updated_at',
                'CRM_Tickets.Trash')
            ->get();
        return view('/tickets/trash', ['tickets' => $tickets]);
    }

    public function restoreTicket($id) {
        $tickets = Tickets::find($id);
        $tickets->Trash = null;
        $tickets->UserId = Auth::id();
        $tickets->save();

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID();
        $ticketLog->TicketId = $id;
        $ticketLog->Log = "Ticket Restored";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        return redirect(route('tickets.show', [$id]));
    }

    public function updateDateFiled(Request $request) {
        if ($request->ajax()) {
            $ticket = Tickets::find($request['id']);
            $ticket->created_at = date('Y-m-d H:i:s', strtotime($request['created_at']));
            $ticket->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateID();
            $ticketLog->TicketId = $request['id'];
            $ticketLog->Log = "Date Filed Updated";
            $ticketLog->LogDetails = "Date filed changed from " . $ticket->created_at . " to " . $request['created_at'];
            $ticketLog->UserId = Auth::id();
            $ticketLog->save();

            return response()->json(['response' => 'ok'], 200);
        }
    }

    public function updateDateDownloaded(Request $request) {
        if ($request->ajax()) {
            $ticket = Tickets::find($request['id']);
            $ticket->DateTimeDownloaded = date('Y-m-d H:i:s', strtotime($request['DateTimeDownloaded']));
            $ticket->Status = "Forwarded to Crew";
            $ticket->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateID();
            $ticketLog->TicketId = $request['id'];
            $ticketLog->Log = "Ticket sent to lineman";
            $ticketLog->LogDetails = "Ticket sent to lineman at " . $request['DateTimeDownloaded'];
            $ticketLog->UserId = Auth::id();
            $ticketLog->save();

            return response()->json(['response' => 'ok'], 200);
        }
    }

    public function updateDateArrival(Request $request) {
        if ($request->ajax()) {
            $ticket = Tickets::find($request['id']);
            $ticket->Status = "Crew Arrived on Site";
            $ticket->DateTimeLinemanArrived = date('Y-m-d H:i:s', strtotime($request['DateTimeLinemanArrived']));
            $ticket->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateID();
            $ticketLog->TicketId = $request['id'];
            $ticketLog->Log = "Lineman site arrival";
            $ticketLog->LogDetails = "Lineman arrived on site at " . $request['DateTimeLinemanArrived'];
            $ticketLog->UserId = Auth::id();
            $ticketLog->save();

            return response()->json(['response' => 'ok'], 200);
        }
    }

    public function updateExecution(Request $request) {
        if ($request->ajax()) {
            $ticket = Tickets::find($request['id']);
            $ticket->Status = $request['Status'];
            $ticket->Notes = $request['Notes'];
            $ticket->DateTimeLinemanExecuted = date('Y-m-d H:i:s', strtotime($request['DateTimeLinemanExecuted']));
            $ticket->save();

            // UPDATE ACCOUNTS
            if ($ticket->Ticket == Tickets::getReconnection() && $ticket->Status == 'Executed') {
                $account = ServiceAccounts::find($ticket->AccountNumber);
                if ($account != null) {
                    $account->AccountStatus = 'ACTIVE';
                    $account->save();

                    // ADD TO DISCO/RECO HISTORY
                    $recoHist = new DisconnectionHistory;
                    $recoHist->id = IDGenerator::generateIDandRandString();
                    $recoHist->AccountNumber = $account->id;
                    $recoHist->ServicePeriod = $ticket->ServicePeriod;
                    $recoHist->Status = 'RECONNECTED';
                    $recoHist->UserId = Auth::id();
                    $recoHist->DateDisconnected = date('Y-m-d', strtotime($ticket->DateTimeLinemanExecuted));
                    $recoHist->TimeDisconnected = date('H:i:s', strtotime($ticket->DateTimeLinemanExecuted));
                    $recoHist->save();
                }
            }

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateID();
            $ticketLog->TicketId = $request['id'];
            $ticketLog->Log = $request['Status'];
            if($request['Status'] == 'Executed') {
                $ticketLog->LogDetails = "Lineman performed action at " . $request['DateTimeLinemanExecuted'];
            } else {
                $ticketLog->LogDetails = $request['Notes'];
            }            
            $ticketLog->UserId = Auth::id();
            $ticketLog->save();

            return response()->json(['response' => 'ok'], 200);
        }
    }

    public function dashboard() {
        return view('/tickets/dashboard');
    }

    public function fetchDashboardTicketsTrend() {
        $startDate = date('Y-m-d', strtotime('first day of this month'));
        $tickets = DB::table('CRM_Tickets')
            ->select(DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE created_at BETWEEN '" . $startDate . "' AND '" . date('Y-m-d', strtotime($startDate . ' +1 month')) . "') AS 'FileOne'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE created_at BETWEEN '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "' AND '" . $startDate . "') AS 'FileTwo'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE created_at BETWEEN '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "') AS 'FileThree'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE created_at BETWEEN '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "') AS 'FileFour'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE created_at BETWEEN '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "') AS 'FileFive'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE created_at BETWEEN '" . date('Y-m-d', strtotime($startDate . '-5 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "') AS 'FileSix'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE DateTimeLinemanExecuted BETWEEN '" . $startDate . "' AND '" . date('Y-m-d', strtotime($startDate . ' +1 month')) . "') AS 'ExecutionOne'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE DateTimeLinemanExecuted BETWEEN '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "' AND '" . $startDate . "') AS 'ExecutionTwo'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE DateTimeLinemanExecuted BETWEEN '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "') AS 'ExecutionThree'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE DateTimeLinemanExecuted BETWEEN '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "') AS 'ExecutionFour'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE DateTimeLinemanExecuted BETWEEN '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "') AS 'ExecutionFive'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE DateTimeLinemanExecuted BETWEEN '" . date('Y-m-d', strtotime($startDate . '-5 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "') AS 'ExecutionSix'"),)
            ->limit(1)
            ->get();

        return response()->json($tickets, 200);
    }

    public function getTicketStatistics() {
        $startDate = date('Y-m-d', strtotime('first day of this month'));
        $endDate = date('Y-m-d', strtotime('last day of this month'));

        // GET AVERAGE
        $execTime = DB::table('CRM_Tickets')
            ->select(DB::raw("DATEDIFF(hh, created_at, DateTimeLinemanExecuted) as 'Average'"))
            ->where('Status', 'Executed')
            ->whereBetween('DateTimeLinemanExecuted', [$startDate, $endDate])
            ->get();

        $total = 0;
        foreach ($execTime as $item) {
            $total += intval($item->Average);
        }

        if (count($execTime) > 0) {
            $average = $total/count($execTime);
        } else {
            $average = 0;
        }        

        // GET STATS
        $stats = DB::table('CRM_Tickets')
            ->select(DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status='Received' AND (Trash IS NULL OR Trash = 'No')) AS 'Received'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status IN ('Forwareded to Crew', 'Downloaded by Crew', 'Forwarded to Crew') AND (Trash IS NULL OR Trash = 'No')) AS 'SentToLineman'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status='Executed' AND (DateTimeLinemanExecuted BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND (Trash IS NULL OR Trash = 'No')) AS 'ExecutedThisMonth'"),
                DB::raw($average . " AS 'AverageExecutionTime'"))
            ->limit(1)
            ->get();

        return response()->json($stats, 200);
    }

    public function getTicketStatisticsDetails(Request $request) {
        if ($request['Query'] == 'Received') {
            $tickets = DB::table('CRM_Tickets')
                ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->where('Status', 'Received')
                ->select('CRM_Tickets.id',
                    'CRM_Tickets.AccountNumber',
                    'CRM_Tickets.ConsumerName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_Tickets.Sitio',
                    'CRM_TicketsRepository.ParentTicket',
                    'CRM_TicketsRepository.Name as Ticket',
                    'CRM_TicketsRepository.Type as TicketType',
                    'CRM_Tickets.created_at AS DatePerformed',)
                ->where(function ($query) {
                        $query->where('CRM_Tickets.Trash', 'No')
                            ->orWhereNull('CRM_Tickets.Trash');
                    })
                ->orderByDesc('CRM_Tickets.created_at')
                ->get();
        } elseif ($request['Query'] == 'Forwarded To Lineman') {
            $tickets = DB::table('CRM_Tickets')
                ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->whereIn('Status', ['Forwareded to Crew', 'Downloaded by Crew', 'Forwarded to Crew'])
                ->select('CRM_Tickets.id',
                    'CRM_Tickets.AccountNumber',
                    'CRM_Tickets.ConsumerName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_Tickets.Sitio',
                    'CRM_TicketsRepository.ParentTicket',
                    'CRM_TicketsRepository.Name as Ticket',
                    'CRM_TicketsRepository.Type as TicketType',
                    'CRM_Tickets.DateTimeDownloaded AS DatePerformed',)
                ->where(function ($query) {
                        $query->where('CRM_Tickets.Trash', 'No')
                            ->orWhereNull('CRM_Tickets.Trash');
                    })
                ->orderByDesc('CRM_Tickets.created_at')
                ->get();
        } else {
            $startDate = date('Y-m-d', strtotime('first day of this month'));
            $endDate = date('Y-m-d', strtotime('last day of this month'));

            $tickets = DB::table('CRM_Tickets')
                ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->where('Status', 'Executed')
                ->whereBetween('DateTimeLinemanExecuted', [$startDate, $endDate])
                ->select('CRM_Tickets.id',
                    'CRM_Tickets.AccountNumber',
                    'CRM_Tickets.ConsumerName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_Tickets.Sitio',
                    'CRM_TicketsRepository.ParentTicket',
                    'CRM_TicketsRepository.Name as Ticket',
                    'CRM_TicketsRepository.Type as TicketType',
                    'CRM_Tickets.DateTimeLinemanExecuted AS DatePerformed',)
                ->where(function ($query) {
                        $query->where('CRM_Tickets.Trash', 'No')
                            ->orWhereNull('CRM_Tickets.Trash');
                    })
                ->orderByDesc('CRM_Tickets.created_at')
                ->get();
        }
        
        $output = "";

        if (count($tickets) > 0) {
            foreach($tickets as $item) {
                $ticketParent = DB::table('CRM_TicketsRepository')
                    ->where('id', $item->ParentTicket)
                    ->first();

                $output .= '
                    <tr>
                        <td>' . $item->AccountNumber . '</td>
                        <td>' . $item->ConsumerName . '</td>
                        <td>' . Tickets::getAddress($item) . '</td>
                        <td>' . ($ticketParent != null ? ($ticketParent->Name . ' - ' . $item->Ticket) : $item->Ticket) . '</td>
                        <td>' . date('F d, Y @ h:i:s A', strtotime($item->DatePerformed)) . '</td>
                        <td>
                            <a href="' . route("tickets.show", [$item->id]) . '"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                ';
            }            
        }

        return response()->json($output, 200);
    }

    public function kpsMonitor() {
        return view('/tickets/kps_monitor');
    }

    public function getKpsTicketCrewGraph(Request $request) {
        $startDate = date('Y-m-d', strtotime($request['Month']));
        $endDate = date('Y-m-d', strtotime($request['Month'] . ' +1 month'));

        $stats = DB::table('CRM_ServiceConnectionCrew')
            ->select('StationName',
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE CrewAssigned=CRM_ServiceConnectionCrew.id AND (Trash IS NULL OR Trash='No') AND (created_at BETWEEN '" . $startDate . "' AND '" . $endDate . "')) AS 'Assigned'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE CrewAssigned=CRM_ServiceConnectionCrew.id AND (Trash IS NULL OR Trash='No') AND Status='Executed' AND (created_at BETWEEN '" . $startDate . "' AND '" . $endDate . "')) AS 'Executed'")
            )
            ->orderBy('StationName')
            ->get();

        return response()->json($stats, 200);
    }

    public function getTicketCrewAverageHours(Request $request) {
        $startDate = date('Y-m-d', strtotime($request['Month']));
        $endDate = date('Y-m-d', strtotime($request['Month'] . ' +1 month'));
        $crews = DB::table('CRM_ServiceConnectionCrew')->orderBy('StationName')->get();

        $crewArr = [];
        $receivedToSentAvg = 0;
        $sentToArrivalAvg = 0;
        $arrivalToExecutionAvg = 0;
        $overAllAvg = 0;
        $i = 0;
        foreach ($crews as $item) {
            // RECEIVED - SENT TO LINEMAN Timeline
            $receivedToSent = DB::table('CRM_Tickets')
                ->select(DB::raw("DATEDIFF(hh, created_at, DateTimeDownloaded) as 'Res'"))
                ->where('CrewAssigned', $item->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('DateTimeDownloaded')
                ->where(function ($query) {
                    $query->where('Trash', 'No')
                        ->orWhereNull('Trash');
                })
                ->get();

            foreach ($receivedToSent as $itemX) {
                $receivedToSentAvg += intval($itemX->Res);
            } 
            if (count($receivedToSent) > 0) {
                $receivedToSentAvg = $receivedToSentAvg/count($receivedToSent);
            } else {
                $receivedToSentAvg = 0;
            }   

            // SENT TO LINEMAN - ARRIVAL Timeline
            $sentToArrival = DB::table('CRM_Tickets')
                ->select(DB::raw("DATEDIFF(hh, DateTimeDownloaded, DateTimeLinemanArrived) as 'Res'"))
                ->where('CrewAssigned', $item->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('DateTimeDownloaded')
                ->whereNotNull('DateTimeLinemanArrived')
                ->where(function ($query) {
                    $query->where('Trash', 'No')
                        ->orWhereNull('Trash');
                })
                ->get();
            foreach ($sentToArrival as $itemY) {
                $sentToArrivalAvg += intval($itemY->Res);
            } 
            if (count($sentToArrival) > 0) {
                $sentToArrivalAvg = $sentToArrivalAvg/count($sentToArrival);
            } else {
                $sentToArrivalAvg = 0;
            }   

            // ARRIVAL - EXECUTION Timeline
            $arrivalToExecution = DB::table('CRM_Tickets')
                ->select(DB::raw("DATEDIFF(hh, DateTimeLinemanArrived, DateTimeLinemanExecuted) as 'Res'"))
                ->where('CrewAssigned', $item->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('DateTimeLinemanArrived')
                ->whereNotNull('DateTimeLinemanExecuted')
                ->where(function ($query) {
                    $query->where('Trash', 'No')
                        ->orWhereNull('Trash');
                })
                ->get();
            foreach ($arrivalToExecution as $itemZ) {
                $arrivalToExecutionAvg += intval($itemZ->Res);
            } 
            if (count($arrivalToExecution) > 0) {
                $arrivalToExecutionAvg = $arrivalToExecutionAvg/count($arrivalToExecution);
            } else {
                $arrivalToExecutionAvg = 0;
            } 

            // OVERALL Timeline
            $overAll = DB::table('CRM_Tickets')
                ->select(DB::raw("DATEDIFF(hh, created_at, DateTimeLinemanExecuted) as 'Res'"))
                ->where('CrewAssigned', $item->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('DateTimeLinemanExecuted')
                ->where(function ($query) {
                    $query->where('Trash', 'No')
                        ->orWhereNull('Trash');
                })
                ->get();
            foreach ($overAll as $itemA) {
                $overAllAvg += intval($itemA->Res);
            } 
            if (count($overAll) > 0) {
                $overAllAvg = $overAllAvg/count($overAll);
            } else {
                $overAllAvg = 0;
            } 

            // AVERAGE COMPLAIN PER DAY THIS MONTH
            $ticketsThisMonth = DB::table('CRM_Tickets')
                ->select(DB::raw("COUNT(id) AS 'Count'"))
                ->where('CrewAssigned', $item->id)
                ->where(function ($query) {
                    $query->where('Trash', 'No')
                        ->orWhereNull('Trash');
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->first();

            // AVERAGE COMPLAIN EXECUTED PER DAY THIS MONTH
            $ticketsExecutedThisMonth = DB::table('CRM_Tickets')
                ->select(DB::raw("COUNT(id) AS 'Count'"))
                ->where('CrewAssigned', $item->id)
                ->where('Status', 'Executed')
                ->where(function ($query) {
                    $query->where('Trash', 'No')
                        ->orWhereNull('Trash');
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->first();
            
            $crewArr[$i]["StationId"] = $item->id;
            $crewArr[$i]["StationCrew"] = $item->StationName;
            $crewArr[$i]["Received"] = number_format($receivedToSentAvg, 2);
            $crewArr[$i]["SentToArrival"] = number_format($sentToArrivalAvg, 2);
            $crewArr[$i]["ArrivalToExecution"] = number_format($arrivalToExecutionAvg, 2);
            $crewArr[$i]["OverAll"] = number_format($overAllAvg, 2);
            $crewArr[$i]["TicketsThisMonth"] = $ticketsThisMonth != null ? $ticketsThisMonth->Count : 0;
            $crewArr[$i]["AverageThisMonth"] = $ticketsThisMonth != null ? (intval($ticketsThisMonth->Count) > 0 ? intval($ticketsThisMonth->Count)/Tickets::getAverageDailyDivisor() : 0) : 0; // 22 days a month
            $crewArr[$i]["ExecutedThisMonth"] = $ticketsExecutedThisMonth != null ? $ticketsExecutedThisMonth->Count : 0;
            $crewArr[$i]["AverageExecutedThisMonth"] = $ticketsExecutedThisMonth != null ? (intval($ticketsExecutedThisMonth->Count) > 0 ? intval($ticketsExecutedThisMonth->Count)/Tickets::getAverageDailyDivisor() : 0) : 0; // 22 days a month
            
            $i++;
        }

        $output = "";
        for ($x=0; $x<count($crewArr); $x++) {
            $output .= '
                <tr>
                    <th>' . $crewArr[$x]["StationCrew"] . '</th>
                    <td class="text-center">' . $crewArr[$x]["Received"] . ' hrs</td>
                    <td class="text-center">' . $crewArr[$x]["SentToArrival"] . ' hrs</td>
                    <td class="text-center">' . $crewArr[$x]["ArrivalToExecution"] . ' hrs</td>
                    <td class="text-center">' . $crewArr[$x]["OverAll"] . ' hrs</td>
                    <td class="text-center">' . $crewArr[$x]["TicketsThisMonth"] . '</td>
                    <td class="text-center">' . number_format(intval($crewArr[$x]["AverageThisMonth"]), 0) . '</td>
                    <td class="text-center">' . $crewArr[$x]["ExecutedThisMonth"] . '</td>
                    <td class="text-center">' . number_format(intval($crewArr[$x]["AverageExecutedThisMonth"]), 0) . '</td>
                </tr>
            ';
        }

        return response()->json($output, 200);
    }

    public function getOverAllAverageKps(Request $request) {
        $startDate = date('Y-m-d', strtotime($request['Month']));
        $endDate = date('Y-m-d', strtotime($request['Month'] . ' +1 month'));

        $stats = DB::table('CRM_Tickets')
            ->select(
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE (Trash IS NULL OR Trash = 'No') AND (created_at BETWEEN '" . $startDate . "' AND '" . $endDate . "')) AS 'TotalFiled'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE (Trash IS NULL OR Trash = 'No') AND (DateTimeLinemanExecuted BETWEEN '" . $startDate . "' AND '" . $endDate . "')) AS 'TotalExecuted'")
            )->limit(1)
            ->first();

        $data = [];
        if ($stats != null) {
            array_push($data, [
                "TotalFiled" => $stats->TotalFiled, 
                "TotalExecuted" => $stats->TotalExecuted,
                "AverageFiled" => intval($stats->TotalFiled) > 0 ? number_format(intval($stats->TotalFiled)/Tickets::getAverageDailyDivisor(), 2) : 0,
                "AverageExecuted" => intval($stats->TotalExecuted) > 0 ? number_format(intval($stats->TotalExecuted)/Tickets::getAverageDailyDivisor(), 2) : 0,
            ]);
        }

        return response()->json($data, 200);
    }

    public function changeMeter(Request $request) {
        if ($request['params'] == null && $request['oldaccount'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.id', 
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.OldAccountNo', 
                            'Billing_ServiceAccounts.AccountCount',  
                            'Billing_ServiceAccounts.AccountStatus',
                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),   
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(18);
        } elseif ($request['params'] == null && $request['oldaccount'] != null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.id', 
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.OldAccountNo',   
                            'Billing_ServiceAccounts.AccountCount',  
                            'Billing_ServiceAccounts.AccountStatus',
                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay')
                        // ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        // ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        // ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->where('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['oldaccount'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(18);            
        } elseif ($request['params'] != null && $request['oldaccount'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.id', 
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.OldAccountNo',   
                            'Billing_ServiceAccounts.AccountCount',  
                            'Billing_ServiceAccounts.AccountStatus',
                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        // ->where('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['oldaccount'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(18);            
        } else {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.id', 
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.OldAccountNo',   
                            'Billing_ServiceAccounts.AccountCount',  
                            'Billing_ServiceAccounts.AccountStatus',
                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['oldaccount'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(18);
        }   

        return view('/tickets/change_meter', ['serviceAccounts' => $serviceAccounts]);
    }

    public function createChangeMeter($accountNumber) {
        if ($accountNumber != null) {
            $serviceAccount = DB::table('Billing_ServiceAccounts')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->select('Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.id', 
                    'CRM_Towns.Town', 
                    'CRM_Barangays.Barangay', 
                    'Billing_ServiceAccounts.Town as TownId',
                    'Billing_ServiceAccounts.Barangay as BarangayId',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.OldAccountNo')
                ->where('Billing_ServiceAccounts.id', $accountNumber)
                ->first();
        } else {
            $serviceAccount = null;
        }

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $cond = 'new';

        $history = DB::table('CRM_Tickets')
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                        ->where('CRM_Tickets.AccountNumber', $accountNumber)
                        ->select('CRM_Tickets.ConsumerName', 
                            'CRM_Tickets.id',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay',
                            'CRM_TicketsRepository.Name',
                            'CRM_TicketsRepository.ParentTicket',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.Reason',
                            'CRM_Tickets.Status',)
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();

        return view('/tickets/create_change_meter', [
            'serviceAccount' => $serviceAccount,
            'towns' => $towns,
            'history' => $history,
            'cond' => $cond,
        ]);
    }

    public function changeMeterAssessments() {
        $tickets = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereNull('CrewAssigned')  
                    ->where('Ticket', Tickets::getChangeMeter())              
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();

        return view('/tickets/assessments_change_meter', [
            'tickets' => $tickets,
        ]);
    }

    public function assessChangeMeterForm($ticketId) {
        $ticket = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.CurrentMeterBrand', 
                                    'CRM_Tickets.CurrentMeterNo', 
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_Tickets.id', $ticketId)              
                    ->orderBy('CRM_Tickets.created_at')
                    ->first();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        $latestReading = Readings::where('AccountNumber', $ticket->AccountNumber)
            ->limit(5)
            ->orderByDesc('ServicePeriod')
            ->get();

        return view('/tickets/assess_change_meter_form', [
            'ticket' => $ticket,
            'crew' => $crew,
            'latestReading' => $latestReading,
        ]);
    }

    public function updateChangeMeterAssessment(Request $request) {
        $ticket = Tickets::find($request['id']);

        if ($ticket != null) {
            $ticket->CrewAssigned = $request['CrewAssigned'];
            $ticket->save();

            Flash::success('Change meter request of ' . $ticket->ConsumerName . ' forwarded to crew.');
        }

        return redirect(route('tickets.assessments-change-meter'));
    }

    public function ordinaryTicketsAssessment() {
        $tickets = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID',   
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereNull('CrewAssigned')  
                    ->where('Office', env('APP_LOCATION'))
                    ->whereNotIn('Ticket', [Tickets::getChangeMeter()])              
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->get();

        return view('/tickets/assessments_ordinary_ticket', [
            'crew' => $crew,
            'tickets' => $tickets,
        ]);
    }

    public function updateOrdinaryTicketAssessment(Request $request) {
        $ticket = Tickets::find($request['id']);

        if ($ticket != null) {
            $ticket->CrewAssigned = $request['CrewAssigned'];
            $ticket->save();
        }

        return response()->json($ticket, 200);
    }

    public function ticketSummaryReport() {
        // TICKETS MATRIX
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->orderBy('Name')->get();

        $towns = Towns::orderBy('Town')->get();

        return view('/tickets/reports_ticket_summary', [
            'parentTickets' => $parentTickets,
            'towns' => $towns,
        ]);
    }

    public function getTicketSummaryResults(Request $request) {
        $ticketParam = $request['TicketParam'];
        $from = $request['From'];
        $to = $request['To'];
        $area = $request['Area'];

        $results = null;
        if ($ticketParam == 'All') {
            if ($area == 'All') {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to])            
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            } else {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to])   
                    ->where('CRM_Tickets.Town', $area)         
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            }
        } else {
            if ($area == 'All') {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to]) 
                    ->where('CRM_Tickets.Ticket', $ticketParam)           
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            } else {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to])
                    ->where('CRM_Tickets.Ticket', $ticketParam)       
                    ->where('CRM_Tickets.Town', $area)         
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            }
        }

        $output = "";
        if ($results != null) {
            foreach($results as $item) {
                $ticketMain = \App\Models\TicketsRepository::find($item->TicketID);
                $parent = \App\Models\TicketsRepository::where('id', $ticketMain->ParentTicket)->first();

                $output .= '
                    <tr>
                        <td><a href="' . route("tickets.show", [$item->id]) . '">' . $item->id . '</a></td>
                        <td>' . $item->AccountNumber . '</td>
                        <td>' . $item->ConsumerName . '</td>
                        <td>' . Tickets::getAddress($item) . '</td>
                        <td>' . ($parent != null ? $parent->Name . ' - ' : '') . $item->Ticket . '</td>
                        <td>' . $item->Status . '</td>
                        <td>' . date("F d, Y h:i A", strtotime($item->created_at)) . '</td>
                        <td>' . ($item->DateTimeLinemanExecuted!=null ? date("F d, Y h:i A", strtotime($item->DateTimeLinemanExecuted)) : "") . '</td>
                    </tr>
                ';
            }
        }
        
        return response()->json($output, 200);
    }

    public function ticketSummaryReportDownloadRoute(Request $request) {
        $ticketParam = $request['TicketParam'];
        $from = $request['From'];
        $to = $request['To'];
        $area = $request['Area'];

        return response()->json(['ticket' => $ticketParam, 'from' => $from, 'to' => $to, 'area' => $area], 200);
    } 

    public function downloadTicketsSummaryReport($ticketParam, $from, $to, $area) {
        $results = null;
        if ($ticketParam == 'All') {
            if ($area == 'All') {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to])            
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            } else {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to])   
                    ->where('CRM_Tickets.Town', $area)         
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            }
        } else {
            if ($area == 'All') {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to]) 
                    ->where('CRM_Tickets.Ticket', $ticketParam)           
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            } else {
                $results = DB::table('CRM_Tickets')
                    ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')                
                    ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                    ->select('CRM_Tickets.id as id',
                                    'CRM_Tickets.AccountNumber',
                                    'CRM_Tickets.ConsumerName',
                                    'CRM_TicketsRepository.Name as Ticket', 
                                    'CRM_Tickets.Status',  
                                    'CRM_Tickets.Sitio as Sitio', 
                                    'CRM_Tickets.created_at', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_Tickets.Office',  
                                    'CRM_Tickets.Reason',  
                                    'CRM_Tickets.ContactNumber',  
                                    'CRM_Tickets.Ticket as TicketID', 
                                    'CRM_Tickets.DateTimeLinemanExecuted',    
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where(function ($query) {
                                        $query->where('CRM_Tickets.Trash', 'No')
                                            ->orWhereNull('CRM_Tickets.Trash');
                                    })
                    ->whereBetween('CRM_Tickets.created_at', [$from, $to])
                    ->where('CRM_Tickets.Ticket', $ticketParam)       
                    ->where('CRM_Tickets.Town', $area)         
                    ->orderBy('CRM_Tickets.created_at')
                    ->get();
            }
        }

        $arr = [];
        foreach($results as $item) {
            $ticketMain = \App\Models\TicketsRepository::find($item->TicketID);
            $parent = \App\Models\TicketsRepository::where('id', $ticketMain->ParentTicket)->first();
            array_push($arr, [
                'TicketNo' => $item->id . '',
                'AccountNo' => $item->AccountNumber,
                'ConsumerName' => $item->ConsumerName,
                'Address' => Tickets::getAddress($item),
                'Complain' => ($parent != null ? $parent->Name . ' - ' : '') . $item->Ticket,
                'Status' => $item->Status,
                'Reason' => $item->Reason,
                'DateRecorded' => date('F d, Y, h:i A', strtotime($item->created_at)),
                'DateExecuted' => ($item->DateTimeLinemanExecuted != null ? date('F d, Y, h:i A', strtotime($item->DateTimeLinemanExecuted)) : ''),
            ]);
        }

        $export = new TicketSummaryReportDownloadExport($arr);

        return Excel::download($export, 'Ticket-Summary-Report.xlsx');
        // print_r($arr);
    }

    public function disconnectionAssessments() {
        $towns = Towns::orderBy('Town')->get();

        return view('/tickets/assessments_disconnection', [
            'towns' => $towns,
        ]);
    }

    public function getDisconnectionResults(Request $request) {
        $period = $request['Period'];
        $route = $request['Route'];

        $disconnectionList = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereNotIn('Billing_Bills.id', DB::table('Cashier_PaidBills')->where('Cashier_PaidBills.ServicePeriod', $period)->pluck('Cashier_PaidBills.ObjectSourceId'))
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM CRM_Tickets WHERE ServicePeriod='" . $period . "' AND Ticket='" . Tickets::getDisconnectionDelinquencyId() . "')")
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $route)
            ->whereRaw('DATEDIFF(dd, Billing_Bills.BillingDate, GETDATE()) > ?', [DisconnectionHistory::noOfDaysTillDisconnection()])
            ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
            ->select('Billing_Bills.id as BillId',
                'Billing_ServiceAccounts.id AS AccountNumber',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.Town',
                'Billing_ServiceAccounts.Barangay',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Latitude',
                'Billing_ServiceAccounts.Longitude',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_Bills.KwhUsed',
                'Billing_Bills.EffectiveRate',
                'Billing_Bills.NetAmount',
                'Billing_Bills.AdditionalCharges',
                'Billing_Bills.Deductions',
                'Billing_Bills.BillingDate',
                'Billing_Bills.ServiceDateFrom',
                'Billing_Bills.ServiceDateTo',
                'Billing_Bills.DueDate',
                'Billing_Bills.ConsumerType',
                'Billing_Bills.MeterNumber',
                'Billing_Bills.ServicePeriod',
                'Billing_Bills.BillNumber')
            ->get();

        $results = "";
        foreach ($disconnectionList as $item) {
            $results .= '
                <tr>
                    <td>' . $item->AccountNumber . '</td>
                    <td>' . $item->ServiceAccountName . '</td>
                    <td>' . ServiceAccounts::getAddress($item) . '</td>
                    <td>' . date('F d, Y', strtotime($item->DueDate)) . '</td>
                    <td>' . number_format($item->KwhUsed, 2) . '</td>
                </tr>
            ';
        }

        return response()->json($results, 200);
    }

    public function disconnectionResultsRoute(Request $request) {
        $period = $request['Period'];
        $route = $request['Route'];

        return response()->json(['Period' => $period, 'Route' => $route], 200);
    }

    public function createAndPrintDisconnectionTickets($period, $route) {
        $disconnectionList = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereNotIn('Billing_Bills.id', DB::table('Cashier_PaidBills')->where('Cashier_PaidBills.ServicePeriod', $period)->pluck('Cashier_PaidBills.ObjectSourceId'))
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM CRM_Tickets WHERE ServicePeriod='" . $period . "' AND Ticket='" . Tickets::getDisconnectionDelinquencyId() . "')")
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $route)
            ->whereRaw('DATEDIFF(dd, Billing_Bills.BillingDate, GETDATE()) > ?', [DisconnectionHistory::noOfDaysTillDisconnection()])
            ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
            ->select('Billing_Bills.id as BillId',
                'Billing_ServiceAccounts.id AS AccountNumber',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.ContactNumber',
                'Billing_ServiceAccounts.Town',
                'Billing_ServiceAccounts.Barangay',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Latitude',
                'Billing_ServiceAccounts.Longitude',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_Bills.KwhUsed',
                'Billing_Bills.EffectiveRate',
                'Billing_Bills.NetAmount',
                'Billing_Bills.AdditionalCharges',
                'Billing_Bills.Deductions',
                'Billing_Bills.BillingDate',
                'Billing_Bills.ServiceDateFrom',
                'Billing_Bills.ServiceDateTo',
                'Billing_Bills.DueDate',
                'Billing_Bills.ConsumerType',
                'Billing_Bills.MeterNumber',
                'Billing_Bills.ServicePeriod',
                'Billing_Bills.BillNumber')
            ->get();

        // create tickets
        $i = 1;
        foreach($disconnectionList as $item) {
            // FILTER IF TICKET HAS ALREADY CREATED
            $ticket = Tickets::where('ServicePeriod', $period)
                ->where('AccountNumber', $item->AccountNumber)
                ->where('Ticket', Tickets::getDisconnectionDelinquencyId())
                ->first();

            if ($ticket != null) {

            } else {
                $ticket = new Tickets;
                $ticket->id = $i . IDGenerator::generateID();
                $ticket->AccountNumber = $item->AccountNumber;
                $ticket->ConsumerName = $item->ServiceAccountName;
                $ticket->Town = $item->Town;
                $ticket->Barangay = $item->Barangay;
                $ticket->Sitio = $item->Purok;
                $ticket->Ticket = Tickets::getDisconnectionDelinquencyId();
                $ticket->ContactNumber = $item->ContactNumber != null ? $item->ContactNumber : 'none';
                $ticket->GeoLocation = $item->Latitude != null ? ($item->Latitude . ',' . $item->Longitude) : '';
                $ticket->Status = 'Received';
                $ticket->UserId = Auth::id();
                $ticket->Office = env('APP_LOCATION');
                $ticket->CurrentMeterNo = $item->MeterNumber;
                $ticket->ServicePeriod = $period;
                $ticket->save();

                // CREATE LOG
                $ticketLog = new TicketLogs;
                $ticketLog->id = IDGenerator::generateIDandRandString();
                $ticketLog->TicketId = $ticket->id;
                $ticketLog->Log = "Ticket Filed";
                $ticketLog->LogDetails = "Ticket automatically created via Disconnection Automation Module";
                $ticketLog->UserId = Auth::id();
                $ticketLog->save();
            }

            $i++;
        }

        $tickets = DB::table('CRM_Tickets')
            ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->where('CRM_Tickets.ServicePeriod', $period)
            ->where('CRM_Tickets.Ticket', Tickets::getDisconnectionDelinquencyId())
            ->select('CRM_Tickets.id',
                'CRM_Tickets.AccountNumber',
                'CRM_Tickets.ConsumerName',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_Tickets.Sitio',
                'CRM_TicketsRepository.ParentTicket',
                'CRM_TicketsRepository.Name as Ticket',
                'CRM_TicketsRepository.Type as TicketType',
                'CRM_Tickets.Reason',
                'CRM_Tickets.ContactNumber',
                'CRM_Tickets.ReportedBy',
                'CRM_Tickets.ORNumber',
                'CRM_Tickets.ORDate',
                'CRM_Tickets.GeoLocation',
                'CRM_Tickets.Neighbor1',
                'CRM_Tickets.Neighbor2',
                'CRM_Tickets.Notes',
                'CRM_Tickets.Status',
                'CRM_Tickets.DateTimeDownloaded',
                'CRM_Tickets.DateTimeLinemanArrived',
                'CRM_Tickets.DateTimeLinemanExecuted',
                'CRM_Tickets.UserId',
                'CRM_ServiceConnectionCrew.StationName',
                'CRM_Tickets.created_at',
                'CRM_Tickets.updated_at',
                'CRM_Tickets.Trash')
            ->get();

        return view('/tickets/print_ticket_bulk', [
            'tickets' => $tickets
        ]);
    }

    public function ticketTally() {
        // TICKETS MATRIX
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->orderBy('Name')->get();

        $towns = Towns::orderBy('Town')->get();
        return view('/tickets/ticket_tally', [
            'parentTickets' => $parentTickets,
            'towns' => $towns,
        ]);
    }

    public function getTicketTally(Request $request) {
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->orderBy('Name')->get();

        $arr = [];
        $total = 0;
        $totalExecuted = 0;
        $totalUnexecuted = 0;
        foreach($parentTickets as $item) {
            array_push($arr, [
                'id' => $item->id,
                'Name' => $item->Name,
                'ReceivedTotal' => 'Parent',
                'NotExecutedTotal' => '',
                'ExecutedTotal' => ''
            ]);
            if ($request['Town'] == 'All') {
                $ticketCounts = DB::table('CRM_TicketsRepository')
                    ->where('Type', $request['Type'])
                    ->where('ParentTicket', $item->id)
                    ->select('id', 'Name',
                        DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket=CRM_TicketsRepository.id AND Trash IS NULL AND (created_at BETWEEN '" . $request['From'] . "' AND '" . $request['To'] . "')) AS ReceivedTotal"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket=CRM_TicketsRepository.id AND Trash IS NULL AND Status != 'Executed' AND (created_at BETWEEN '" . $request['From'] . "' AND '" . $request['To'] . "')) AS NotExecutedTotal"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket=CRM_TicketsRepository.id AND Trash IS NULL AND Status = 'Executed' AND (created_at BETWEEN '" . $request['From'] . "' AND '" . $request['To'] . "')) AS ExecutedTotal")      
                    )
                    ->get();
            } else {
                $ticketCounts = DB::table('CRM_TicketsRepository')
                    ->where('Type', $request['Type'])
                    ->where('ParentTicket', $item->id)
                    ->select('id', 'Name',
                        DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket=CRM_TicketsRepository.id AND Trash IS NULL AND Town='" . $request['Town'] . "' AND (created_at BETWEEN '" . $request['From'] . "' AND '" . $request['To'] . "')) AS ReceivedTotal"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket=CRM_TicketsRepository.id AND Trash IS NULL AND Town='" . $request['Town'] . "' AND Status != 'Executed' AND (created_at BETWEEN '" . $request['From'] . "' AND '" . $request['To'] . "')) AS NotExecutedTotal"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket=CRM_TicketsRepository.id AND Trash IS NULL AND Town='" . $request['Town'] . "' AND Status = 'Executed' AND (created_at BETWEEN '" . $request['From'] . "' AND '" . $request['To'] . "')) AS ExecutedTotal")      
                    )
                    ->get();
            }            
            
            foreach($ticketCounts as $ticketCount) {
                array_push($arr, [
                    'id' => $ticketCount->id,
                    'Name' => $ticketCount->Name,
                    'ReceivedTotal' => $ticketCount->ReceivedTotal,
                    'NotExecutedTotal' => $ticketCount->NotExecutedTotal,
                    'ExecutedTotal' => $ticketCount->ExecutedTotal
                ]);
                $total += intval($ticketCount->ReceivedTotal);
                $totalExecuted += intval($ticketCount->NotExecutedTotal);
                $totalUnexecuted += intval($ticketCount->ExecutedTotal);
            }
        }

        array_push($arr, [
            'id' => '0',
            'Name' => 'Total',
            'ReceivedTotal' => $total,
            'NotExecutedTotal' => $totalExecuted,
            'ExecutedTotal' => $totalUnexecuted
        ]);

        return response()->json($arr, 200);
    }

    public function getCrewMonitorData(Request $request) {
        $data = DB::table('CRM_ServiceConnectionCrew')
            ->select('id',
                'StationName',
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status NOT IN('Executed', 'Acted') AND CrewAssigned=CRM_ServiceConnectionCrew.id AND (Trash IS NULL OR Trash='No')) AS AllAssigned"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status IN('Downloaded by Crew') AND CrewAssigned=CRM_ServiceConnectionCrew.id AND (Trash IS NULL OR Trash='No')) AS Downloaded"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status IN('Received') AND CrewAssigned=CRM_ServiceConnectionCrew.id AND (Trash IS NULL OR Trash='No')) AS Undownloaded"),
                DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Status IN('Crew Arrived on Site') AND CrewAssigned=CRM_ServiceConnectionCrew.id AND (Trash IS NULL OR Trash='No')) AS SiteArrivals"),
            )
            ->orderBy('StationName')
            ->get();

        $output = "";
        foreach($data as $item) {
            $output .= "<tr>
                                <td>" . $item->StationName . "</td>
                                <td>" . $item->AllAssigned . "</td>
                                <td>" . $item->Undownloaded . "</td>
                                <td>" . $item->Downloaded . "</td>
                                <td>" . $item->SiteArrivals . "</td>
                            </tr>
                        ";
        }

        return response()->json($output, 200);
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

        return view('/tickets/relocation_search', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function createRelocation($id) {
        if ($id != null) {
            $serviceAccount = DB::connection('sqlsrvbilling')
                ->table('AccountMaster')
                ->where('AccountNumber', $id)
                ->select('AccountMaster.*')
                ->first();

            if ($serviceAccount != null) {
                $left = AccountMaster::where('Route', $serviceAccount->Route)
                    ->whereRaw("SequenceNumber < " . $serviceAccount->SequenceNumber)
                    ->orderByDesc('SequenceNumber')
                    ->first();

                $right = AccountMaster::where('Route', $serviceAccount->Route)
                    ->whereRaw("SequenceNumber > " . $serviceAccount->SequenceNumber)
                    ->orderBy('SequenceNumber')
                    ->first();
            } else {
                $left = null;

                $right = null;
            }
        } else {
            $serviceAccount = null;
            $left = null;
            $right = null;
        }

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        // TICKETS MATRIX
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->whereIn('id', ['1668541254405', '1668541254392'])->orderBy('Name')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        $inspectors = User::role('Inspector')->pluck('name', 'id'); // CHANGE PERMISSION TO WHATEVER VERIFIER NAME IS

        $history = DB::table('CRM_Tickets')
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
                        ->where('CRM_Tickets.AccountNumber', $id)
                        ->select('CRM_Tickets.ConsumerName', 
                            'CRM_Tickets.id',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay',
                            'CRM_TicketsRepository.Name',
                            'CRM_TicketsRepository.ParentTicket',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.Reason',
                            'CRM_Tickets.Status',)
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();

        $cond = 'new';

        $tickets = null;

        return view('tickets.create_relocation',   [
            'serviceAccount' => $serviceAccount,
            'towns' => $towns,
            'parentTickets' => $parentTickets,
            'crew' => $crew,
            'history' => $history,
            'cond' => $cond,
            'left' => $left,
            'right' => $right,
            'tickets' => $tickets,
            'inspectors' => $inspectors,
        ]);
    }

    public function storeRelocation(Request $request) {
        $input = $request->all();

        $scId = IDGenerator::generateID();

        $input['ServiceConnectionId'] = $scId;
        $input['InspectionId'] = $scId;
        $input['Status'] = 'For Inspection';

        $tickets = $this->ticketsRepository->create($input);

        // SAVE SERVICE CONNECTION
        $serviceConnection = new ServiceConnections;
        $serviceConnection->id = $scId;
        $serviceConnection->ServiceAccountName = $input['ConsumerName'];
        $serviceConnection->DateOfApplication = date('Y-m-d');
        $serviceConnection->Sitio = $input['Sitio'];
        $serviceConnection->Barangay = $input['Barangay'];
        $serviceConnection->Town = $input['Town'];
        $serviceConnection->ConnectionApplicationType = 'Relocation';
        $serviceConnection->Status = 'For Inspection';
        $serviceConnection->Office = env('APP_LOCATION');
        $serviceConnection->save();

        // SAVE INSPECTION
        $inspections = new ServiceConnectionInspections;
        $inspections->id = $scId;
        $inspections->ServiceConnectionId = $scId;
        $inspections->Status = 'FOR INSPECTION';
        $inspections->Inspector = $input['Inspector'];
        $inspections->save();

        // FILTER METER RELATED TICKETS
        // $ticket = DB::table('CRM_TicketsRepository')
        //     ->where('id', $tickets->Ticket)
        //     ->whereIn('ParentTicket', ['1668541254365', '1668541254387', '1668541254387', '1668541254422', '1668541254427']) // Mother Meter, KWH Meter, KWH Meter Transfer, Disconnection, Reconnection
        //     ->first(); 
            
        // if ($ticket != null) {
            
        // }
        // SAVE METER INFO
        $accountMeterInfo = DB::connection('sqlsrvbilling')
            ->table('AccountMaster')
            ->where('AccountNumber', $tickets->AccountNumber)
            ->select('*')
            ->first();
        
        if ($accountMeterInfo != null) {
            $tickets->CurrentMeterNo = $accountMeterInfo->MeterNumber;
            // EDIT LATER
            $tickets->GeoLocation = $accountMeterInfo->Item1;
            $tickets->save();
        }

        Flash::success('Tickets saved successfully.');

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID();
        $ticketLog->TicketId = $tickets->id;
        $ticketLog->Log = "Received";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        return redirect(route('tickets.show', [$tickets->id]));
    }

    public function crewFieldMonitor() {
        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->whereNotIn('id', ['1668541254405', '1668541254392'])->orderBy('Name')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->get();

        $status = DB::table('CRM_Tickets')
            ->whereRaw("Status NOT IN ('Executed', 'For Payment', 'For Inspection')")
            ->select('Status')
            ->groupBy('Status')
            ->orderByDesc('Status')
            ->get();

        return view('/tickets/crew_field_monitor', [
            'parentTickets' => $parentTickets,
            'crew' => $crew,
            'status' => $status
        ]);
    }

    public function getCrewFieldMonitorData(Request $request) {
        $ticket = $request['Ticket'];
        $status = $request['Status'];
        $crew = $request['Crew'];

        if ($ticket == 'All') {
            if ($status == 'All') {
                if ($crew == 'All') {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                } else {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.CrewAssigned', $crew)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                }
            } else {
                if ($crew == 'All') {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.Status', $status)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                } else {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.Status', $status)
                        ->where('CRM_Tickets.CrewAssigned', $crew)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                }
            }
            
        } else {
            if ($status == 'All') {
                if ($crew == 'All') {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.Ticket', $ticket)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                } else {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.CrewAssigned', $crew)
                        ->where('CRM_Tickets.Ticket', $ticket)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                }
            } else {
                if ($crew == 'All') {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.Status', $status)
                        ->where('CRM_Tickets.Ticket', $ticket)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                } else {
                    $data = DB::table('CRM_Tickets') 
                        ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                        ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_Tickets.CrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                        ->where('CRM_Tickets.Status', $status)
                        ->where('CRM_Tickets.CrewAssigned', $crew)
                        ->where('CRM_Tickets.Ticket', $ticket)
                        ->select('CRM_TicketsRepository.Name',
                            'CRM_Tickets.id',
                            'CRM_Tickets.ConsumerName',
                            'CRM_ServiceConnectionCrew.StationName',
                            'CRM_Tickets.created_at',
                            'CRM_Tickets.GeoLocation'
                        )
                        ->orderByDesc('CRM_Tickets.created_at')
                        ->get();
                }
            }
        }

        // $output = "";
        // foreach($data as $item) {
        //     $output .= "<tr>
        //                     <td>
        //                         (<span><a href='" . route('tickets.show', [$item->id]) . "'>" . $item->id . "</a></span>) <strong>" . $item->Name . "</strong><br>
        //                         <span>" . $item->ConsumerName . "</span><br>
        //                         <span>Crew: <strong>" . $item->StationName . "</strong></span><br>
        //                     </td>
        //                 </tr>";
        // }

        return response()->json($data, 200);
    }

    public function saveTicketLog(Request $request) {
        $log = $request['Title'];
        $notes = $request['Description'];
        $ticketId = $request['TicketId'];

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID();
        $ticketLog->TicketId = $ticketId;
        $ticketLog->Log = $log;
        $ticketLog->LogDetails = $notes;
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        return response()->json($ticketLog, 200);
    }

    public function neaKpsSummary(Request $request) {
        $town = $request['Town'];
        $from = $request['From'];
        $to = $request['To'];

        if ($town == 'All') {
            $data = DB::table('CRM_Tickets')
                ->select(
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1a"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1a"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1b"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1b"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.a' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received2a"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.a' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted2a"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received2c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted2c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received3c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted3c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4a"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4a"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4b"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4b"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4d"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4d"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received5"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted5"),
                )
                ->first();
        } else {
            $data = DB::table('CRM_Tickets')
            ->select(
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1a"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1a"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1b"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1b"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.a' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received2a"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.a' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted2a"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received2c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted2c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received3c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted3c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4a"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4a"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4b"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4b"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4d"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4d"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received5"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted5"),
            )
                ->first();
        }

        return view('/tickets/nea_kps_summary', [
            'towns' => Towns::orderBy('Town')->get(),
            'data' => $data,
        ]);
    }

    public function downloadKpsSummaryReport($town, $from, $to) {
        if ($town == 'All') {
            $data = DB::table('CRM_Tickets')
                ->select(
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1a"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1a"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1b"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1b"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received2c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted2c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received3c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted3c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4a"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4a"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4b"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4b"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4c"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4c"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4d"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4d"),

                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received5"),
                    DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Status != 'Received' AND Trash IS NULL AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted5"),
                )
                ->first();
        } else {
            $data = DB::table('CRM_Tickets')
            ->select(
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1a"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.a' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1a"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1b"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.b' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1b"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received1c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='1.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted1c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received2c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='2.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted2c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received3c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='3.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted3c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4a"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.a' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4a"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4b"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.b' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4b"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4c"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.c' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4c"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received4d"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='4.d' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted4d"),

                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Received5"),
                DB::raw("(SELECT COUNT(t.id) FROM CRM_Tickets t LEFT JOIN CRM_TicketsRepository tr ON t.Ticket=tr.id WHERE tr.KPSCategory='5' AND Status != 'Received' AND Trash IS NULL AND Town='" . $town . "' AND (t.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Acted5"),
            )
                ->first();
        }

        $town = $town=='All' ? 'All' : Towns::find($town)->Town;

        $export = new KPSTicketsExport($data, $town);

        return Excel::download($export, 'NEA-KPS-Report.xlsx');
    }

    public function kpsCustomerServiceParameters(Request $request) {
        $month = isset($request['Month']) ? $request['Month'] : '01';
        $year = isset($request['Year']) ? $request['Year'] : '1991';
        $office = isset($request['Office']) ? $request['Office'] : 'All';

        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $prevFrom = date('Y-m-d', strtotime($from . '-1 month' ));
        $prevTo = date('Y-m-d', strtotime($to . '-1 month' ));

        $data = [];

        if ($office == 'All') {
            // ====================================
            // 2 => Previous Month
            // ====================================
            $category2Previous = DB::table('CRM_ServiceConnections')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_ServiceConnections.ORDate, CRM_ServiceConnections.DateTimeOfEnergization) as 'Res'")
                )
                ->whereRaw("(CRM_ServiceConnections.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND  
                    ORDate IS NOT NULL AND DateTimeOfEnergization IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category2PreviousAvg = 0;
            foreach ($category2Previous as $itemX) {
                $category2PreviousAvg += intval($itemX->Res);
            } 
            if (count($category2Previous) > 0) {
                $category2PreviousAvg = $category2PreviousAvg/count($category2Previous);
            } else {
                $category2PreviousAvg = 0;
            } 

            // ====================================
            // 2 => Current Month
            // ====================================
            $category2Current = DB::table('CRM_ServiceConnections')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_ServiceConnections.ORDate, CRM_ServiceConnections.DateTimeOfEnergization) as 'Res'")
                )
                ->whereRaw("(CRM_ServiceConnections.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND  
                    ORDate IS NOT NULL AND DateTimeOfEnergization IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category2CurrentAvg = 0;
            foreach ($category2Current as $itemX) {
                $category2CurrentAvg += intval($itemX->Res);
            } 
            if (count($category2Current) > 0) {
                $category2CurrentAvg = $category2CurrentAvg/count($category2Current);
            } else {
                $category2CurrentAvg = 0;
            } 

            // ====================================
            // 3 => Previous Month
            // ====================================
            $category3Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeLinemanArrived, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='3' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND DateTimeLinemanArrived IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category3PreviousAvg = 0;
            foreach ($category3Previous as $itemX) {
                $category3PreviousAvg += intval($itemX->Res);
            } 
            if (count($category3Previous) > 0) {
                $category3PreviousAvg = $category3PreviousAvg/count($category3Previous);
            } else {
                $category3PreviousAvg = 0;
            } 

            // ====================================
            // 3 => Current Month
            // ====================================
            $category3Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeLinemanArrived, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='3' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND DateTimeLinemanArrived IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category3CurrentAvg = 0;
            foreach ($category3Current as $itemX) {
                $category3CurrentAvg += intval($itemX->Res);
            } 
            if (count($category3Current) > 0) {
                $category3CurrentAvg = $category3CurrentAvg/count($category3Current);
            } else {
                $category3CurrentAvg = 0;
            } 

            // ====================================
            // 4 => Previous Month
            // ====================================
            $category4Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeComplainLogged, CRM_Tickets.created_at) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.DateTimeComplainLogged BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='4' AND 
                    CRM_Tickets.created_at IS NOT NULL AND CRM_Tickets.DateTimeComplainLogged IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category4PreviousAvg = 0;
            foreach ($category4Previous as $itemX) {
                $category4PreviousAvg += intval($itemX->Res);
            } 
            if (count($category4Previous) > 0) {
                $category4PreviousAvg = $category4PreviousAvg/count($category4Previous);
            } else {
                $category4PreviousAvg = 0;
            } 

            // ====================================
            // 4 => Current Month
            // ====================================
            $category4Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeComplainLogged, CRM_Tickets.created_at) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.DateTimeComplainLogged BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='4' AND 
                    CRM_Tickets.created_at IS NOT NULL AND CRM_Tickets.DateTimeComplainLogged IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category4CurrentAvg = 0;
            foreach ($category4Current as $itemX) {
                $category4CurrentAvg += intval($itemX->Res);
            } 
            if (count($category4Current) > 0) {
                $category4CurrentAvg = $category4CurrentAvg/count($category4Current);
            } else {
                $category4CurrentAvg = 0;
            } 

            // ====================================
            // 6 => Previous Month
            // ====================================
            $category6Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeDownloaded) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='6' AND 
                    DateTimeDownloaded IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category6PreviousAvg = 0;
            foreach ($category6Previous as $itemX) {
                $category6PreviousAvg += intval($itemX->Res);
            } 
            if (count($category6Previous) > 0) {
                $category6PreviousAvg = $category6PreviousAvg/count($category6Previous);
            } else {
                $category6PreviousAvg = 0;
            } 

            // ====================================
            // 6 => Current Month
            // ====================================
            $category6Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeDownloaded) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='6' AND 
                    DateTimeDownloaded IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category6CurrentAvg = 0;
            foreach ($category6Current as $itemX) {
                $category6CurrentAvg += intval($itemX->Res);
            } 
            if (count($category6Current) > 0) {
                $category6CurrentAvg = $category6CurrentAvg/count($category6Current);
            } else {
                $category6CurrentAvg = 0;
            } 

            // ====================================
            // 7 => Previous Month
            // ====================================
            $category7Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='7' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category7PreviousAvg = 0;
            foreach ($category7Previous as $itemX) {
                $category7PreviousAvg += intval($itemX->Res);
            } 
            if (count($category7Previous) > 0) {
                $category7PreviousAvg = $category7PreviousAvg/count($category7Previous);
            } else {
                $category7PreviousAvg = 0;
            } 

            // ====================================
            // 7 => Current Month
            // ====================================
            $category7Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("(CRM_Tickets.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='7' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category7CurrentAvg = 0;
            foreach ($category7Current as $itemX) {
                $category7CurrentAvg += intval($itemX->Res);
            } 
            if (count($category7Current) > 0) {
                $category7CurrentAvg = $category7CurrentAvg/count($category7Current);
            } else {
                $category7CurrentAvg = 0;
            } 

            /**
             * CONSOLIDATE DATA
             */
            $data['Category2Previous'] = $category2PreviousAvg;
            $data['Category2Current'] = $category2CurrentAvg;
            $data['Category3Previous'] = $category3PreviousAvg;
            $data['Category3Current'] = $category3CurrentAvg;
            $data['Category4Previous'] = $category4PreviousAvg;
            $data['Category4Current'] = $category4CurrentAvg;
            $data['Category6Previous'] = $category6PreviousAvg;
            $data['Category6Current'] = $category6CurrentAvg;
            $data['Category7Previous'] = $category7PreviousAvg;
            $data['Category7Current'] = $category7CurrentAvg;
        } else {
            // ====================================
            // 2 => Previous Month
            // ====================================
            $category2Previous = DB::table('CRM_ServiceConnections')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_ServiceConnections.ORDate, CRM_ServiceConnections.DateTimeOfEnergization) as 'Res'")
                )
                ->whereRaw("CRM_ServiceConnections.Office='" . $office . "' AND (CRM_ServiceConnections.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND  
                    ORDate IS NOT NULL AND DateTimeOfEnergization IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category2PreviousAvg = 0;
            foreach ($category2Previous as $itemX) {
                $category2PreviousAvg += intval($itemX->Res);
            } 
            if (count($category2Previous) > 0) {
                $category2PreviousAvg = $category2PreviousAvg/count($category2Previous);
            } else {
                $category2PreviousAvg = 0;
            } 

            // ====================================
            // 2 => Current Month
            // ====================================
            $category2Current = DB::table('CRM_ServiceConnections')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_ServiceConnections.ORDate, CRM_ServiceConnections.DateTimeOfEnergization) as 'Res'")
                )
                ->whereRaw("CRM_ServiceConnections.Office='" . $office . "' AND (CRM_ServiceConnections.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND  
                    ORDate IS NOT NULL AND DateTimeOfEnergization IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category2CurrentAvg = 0;
            foreach ($category2Current as $itemX) {
                $category2CurrentAvg += intval($itemX->Res);
            } 
            if (count($category2Current) > 0) {
                $category2CurrentAvg = $category2CurrentAvg/count($category2Current);
            } else {
                $category2CurrentAvg = 0;
            } 

            // ====================================
            // 3 => Previous Month
            // ====================================
            $category3Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeLinemanArrived, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='3' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND DateTimeLinemanArrived IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category3PreviousAvg = 0;
            foreach ($category3Previous as $itemX) {
                $category3PreviousAvg += intval($itemX->Res);
            } 
            if (count($category3Previous) > 0) {
                $category3PreviousAvg = $category3PreviousAvg/count($category3Previous);
            } else {
                $category3PreviousAvg = 0;
            } 

            // ====================================
            // 3 => Current Month
            // ====================================
            $category3Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeLinemanArrived, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='3' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND DateTimeLinemanArrived IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category3CurrentAvg = 0;
            foreach ($category3Current as $itemX) {
                $category3CurrentAvg += intval($itemX->Res);
            } 
            if (count($category3Current) > 0) {
                $category3CurrentAvg = $category3CurrentAvg/count($category3Current);
            } else {
                $category3CurrentAvg = 0;
            } 

            // ====================================
            // 4 => Previous Month
            // ====================================
            $category4Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeComplainLogged, CRM_Tickets.created_at) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.DateTimeComplainLogged BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='4' AND 
                    CRM_Tickets.created_at IS NOT NULL AND CRM_Tickets.DateTimeComplainLogged IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category4PreviousAvg = 0;
            foreach ($category4Previous as $itemX) {
                $category4PreviousAvg += intval($itemX->Res);
            } 
            if (count($category4Previous) > 0) {
                $category4PreviousAvg = $category4PreviousAvg/count($category4Previous);
            } else {
                $category4PreviousAvg = 0;
            } 

            // ====================================
            // 4 => Current Month
            // ====================================
            $category4Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.DateTimeComplainLogged, CRM_Tickets.created_at) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.DateTimeComplainLogged BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='4' AND 
                    CRM_Tickets.created_at IS NOT NULL AND CRM_Tickets.DateTimeComplainLogged IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category4CurrentAvg = 0;
            foreach ($category4Current as $itemX) {
                $category4CurrentAvg += intval($itemX->Res);
            } 
            if (count($category4Current) > 0) {
                $category4CurrentAvg = $category4CurrentAvg/count($category4Current);
            } else {
                $category4CurrentAvg = 0;
            } 

            // ====================================
            // 6 => Previous Month
            // ====================================
            $category6Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeDownloaded) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='6' AND 
                    DateTimeDownloaded IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category6PreviousAvg = 0;
            foreach ($category6Previous as $itemX) {
                $category6PreviousAvg += intval($itemX->Res);
            } 
            if (count($category6Previous) > 0) {
                $category6PreviousAvg = $category6PreviousAvg/count($category6Previous);
            } else {
                $category6PreviousAvg = 0;
            } 

            // ====================================
            // 6 => Current Month
            // ====================================
            $category6Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeDownloaded) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='6' AND 
                    DateTimeDownloaded IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category6CurrentAvg = 0;
            foreach ($category6Current as $itemX) {
                $category6CurrentAvg += intval($itemX->Res);
            } 
            if (count($category6Current) > 0) {
                $category6CurrentAvg = $category6CurrentAvg/count($category6Current);
            } else {
                $category6CurrentAvg = 0;
            } 

            // ====================================
            // 7 => Previous Month
            // ====================================
            $category7Previous = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.created_at BETWEEN '" . $prevFrom . "' AND '" . $prevTo . "') AND CRM_TicketsRepository.KPSHourlyCategory='7' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category7PreviousAvg = 0;
            foreach ($category7Previous as $itemX) {
                $category7PreviousAvg += intval($itemX->Res);
            } 
            if (count($category7Previous) > 0) {
                $category7PreviousAvg = $category7PreviousAvg/count($category7Previous);
            } else {
                $category7PreviousAvg = 0;
            } 

            // ====================================
            // 7 => Current Month
            // ====================================
            $category7Current = DB::table('CRM_Tickets')
                ->leftJoin('CRM_TicketsRepository', 'CRM_Tickets.Ticket', '=', 'CRM_TicketsRepository.id')
                ->select(
                    DB::raw("DATEDIFF(hh, CRM_Tickets.created_at, CRM_Tickets.DateTimeLinemanExecuted) as 'Res'")
                )
                ->whereRaw("CRM_Tickets.Office='" . $office . "' AND (CRM_Tickets.created_at BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_TicketsRepository.KPSHourlyCategory='7' AND 
                    DateTimeLinemanExecuted IS NOT NULL AND CRM_Tickets.created_at IS NOT NULL AND (Trash IS NULL OR Trash='No')")
                ->get();

            $category7CurrentAvg = 0;
            foreach ($category7Current as $itemX) {
                $category7CurrentAvg += intval($itemX->Res);
            } 
            if (count($category7Current) > 0) {
                $category7CurrentAvg = $category7CurrentAvg/count($category7Current);
            } else {
                $category7CurrentAvg = 0;
            } 

            /**
             * CONSOLIDATE DATA
             */
            $data['Category2Previous'] = $category2PreviousAvg;
            $data['Category2Current'] = $category2CurrentAvg;
            $data['Category3Previous'] = $category3PreviousAvg;
            $data['Category3Current'] = $category3CurrentAvg;
            $data['Category4Previous'] = $category4PreviousAvg;
            $data['Category4Current'] = $category4CurrentAvg;
            $data['Category6Previous'] = $category6PreviousAvg;
            $data['Category6Current'] = $category6CurrentAvg;
            $data['Category7Previous'] = $category7PreviousAvg;
            $data['Category7Current'] = $category7CurrentAvg;
        }

        $parentTickets = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->orderBy('Name')->get();

        return view('/tickets/kps_customer_service_parameters', [
            'parentTickets' => $parentTickets,
            'data' => $data,
        ]);
    }

    public function monthlyPerTown(Request $request) {
        $month = isset($request['Month']) ? $request['Month'] : '01';
        $year = isset($request['Year']) ? $request['Year'] : '1991';

        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $data = DB::table('CRM_Towns')
                ->select(
                    'Town',
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254404', '1668541254399', '1668541254400', '1655791242281', '1672792458611', '1672792439659', '1655791203676') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS SDW"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254390', '1672792232225') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS MeterReplacements"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254393', '1668541254394', '1668541254395', '1668541254396', '1668541254397') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS MeterTransfer"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254428', '1668541254429', '1668541254430') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Reconnection"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket NOT IN ('1668541254428', '1668541254429', '1668541254430',
                            '1668541254404', '1668541254399', '1668541254400', '1655791242281', '1672792458611', '1672792439659', '1655791203676',
                            '1668541254390', '1672792232225',
                            '1668541254393', '1668541254394', '1668541254395', '1668541254396', '1668541254397',
                            '1668541254428', '1668541254429', '1668541254430') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Others")
                )
            ->orderBy('Town')
            ->get();

        return view('/tickets/monthly_per_town', [
            'data' => $data,
        ]);
    }

    public function downoadMonthlyPerTown($month, $year) {
        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        $data = DB::table('CRM_Towns')
                ->select(
                    'Town',
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254404', '1668541254399', '1668541254400', '1655791242281', '1672792458611', '1672792439659', '1655791203676') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS SDW"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254390', '1672792232225') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS MeterReplacements"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254393', '1668541254394', '1668541254395', '1668541254396', '1668541254397') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS MeterTransfer"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket IN ('1668541254428', '1668541254429', '1668541254430') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Reconnection"),
                    DB::raw("(SELECT COUNT(id) FROM CRM_Tickets WHERE Ticket NOT IN ('1668541254428', '1668541254429', '1668541254430',
                            '1668541254404', '1668541254399', '1668541254400', '1655791242281', '1672792458611', '1672792439659', '1655791203676',
                            '1668541254390', '1672792232225',
                            '1668541254393', '1668541254394', '1668541254395', '1668541254396', '1668541254397',
                            '1668541254428', '1668541254429', '1668541254430') 
                        AND (Trash IS NULL OR Trash='No') AND Town=CRM_Towns.id AND (created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Others")
                )
            ->orderBy('Town')
            ->get();

        $headers = [
            'Town',
            'SDW Related',
            'Meter Replacements',
            'Meter Transfer',
            'Reconnection',
            'Others'
        ];

        $export = new DynamicExport($data->toArray(), $headers, null, 'TICKETS MONTHLY SUMMARY REPORT PER TOWN FOR ' . date('F Y', strtotime($year . '-' . $month . '-01')));

        return Excel::download($export, 'Ticket-Monthly-Summary-Report-per-Town.xlsx');
    }
}
