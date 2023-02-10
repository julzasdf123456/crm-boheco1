<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaidBillsRequest;
use App\Http\Requests\UpdatePaidBillsRequest;
use App\Repositories\PaidBillsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PaidBillsController extends AppBaseController
{
    /** @var  PaidBillsRepository */
    private $paidBillsRepository;

    public function __construct(PaidBillsRepository $paidBillsRepo)
    {
        $this->middleware('auth');
        $this->paidBillsRepository = $paidBillsRepo;
    }

    /**
     * Display a listing of the PaidBills.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $paidBills = $this->paidBillsRepository->all();

        return view('paid_bills.index')
            ->with('paidBills', $paidBills);
    }

    /**
     * Show the form for creating a new PaidBills.
     *
     * @return Response
     */
    public function create()
    {
        return view('paid_bills.create');
    }

    /**
     * Store a newly created PaidBills in storage.
     *
     * @param CreatePaidBillsRequest $request
     *
     * @return Response
     */
    public function store(CreatePaidBillsRequest $request)
    {
        $input = $request->all();

        $paidBills = $this->paidBillsRepository->create($input);

        Flash::success('Paid Bills saved successfully.');

        return redirect(route('paidBills.index'));
    }

    /**
     * Display the specified PaidBills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        return view('paid_bills.show')->with('paidBills', $paidBills);
    }

    /**
     * Show the form for editing the specified PaidBills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        return view('paid_bills.edit')->with('paidBills', $paidBills);
    }

    /**
     * Update the specified PaidBills in storage.
     *
     * @param int $id
     * @param UpdatePaidBillsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaidBillsRequest $request)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        $paidBills = $this->paidBillsRepository->update($request->all(), $id);

        Flash::success('Paid Bills updated successfully.');

        return redirect(route('paidBills.index'));
    }

    /**
     * Remove the specified PaidBills from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        $this->paidBillsRepository->delete($id);

        Flash::success('Paid Bills deleted successfully.');

        return redirect(route('paidBills.index'));
    }
}
