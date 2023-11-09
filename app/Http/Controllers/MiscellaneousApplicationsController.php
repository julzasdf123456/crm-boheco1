<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMiscellaneousApplicationsRequest;
use App\Http\Requests\UpdateMiscellaneousApplicationsRequest;
use App\Repositories\MiscellaneousApplicationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Towns;
use App\Models\MiscellaneousPayments;
use App\Models\IDGenerator;
use App\Models\CRMQueue;
use App\Models\CRMDetails;
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
        return view('/miscellaneous_applications/service_drop_purchasing', [

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

        $miscellaneousApplications = 

        // SAVE Miscellaneous Payments
        $miscPayments = new MiscellaneousPayments;
        $miscPayments->id = IDGenerator::generateIDandRandString();
        $miscPayments->MiscellaneousId = $miscellaneousApplications->id;
        $miscPayments->GLCode = '27220905000';
        $miscPayments->Description = 'Service Drop Wire';
        $miscPayments->Unit = 'meters';
        $miscPayments->Quantity = $input['ServiceDropLength'];
        $miscPayments->PricePerQuantity = $input['PricePerQuantity'];
        $miscPayments->Amount = $input['TotalAmount'];
        $miscPayments->save();

        $queueId = $input['id'] . '-SDW';
        $queue = new CRMQueue;
        $queue->id = $queueId;
        $queue->ConsumerName = $miscellaneousApplications->ConsumerName;
        $queue->ConsumerAddress = ServiceConnections::getAddress($serviceConnection);
        $queue->TransactionPurpose = 'Service Connection Fees';
        $queue->SourceId = $scId;
        $queue->SubTotal = $totalTransactions->SubTotal;
        $queue->VAT = $totalTransactions->TotalVat;
        $queue->Total = $totalTransactions->Total;
        $queue->save();
    }
}
