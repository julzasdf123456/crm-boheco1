<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemberConsumersRequest;
use App\Http\Requests\UpdateMemberConsumersRequest;
use App\Repositories\MemberConsumersRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\MemberConsumerTypes;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Barangays;
use App\Models\Towns;
use App\Models\MemberConsumers;
use App\Models\Notifiers;
use App\Models\MemberConsumerChecklistsRep;
use App\Models\TransactionDetails;
use App\Models\TransactionIndex;
use App\Models\ServiceConnections;
use App\Exports\DynamicExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class MemberConsumersController extends AppBaseController
{
    /** @var  MemberConsumersRepository */
    private $memberConsumersRepository;

    public function __construct(MemberConsumersRepository $memberConsumersRepo)
    {
        $this->middleware('auth');
        $this->memberConsumersRepository = $memberConsumersRepo;
    }

    /**
     * Display a listing of the MemberConsumers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $memberConsumers = $this->memberConsumersRepository->all();

        return view('member_consumers.index')
            ->with('memberConsumers', $memberConsumers);
    }

    /**
     * Show the form for creating a new MemberConsumers.
     *
     * @return Response
     */
    public function create()
    {
        $memberConsumers = null;

        $types = MemberConsumerTypes::orderByDesc('Id')->pluck('Type', 'Id');

        $barangays = Barangays::orderBy('Barangay')->pluck('Barangay', 'id');

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');
        
        $cond = 'new';

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('member_consumers.create', [
                'memberConsumers' => $memberConsumers, 
                'types' => $types, 
                'cond' => $cond, 
                'barangays' => $barangays, 
                'towns' => $towns
            ]);
        } else {
            return abort(403, "You're not authorized to create a membership application.");
        }
        
    }

    /**
     * Store a newly created MemberConsumers in storage.
     *
     * @param CreateMemberConsumersRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberConsumersRequest $request)
    {
        $input = $request->all();

        if ($input['Id'] != null) {
            $mco = MemberConsumers::find($input['Id']);

            if ($mco != null) {
                $memberConsumers = $this->memberConsumersRepository->find($mco->Id);

                if (empty($memberConsumers)) {
                    Flash::error('Member Consumers not found');

                    return redirect(route('memberConsumers.index'));
                }
                $input['FirstName'] = strtoupper($input['FirstName']);
                $input['MiddleName'] = strtoupper($input['MiddleName']);
                $input['LastName'] = strtoupper($input['LastName']);
                $memberConsumers = $this->memberConsumersRepository->update($request->all(), $mco->Id);

                if ($input['CivilStatus'] == 'Married') {
                    return redirect(route('memberConsumerSpouses.create', [$mco->Id]));
                } else {
                    // return redirect(route('memberConsumers.assess-checklists', [$input['Id']]));
                    // return redirect(route('serviceConnections.create_new', [$mco->Id]));
                    return redirect(route('memberConsumers.show', [$mco->Id]));
                }
            } else {
                $input['FirstName'] = strtoupper($input['FirstName']);
                $input['MiddleName'] = strtoupper($input['MiddleName']);
                $input['LastName'] = strtoupper($input['LastName']);
                $memberConsumers = $this->memberConsumersRepository->create($input);

                Flash::success('Member Consumers saved successfully.');

                // CREATE NOTIFICATION
                if ($input['ContactNumbers'] != null) {
                    if (strlen($input['ContactNumbers'] > 9)) {
                        $notifier = new Notifiers;
                        $notifier->id = IDGenerator::generateIDandRandString();
                        $notifier->Notification = 'Congratulations! You have been successfully registered as a Member-Consumer-Owner of BOHECO I. This is a system generated message.';
                        $notifier->From = Auth::id();
                        $notifier->Status = 'SENT';
                        $notifier->Intent = "MEMBERSHIP REGISTRATION"; 
                        $notifier->ObjectId = $input['Id'];
                        $notifier->ContactNumber = $input['ContactNumbers'];
                        $notifier->save();
                    }
                }

                if ($input['CivilStatus'] == 'Married') {
                    return redirect(route('memberConsumerSpouses.create', [$input['Id']]));
                } else {
                    // return redirect(route('memberConsumers.assess-checklists', [$input['Id']]));
                    // return redirect(route('serviceConnections.create_new', [$memberConsumers->Id]));
                    return redirect(route('memberConsumers.show', [$memberConsumers->Id]));
                }
            }
        } else {
            return abort('ID Not found!', 404);
        }      
    }

    /**
     * Display the specified MemberConsumers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberConsumers = DB::table('CRM_MemberConsumers')
                            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('users', 'CRM_MemberConsumers.UserId', '=', 'users.id')
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
                                    'CRM_MemberConsumers.ORNumber', 
                                    'CRM_MemberConsumers.ORDate', 
                                    'CRM_MemberConsumers.Sitio as Sitio', 
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'users.name',
                                    'CRM_Barangays.Barangay as Barangay')
                            ->where('CRM_MemberConsumers.Id', $id)
                            ->first();

        $memberConsumerSpouse = DB::table('CRM_MemberConsumerSpouse')
                            ->leftJoin('CRM_MemberConsumers', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.id')
                            ->select('CRM_MemberConsumerSpouse.*')
                            ->where('CRM_MemberConsumerSpouse.MemberConsumerId', $id)
                            ->first();

        if (empty($memberConsumers)) {
            Flash::error('Member Consumers not found');

            return redirect(route('memberConsumers.index'));
        }
        
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['view membership', 'Super Admin'])) {
            return view('member_consumers.show', ['memberConsumers' => $memberConsumers, 'memberConsumerSpouse' => $memberConsumerSpouse]);
        } else {
            return abort(403, "You're not authorized to access this page.");
        }

        
    }

    /**
     * Show the form for editing the specified MemberConsumers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $memberConsumers = $this->memberConsumersRepository->find($id);

        $types = MemberConsumerTypes::orderByDesc('Id')->pluck('Type', 'Id');

        $barangays = Barangays::orderBy('Barangay')->pluck('Barangay', 'id');

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $cond = 'edit';

        if (empty($memberConsumers)) {
            Flash::error('Member Consumers not found');

            return redirect(route('memberConsumers.index'));
        }

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'Super Admin'])) {
            return view('member_consumers.edit', ['memberConsumers' => $memberConsumers, 'types' => $types, 'cond' => $cond, 'barangays' => $barangays, 'towns' => $towns]);
        } else {
            return abort(403, "You're not authorized to update a membership application.");
        }

    }

    /**
     * Update the specified MemberConsumers in storage.
     *
     * @param int $id
     * @param UpdateMemberConsumersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberConsumersRequest $request)
    {
        $memberConsumers = $this->memberConsumersRepository->find($id);

        if (empty($memberConsumers)) {
            Flash::error('Member Consumers not found');

            return redirect(route('memberConsumers.index'));
        }

        $memberConsumers = $this->memberConsumersRepository->update($request->all(), $id);

        Flash::success('Member Consumers updated successfully.');

        return redirect(route('memberConsumers.show', [$id]));
    }

    /**
     * Remove the specified MemberConsumers from storage.
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
            $memberConsumers = $this->memberConsumersRepository->find($id);

            if (empty($memberConsumers)) {
                Flash::error('Member Consumers not found');

                return redirect(route('memberConsumers.index'));
            }

            $this->memberConsumersRepository->delete($id);

            Flash::success('Member Consumers deleted successfully.');

            return redirect(route('memberConsumers.index'));
        } else {
            return abort(403, "You're not authorized to delete a membership application.");
        }        
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
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_MemberConsumers.LastName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.Id', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.MiddleName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_MemberConsumers.FirstName', 'LIKE', '%' . $query . '%')
                    ->whereRaw("CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT') OR CRM_MemberConsumers.Notes IS NULL")
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
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->whereRaw("CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT') OR CRM_MemberConsumers.Notes IS NULL")
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
                                                <a href="' . route('memberConsumers.show', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="' . route('memberConsumers.edit', [$row->ConsumerId]) . '" class="text-warning" style="margin-top: 5px; padding: 8px;" title="Edit"><i class="fas fa-pen"></i></a>
                                                <a href="' . route('memberConsumers.print-membership-application', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="Print Membership Application Form"><i class="fas fa-print"></i></a>
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

    public function assessChecklists($id) {
        $memberConsumers = $this->memberConsumersRepository->find($id);

        $checklist = MemberConsumerChecklistsRep::all();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/member_consumers/assess_checklists', ['memberConsumers' => $memberConsumers, 'checklist' => $checklist]);
        } else {
            return abort(403, "You're not authorized to create a membership application.");
        }
        
    }

    public function captureImage($id) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'Super Admin'])) {
            return view('/member_consumers/capture_image', ['id' => $id]);
        } else {
            return abort(403, "You're not authorized to update a membership application.");
        }
        
    }

    public function printMembershipApplication($id) {
        $memberConsumers = DB::table('CRM_MemberConsumers')
                            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('users', 'CRM_MemberConsumers.UserId', '=', 'users.id')
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
                                    'CRM_MemberConsumers.ORNumber', 
                                    'CRM_MemberConsumers.ORDate', 
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'users.name',
                                    'CRM_Barangays.Barangay as Barangay')
                            ->where('CRM_MemberConsumers.Id', $id)
                            ->first();

        
        if ($memberConsumers != null) {
            $memberConsumerSpouse = DB::table('CRM_MemberConsumerSpouse')
                            ->leftJoin('CRM_MemberConsumers', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.id')
                            ->select('CRM_MemberConsumerSpouse.*')
                            ->where('CRM_MemberConsumerSpouse.MemberConsumerId', $id)
                            ->first();

            $serviceConnection = ServiceConnections::where('MemberConsumerId', $id)->first();

            if ($serviceConnection != null) {
                $transaction = DB::table('Cashier_TransactionIndex')
                    ->select('ORNumber',
                        'ORDate',
                        DB::raw("(SELECT Amount FROM Cashier_TransactionDetails WHERE Particular='Membership Fee' AND TransactionIndexId=Cashier_TransactionIndex.id) AS Amount"))
                    ->where('ServiceConnectionId', $serviceConnection->id)
                    ->first();
            } else {
                $transaction = null;
            }
            return view('/member_consumers/print_membership_application', [
                'memberConsumers' => $memberConsumers,
                'transaction' => $transaction,
                'serviceConnection' => $serviceConnection,
                'memberConsumerSpouse' => $memberConsumerSpouse,
            ]);
        } else {
            return abort(404, 'Member-Consumer not found');
        }
    }

    public function printCertificate($id) {
        $memberConsumers = DB::table('CRM_MemberConsumers')
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
                                    'CRM_MemberConsumerTypes.*',
                                    'CRM_Towns.Town as Town',
                                    'CRM_Barangays.Barangay as Barangay')
                            ->where('CRM_MemberConsumers.Id', $id)
                            ->first();

        return view('/member_consumers/print_certificate', [
            'memberConsumer' => $memberConsumers
        ]);
    }

    public function monthlyReports(Request $request) {
        $town = isset($request['Town']) ? $request['Town'] : '';
        $month = isset($request['Month']) ? $request['Month'] : '01';
        $year = isset($request['Year']) ? $request['Year'] : '1970';
        $office = $request['Office'];

        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        if ($town == 'All') {
            if  ($office == 'All') {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseLastName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseSuffix')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "')")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND Office='" . $office . "'")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            }
        } else {
            if  ($office == 'All') {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_MemberConsumers.Town='" . $town . "'")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND Office='" . $office . "' AND CRM_MemberConsumers.Town='" . $town . "'")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            }
        }

        return view('/member_consumers/monthly_reports', [
            'towns' => Towns::orderBy('Town')->get(),
            'data' => $data,
        ]);
    }

    public function downloadMonthlyReports($town, $month, $year, $office) {
        $from = $year . '-' . $month . '-01';
        $to = date('Y-m-d', strtotime('last day of ' . $from));

        if ($town == 'All') {
            if  ($office == 'All') {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseLastName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseSuffix')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "')")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND Office='" . $office . "'")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            }
        } else {
            if  ($office == 'All') {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_MemberConsumers.Town='" . $town . "'")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                    ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                    ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                    ->select('CRM_MemberConsumers.Id',
                                    'CRM_MemberConsumers.MembershipType',
                                    'CRM_MemberConsumers.FirstName', 
                                    'CRM_MemberConsumers.MiddleName', 
                                    'CRM_MemberConsumers.LastName', 
                                    'CRM_MemberConsumers.OrganizationName', 
                                    'CRM_MemberConsumers.Suffix', 
                                    'CRM_MemberConsumers.DateApplied', 
                                    'CRM_MemberConsumers.Sitio', 
                                    'CRM_MemberConsumerTypes.Type',
                                    'CRM_Towns.Town',
                                    'CRM_Barangays.Barangay',
                                    'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                    'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                    ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND Office='" . $office . "' AND CRM_MemberConsumers.Town='" . $town . "'")
                    ->orderBy('CRM_MemberConsumers.DateApplied')
                    ->orderBy('CRM_MemberConsumers.LastName')
                    ->get();
            }
        }

        $arr = [];
        $i=1;
        foreach ($data as $item) {
            array_push($arr, [
                'No' => $i,
                'id' => $item->Id,
                'ApplicantName' => strtoupper(MemberConsumers::serializeMemberNameFormal($item)),
                'Spouse' => strtoupper(MemberConsumers::serializeSpouseDeclared($item)),
                'Address' => strtoupper(MemberConsumers::getAddress($item)),
                'Category' => strtoupper($item->Type),
                'DateApplied' => date('M d, Y', strtotime($item->DateApplied)),
            ]);
            $i++;
        }

        $headers = [
            '#',
            'ID',
            'Applicant Name',
            'Applicant Spouse',
            'Address',
            'Category',
            'Date Applied'
        ];

        $export = new DynamicExport($arr, $headers, null, 'Membership Report for ' . date('F Y', strtotime($from)));

        return Excel::download($export, 'Monthly-Membership-Report.xlsx');
    }

    public function quarterlyReports(Request $request) {
        $town = isset($request['Town']) ? $request['Town'] : '';
        $quarter = isset($request['Quarter']) ? $request['Quarter'] : '01';
        $year = isset($request['Year']) ? $request['Year'] : '1970';

        if ($quarter != null && $year != null) {
            if ($quarter == '01') {
                $from = $year . '-01-01';
                $to = date('Y-m-d', strtotime('last day of March ' . $year));
            } elseif ($quarter == '02') {
                $from = $year . '-04-01';
                $to = date('Y-m-d', strtotime('last day of June ' . $year));
            } elseif ($quarter == '03') {
                $from = $year . '-07-01';
                $to = date('Y-m-d', strtotime('last day of September ' . $year));
            } else {
                $from = $year . '-10-01';
                $to = date('Y-m-d', strtotime('last day of December ' . $year));
            }            

            if ($town == 'All') {
                $data = DB::table('CRM_MemberConsumers')
                        ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                        ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                        ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                        ->select('CRM_MemberConsumers.Id',
                                        'CRM_MemberConsumers.MembershipType',
                                        'CRM_MemberConsumers.FirstName', 
                                        'CRM_MemberConsumers.MiddleName', 
                                        'CRM_MemberConsumers.LastName', 
                                        'CRM_MemberConsumers.OrganizationName', 
                                        'CRM_MemberConsumers.Suffix', 
                                        'CRM_MemberConsumers.DateApplied', 
                                        'CRM_MemberConsumers.Sitio', 
                                        'CRM_MemberConsumerTypes.Type',
                                        'CRM_Towns.Town',
                                        'CRM_Barangays.Barangay',
                                        'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                        'CRM_MemberConsumerSpouse.LastName AS SpouseLastName', 
                                        'CRM_MemberConsumerSpouse.Suffix AS SpouseSuffix')
                        ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "')")
                        ->orderBy('CRM_MemberConsumers.DateApplied')
                        ->orderBy('CRM_MemberConsumers.LastName')
                        ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                        ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                        ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                        ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                        ->select('CRM_MemberConsumers.Id',
                                        'CRM_MemberConsumers.MembershipType',
                                        'CRM_MemberConsumers.FirstName', 
                                        'CRM_MemberConsumers.MiddleName', 
                                        'CRM_MemberConsumers.LastName', 
                                        'CRM_MemberConsumers.OrganizationName', 
                                        'CRM_MemberConsumers.Suffix', 
                                        'CRM_MemberConsumers.DateApplied', 
                                        'CRM_MemberConsumers.Sitio', 
                                        'CRM_MemberConsumerTypes.Type',
                                        'CRM_Towns.Town',
                                        'CRM_Barangays.Barangay',
                                        'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                        'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                        'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                        ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_MemberConsumers.Town='" . $town . "'")
                        ->orderBy('CRM_MemberConsumers.DateApplied')
                        ->orderBy('CRM_MemberConsumers.LastName')
                        ->get();
            }
        } else {
            $data = [];
        }
        
        return view('/member_consumers/quarterly_reports', [
            'towns' => Towns::orderBy('Town')->get(),
            'data' => $data,
        ]);
    }

    public function downloadQuarterlyReports($town, $quarter, $year) {
        if ($quarter != null && $year != null) {
            if ($quarter == '01') {
                $from = $year . '-01-01';
                $to = date('Y-m-d', strtotime('last day of March ' . $year));
            } elseif ($quarter == '02') {
                $from = $year . '-04-01';
                $to = date('Y-m-d', strtotime('last day of June ' . $year));
            } elseif ($quarter == '03') {
                $from = $year . '-07-01';
                $to = date('Y-m-d', strtotime('last day of September ' . $year));
            } else {
                $from = $year . '-10-01';
                $to = date('Y-m-d', strtotime('last day of December ' . $year));
            }            

            if ($town == 'All') {
                $data = DB::table('CRM_MemberConsumers')
                        ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                        ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                        ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                        ->select('CRM_MemberConsumers.Id',
                                        'CRM_MemberConsumers.MembershipType',
                                        'CRM_MemberConsumers.FirstName', 
                                        'CRM_MemberConsumers.MiddleName', 
                                        'CRM_MemberConsumers.LastName', 
                                        'CRM_MemberConsumers.OrganizationName', 
                                        'CRM_MemberConsumers.Suffix', 
                                        'CRM_MemberConsumers.DateApplied', 
                                        'CRM_MemberConsumers.Sitio', 
                                        'CRM_MemberConsumerTypes.Type',
                                        'CRM_Towns.Town',
                                        'CRM_Barangays.Barangay',
                                        'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                        'CRM_MemberConsumerSpouse.LastName AS SpouseLastName', 
                                        'CRM_MemberConsumerSpouse.Suffix AS SpouseSuffix')
                        ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "')")
                        ->orderBy('CRM_MemberConsumers.DateApplied')
                        ->orderBy('CRM_MemberConsumers.LastName')
                        ->get();
            } else {
                $data = DB::table('CRM_MemberConsumers')
                        ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                        ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                        ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_MemberConsumerSpouse', 'CRM_MemberConsumerSpouse.MemberConsumerId', '=', 'CRM_MemberConsumers.Id')
                        ->select('CRM_MemberConsumers.Id',
                                        'CRM_MemberConsumers.MembershipType',
                                        'CRM_MemberConsumers.FirstName', 
                                        'CRM_MemberConsumers.MiddleName', 
                                        'CRM_MemberConsumers.LastName', 
                                        'CRM_MemberConsumers.OrganizationName', 
                                        'CRM_MemberConsumers.Suffix', 
                                        'CRM_MemberConsumers.DateApplied', 
                                        'CRM_MemberConsumers.Sitio', 
                                        'CRM_MemberConsumerTypes.Type',
                                        'CRM_Towns.Town',
                                        'CRM_Barangays.Barangay',
                                        'CRM_MemberConsumerSpouse.FirstName AS SpouseFirstName', 
                                        'CRM_MemberConsumerSpouse.LastName AS SpouseFirstName', 
                                        'CRM_MemberConsumerSpouse.Suffix AS SpouseFirstName')
                        ->whereRaw("(DateApplied BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_MemberConsumers.Town='" . $town . "'")
                        ->orderBy('CRM_MemberConsumers.DateApplied')
                        ->orderBy('CRM_MemberConsumers.LastName')
                        ->get();
            }
        } else {
            $data = [];
        }

        $arr = [];
        $i=1;
        foreach ($data as $item) {
            array_push($arr, [
                'No' => $i,
                'id' => $item->Id,
                'ApplicantName' => strtoupper(MemberConsumers::serializeMemberNameFormal($item)),
                'Spouse' => strtoupper(MemberConsumers::serializeSpouseDeclared($item)),
                'Address' => strtoupper(MemberConsumers::getAddress($item)),
                'Category' => strtoupper($item->Type),
                'DateApplied' => date('M d, Y', strtotime($item->DateApplied)),
            ]);
            $i++;
        }

        $headers = [
            '#',
            'ID',
            'Applicant Name',
            'Applicant Spouse',
            'Address',
            'Category',
            'Date Applied'
        ];

        $export = new DynamicExport($arr, $headers, null, 'Membership Report for Quarter ' . $quarter);

        return Excel::download($export, 'Quarterly-Membership-Report.xlsx');
    }

    public function dailyMonitor() {
        return view('/member_consumers/daily_monitor', [

        ]);
    }

    public function dailyMonitorData(Request $request) {
        $date = $request['Date'];

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
                            'CRM_MemberConsumers.ORNumber', 
                            'CRM_MemberConsumers.ORDate', 
                            'CRM_MemberConsumers.Sitio as Sitio', 
                            'CRM_MemberConsumerTypes.*',
                            'CRM_Towns.Town as Town',
                            'CRM_Barangays.Barangay as Barangay')
            ->whereRaw("CRM_MemberConsumers.DateApplied='" . $date . "'")
            ->orderBy('CRM_MemberConsumers.FirstName')
            ->get();

        $output = '';
        foreach ($data as $item) {
            $output .= '
                        <tr>
                            <td><a href="' . route('memberConsumers.show', [$item->ConsumerId]) . '">' . $item->ConsumerId . '</a></td>
                            <td>' . MemberConsumers::serializeMemberName($item) . '</td>
                            <td>' . MemberConsumers::getAddress($item) . '</td>
                            <td>' . $item->ORNumber . '</td>
                            <td>' . ($item->ORDate != null ? date('M d, Y', strtotime($item->ORDate)) : '') . '</td>
                        </tr>   
                    ';
        }

        return response()->json($output, 200);
    }
}
