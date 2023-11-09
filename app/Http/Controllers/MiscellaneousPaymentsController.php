<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMiscellaneousPaymentsRequest;
use App\Http\Requests\UpdateMiscellaneousPaymentsRequest;
use App\Repositories\MiscellaneousPaymentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MiscellaneousPaymentsController extends AppBaseController
{
    /** @var MiscellaneousPaymentsRepository $miscellaneousPaymentsRepository*/
    private $miscellaneousPaymentsRepository;

    public function __construct(MiscellaneousPaymentsRepository $miscellaneousPaymentsRepo)
    {
        $this->middleware('auth');
        $this->miscellaneousPaymentsRepository = $miscellaneousPaymentsRepo;
    }

    /**
     * Display a listing of the MiscellaneousPayments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->all();

        return view('miscellaneous_payments.index')
            ->with('miscellaneousPayments', $miscellaneousPayments);
    }

    /**
     * Show the form for creating a new MiscellaneousPayments.
     *
     * @return Response
     */
    public function create()
    {
        return view('miscellaneous_payments.create');
    }

    /**
     * Store a newly created MiscellaneousPayments in storage.
     *
     * @param CreateMiscellaneousPaymentsRequest $request
     *
     * @return Response
     */
    public function store(CreateMiscellaneousPaymentsRequest $request)
    {
        $input = $request->all();

        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->create($input);

        Flash::success('Miscellaneous Payments saved successfully.');

        return redirect(route('miscellaneousPayments.index'));
    }

    /**
     * Display the specified MiscellaneousPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->find($id);

        if (empty($miscellaneousPayments)) {
            Flash::error('Miscellaneous Payments not found');

            return redirect(route('miscellaneousPayments.index'));
        }

        return view('miscellaneous_payments.show')->with('miscellaneousPayments', $miscellaneousPayments);
    }

    /**
     * Show the form for editing the specified MiscellaneousPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->find($id);

        if (empty($miscellaneousPayments)) {
            Flash::error('Miscellaneous Payments not found');

            return redirect(route('miscellaneousPayments.index'));
        }

        return view('miscellaneous_payments.edit')->with('miscellaneousPayments', $miscellaneousPayments);
    }

    /**
     * Update the specified MiscellaneousPayments in storage.
     *
     * @param int $id
     * @param UpdateMiscellaneousPaymentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMiscellaneousPaymentsRequest $request)
    {
        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->find($id);

        if (empty($miscellaneousPayments)) {
            Flash::error('Miscellaneous Payments not found');

            return redirect(route('miscellaneousPayments.index'));
        }

        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->update($request->all(), $id);

        Flash::success('Miscellaneous Payments updated successfully.');

        return redirect(route('miscellaneousPayments.index'));
    }

    /**
     * Remove the specified MiscellaneousPayments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $miscellaneousPayments = $this->miscellaneousPaymentsRepository->find($id);

        if (empty($miscellaneousPayments)) {
            Flash::error('Miscellaneous Payments not found');

            return redirect(route('miscellaneousPayments.index'));
        }

        $this->miscellaneousPaymentsRepository->delete($id);

        Flash::success('Miscellaneous Payments deleted successfully.');

        return redirect(route('miscellaneousPayments.index'));
    }
}
