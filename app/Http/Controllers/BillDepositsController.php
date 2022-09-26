<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillDepositsRequest;
use App\Http\Requests\UpdateBillDepositsRequest;
use App\Repositories\BillDepositsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillDepositsController extends AppBaseController
{
    /** @var  BillDepositsRepository */
    private $billDepositsRepository;

    public function __construct(BillDepositsRepository $billDepositsRepo)
    {
        $this->middleware('auth');
        $this->billDepositsRepository = $billDepositsRepo;
    }

    /**
     * Display a listing of the BillDeposits.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billDeposits = $this->billDepositsRepository->all();

        return view('bill_deposits.index')
            ->with('billDeposits', $billDeposits);
    }

    /**
     * Show the form for creating a new BillDeposits.
     *
     * @return Response
     */
    public function create()
    {
        return view('bill_deposits.create');
    }

    /**
     * Store a newly created BillDeposits in storage.
     *
     * @param CreateBillDepositsRequest $request
     *
     * @return Response
     */
    public function store(CreateBillDepositsRequest $request)
    {
        $input = $request->all();

        $billDeposits = $this->billDepositsRepository->create($input);

        Flash::success('Bill Deposits saved successfully.');

        return redirect(route('billDeposits.index'));
    }

    /**
     * Display the specified BillDeposits.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billDeposits = $this->billDepositsRepository->find($id);

        if (empty($billDeposits)) {
            Flash::error('Bill Deposits not found');

            return redirect(route('billDeposits.index'));
        }

        return view('bill_deposits.show')->with('billDeposits', $billDeposits);
    }

    /**
     * Show the form for editing the specified BillDeposits.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billDeposits = $this->billDepositsRepository->find($id);

        if (empty($billDeposits)) {
            Flash::error('Bill Deposits not found');

            return redirect(route('billDeposits.index'));
        }

        return view('bill_deposits.edit')->with('billDeposits', $billDeposits);
    }

    /**
     * Update the specified BillDeposits in storage.
     *
     * @param int $id
     * @param UpdateBillDepositsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillDepositsRequest $request)
    {
        $billDeposits = $this->billDepositsRepository->find($id);

        if (empty($billDeposits)) {
            Flash::error('Bill Deposits not found');

            return redirect(route('billDeposits.index'));
        }

        $billDeposits = $this->billDepositsRepository->update($request->all(), $id);

        Flash::success('Bill Deposits updated successfully.');

        return redirect(route('billDeposits.index'));
    }

    /**
     * Remove the specified BillDeposits from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billDeposits = $this->billDepositsRepository->find($id);

        if (empty($billDeposits)) {
            Flash::error('Bill Deposits not found');

            return redirect(route('billDeposits.index'));
        }

        $this->billDepositsRepository->delete($id);

        Flash::success('Bill Deposits deleted successfully.');

        return redirect(route('billDeposits.index'));
    }
}
