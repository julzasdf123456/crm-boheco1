<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMiscellaneousApplicationsRequest;
use App\Http\Requests\UpdateMiscellaneousApplicationsRequest;
use App\Repositories\MiscellaneousApplicationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Towns;
use App\Models\MiscellaneousPayments;
use App\Models\MiscellaneousApplications;
use App\Models\IDGenerator;
use App\Models\CRMQueue;
use App\Models\CRMDetails;
use App\Models\TicketLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class MiscellaneousApplicationsController extends AppBaseController
{
    /** @var MiscellaneousApplicationsRepository $miscellaneousApplicationsRepository*/
    private $miscellaneousApplicationsRepository;

    public function __construct(MiscellaneousApplicationsRepository $miscellaneousApplicationsRepo)
    {
        $this->middleware('auth');
        $this->miscellaneousApplicationsRepository = $miscellaneousApplicationsRepo;
    }

    /**
     * Display a listing of the MiscellaneousApplications.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->all();

        return view('miscellaneous_applications.index')
            ->with('miscellaneousApplications', $miscellaneousApplications);
    }

    /**
     * Show the form for creating a new MiscellaneousApplications.
     *
     * @return Response
     */
    public function create()
    {
        return view('miscellaneous_applications.create');
    }

    /**
     * Store a newly created MiscellaneousApplications in storage.
     *
     * @param CreateMiscellaneousApplicationsRequest $request
     *
     * @return Response
     */
    public function store(CreateMiscellaneousApplicationsRequest $request)
    {
        $input = $request->all();

        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->create($input);

        Flash::success('Miscellaneous Applications saved successfully.');

        return redirect(route('miscellaneousApplications.index'));
    }

    /**
     * Display the specified MiscellaneousApplications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->find($id);

        if (empty($miscellaneousApplications)) {
            Flash::error('Miscellaneous Applications not found');

            return redirect(route('miscellaneousApplications.index'));
        }

        return view('miscellaneous_applications.show')->with('miscellaneousApplications', $miscellaneousApplications);
    }

    /**
     * Show the form for editing the specified MiscellaneousApplications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->find($id);

        if (empty($miscellaneousApplications)) {
            Flash::error('Miscellaneous Applications not found');

            return redirect(route('miscellaneousApplications.index'));
        }

        return view('miscellaneous_applications.edit')->with('miscellaneousApplications', $miscellaneousApplications);
    }

    /**
     * Update the specified MiscellaneousApplications in storage.
     *
     * @param int $id
     * @param UpdateMiscellaneousApplicationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMiscellaneousApplicationsRequest $request)
    {
        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->find($id);

        if (empty($miscellaneousApplications)) {
            Flash::error('Miscellaneous Applications not found');

            return redirect(route('miscellaneousApplications.index'));
        }

        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->update($request->all(), $id);

        Flash::success('Miscellaneous Applications updated successfully.');

        return redirect(route('miscellaneousApplications.index'));
    }

    /**
     * Remove the specified MiscellaneousApplications from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->find($id);

        if (empty($miscellaneousApplications)) {
            Flash::error('Miscellaneous Applications not found');

            return redirect(route('miscellaneousApplications.index'));
        }

        $this->miscellaneousApplicationsRepository->delete($id);

        Flash::success('Miscellaneous Applications deleted successfully.');

        return redirect(route('miscellaneousApplications.index'));
    }

    public function serviceDropPurchasing(Request $request) {
        $searchParams = $request['searchParams'];

        if ($searchParams != null) {
            $miscellaneousApplications = DB::table('CRM_MiscellaneousApplications')
                ->leftJoin('CRM_Towns', 'CRM_MiscellaneousApplications.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_MiscellaneousApplications.Barangay', '=', 'CRM_Barangays.id')
                ->select('CRM_MiscellaneousApplications.id',
                    'CRM_MiscellaneousApplications.ConsumerName',
                    'CRM_MiscellaneousApplications.Sitio',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_MiscellaneousApplications.TotalAmount',
                    'CRM_MiscellaneousApplications.created_at',
                    'CRM_MiscellaneousApplications.ServiceDropLength',
                    'CRM_MiscellaneousApplications.TotalAmount',
                )
                ->whereRaw("CRM_MiscellaneousApplications.ConsumerName LIKE '%" . $searchParams . "%' AND Status NOT IN ('Trash')")
                ->orderByDesc('CRM_MiscellaneousApplications.created_at')
                ->paginate(25);
        } else {
            $miscellaneousApplications = DB::table('CRM_MiscellaneousApplications')
                ->leftJoin('CRM_Towns', 'CRM_MiscellaneousApplications.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_MiscellaneousApplications.Barangay', '=', 'CRM_Barangays.id')
                ->select('CRM_MiscellaneousApplications.id',
                    'CRM_MiscellaneousApplications.ConsumerName',
                    'CRM_MiscellaneousApplications.Sitio',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_MiscellaneousApplications.TotalAmount',
                    'CRM_MiscellaneousApplications.created_at',
                    'CRM_MiscellaneousApplications.ServiceDropLength',
                    'CRM_MiscellaneousApplications.TotalAmount',
                )
                ->whereRaw("Status NOT IN ('Trash')")
                ->orderByDesc('CRM_MiscellaneousApplications.created_at')
                ->paginate(25);
        }

        return view('/miscellaneous_applications/service_drop_purchasing', [
            'miscellaneousApplications' => $miscellaneousApplications,
        ]);
    }

    public function createServiceDropPurchasing(Request $request) {
        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        return view('/miscellaneous_applications/create_service_drop_request', [
            'towns' => $towns,
        ]);
    }

    public function storeServiceDropPurchase(CreateMiscellaneousApplicationsRequest $request) {
        $input = $request->all();

        $miscellaneousApplications = $this->miscellaneousApplicationsRepository->create($input);

        // SAVE Miscellaneous Payments
        $miscPayments = new MiscellaneousPayments;
        $miscPayments->id = IDGenerator::generateIDandRandString();
        $miscPayments->MiscellaneousId = $miscellaneousApplications->id;
        $miscPayments->GLCode = '23220905000';
        $miscPayments->Description = 'Cash - Service Drop Wire';
        $miscPayments->Unit = 'meters';
        $miscPayments->Quantity = $input['ServiceDropLength'];
        $miscPayments->PricePerQuantity = $input['PricePerQuantity'];
        $miscPayments->Amount = $input['TotalAmount'];
        $miscPayments->save();

        $miscellaneousApplications = DB::table('CRM_MiscellaneousApplications')
            ->leftJoin('CRM_Towns', 'CRM_MiscellaneousApplications.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_MiscellaneousApplications.Barangay', '=', 'CRM_Barangays.id')
            ->select('CRM_MiscellaneousApplications.id',
                'CRM_MiscellaneousApplications.ConsumerName',
                'CRM_MiscellaneousApplications.Sitio',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_MiscellaneousApplications.TotalAmount',
            )
            ->where('CRM_MiscellaneousApplications.id', $input['id'])
            ->first();

        $queueId = $input['id'] . '-SDW';
        $queue = new CRMQueue;
        $queue->id = $queueId;
        $queue->ConsumerName = $miscellaneousApplications->ConsumerName;
        $queue->ConsumerAddress = MiscellaneousApplications::getAddress($miscellaneousApplications);
        $queue->TransactionPurpose = 'Service Drop Wire Payment';
        $queue->SourceId = $miscellaneousApplications->id;
        $queue->SubTotal = floatval($input['ServiceDropLength']) * floatval($input['PricePerQuantity']);
        $queue->VAT = $input['VAT'];
        $queue->Total = $input['TotalAmount'];
        $queue->save();

        /**
         * CRM QUEUE DETAILS
         */
        $queuDetails = new CRMDetails;
        $queuDetails->id = IDGenerator::generateID() . "1";
        $queuDetails->ReferenceNo = $queueId;
        $queuDetails->Particular = $miscPayments->Description . ' - ' . $input['ServiceDropLength'] . ' mtrs';
        $queuDetails->GLCode = $miscPayments->GLCode;
        $queuDetails->Total = $queue->SubTotal;
        $queuDetails->save();

        $queuDetails = new CRMDetails;
        $queuDetails->id = IDGenerator::generateID() . "2";
        $queuDetails->ReferenceNo = $queueId;
        $queuDetails->Particular = 'EVAT';
        $queuDetails->GLCode = '22420414001';
        $queuDetails->Total = $queue->VAT;
        $queuDetails->save();

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID() . '1';
        $ticketLog->TicketId = $miscellaneousApplications->id;
        $ticketLog->Log = "Received";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateID() . '2';
        $ticketLog->TicketId = $miscellaneousApplications->id;
        $ticketLog->Log = "SDW Request Forwarded to Cashier";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        return redirect(route('miscellaneousApplications.service-drop-purchasing'));
    }

    public function serviceDropPurchasingView($id) {
        $miscellaneousApplication = DB::table('CRM_MiscellaneousApplications')
                ->leftJoin('CRM_Towns', 'CRM_MiscellaneousApplications.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_MiscellaneousApplications.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'CRM_MiscellaneousApplications.UserId', '=', 'users.id')
                ->select('CRM_MiscellaneousApplications.id',
                    'CRM_MiscellaneousApplications.ConsumerName',
                    'CRM_MiscellaneousApplications.Sitio',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_MiscellaneousApplications.Status',
                    'CRM_MiscellaneousApplications.created_at',
                    'CRM_MiscellaneousApplications.ServiceDropLength',
                    'CRM_MiscellaneousApplications.TotalAmount',
                    'users.name'
                )
                ->whereRaw("CRM_MiscellaneousApplications.id='" . $id . "'")
                ->first();

        $miscellaneousPayment = MiscellaneousPayments::where('MiscellaneousId', $id)->first();

        $logs = DB::table('CRM_TicketLogs')
            ->leftJoin('users', 'CRM_TicketLogs.UserId', '=', 'users.id')
            ->where('TicketId', $id)
            ->select('CRM_TicketLogs.*', 'users.name')
            ->orderByDesc('created_at')
            ->get();

        return view('/miscellaneous_applications/service_drop_purchasing_view', [
            'miscellaneousApplication' => $miscellaneousApplication,
            'miscellaneousPayment' => $miscellaneousPayment,
            'logs' => $logs,
        ]);
    }

    public function transformerTesting() {
        return view('/miscellaneous_applications/transformer_testing', [

        ]);
    }

    public function discoApplication() {
        return view('/miscellaneous_applications/disco_application', [

        ]);
    }
}
