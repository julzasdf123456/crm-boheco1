<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillsReadingsRequest;
use App\Http\Requests\UpdateBillsReadingsRequest;
use App\Repositories\BillsReadingsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillsReadingsController extends AppBaseController
{
    /** @var BillsReadingsRepository $billsReadingsRepository*/
    private $billsReadingsRepository;

    public function __construct(BillsReadingsRepository $billsReadingsRepo)
    {
        $this->middleware('auth');
        $this->billsReadingsRepository = $billsReadingsRepo;
    }

    /**
     * Display a listing of the BillsReadings.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billsReadings = $this->billsReadingsRepository->all();

        return view('bills_readings.index')
            ->with('billsReadings', $billsReadings);
    }

    /**
     * Show the form for creating a new BillsReadings.
     *
     * @return Response
     */
    public function create()
    {
        return view('bills_readings.create');
    }

    /**
     * Store a newly created BillsReadings in storage.
     *
     * @param CreateBillsReadingsRequest $request
     *
     * @return Response
     */
    public function store(CreateBillsReadingsRequest $request)
    {
        $input = $request->all();

        $billsReadings = $this->billsReadingsRepository->create($input);

        Flash::success('Bills Readings saved successfully.');

        return redirect(route('billsReadings.index'));
    }

    /**
     * Display the specified BillsReadings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billsReadings = $this->billsReadingsRepository->find($id);

        if (empty($billsReadings)) {
            Flash::error('Bills Readings not found');

            return redirect(route('billsReadings.index'));
        }

        return view('bills_readings.show')->with('billsReadings', $billsReadings);
    }

    /**
     * Show the form for editing the specified BillsReadings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billsReadings = $this->billsReadingsRepository->find($id);

        if (empty($billsReadings)) {
            Flash::error('Bills Readings not found');

            return redirect(route('billsReadings.index'));
        }

        return view('bills_readings.edit')->with('billsReadings', $billsReadings);
    }

    /**
     * Update the specified BillsReadings in storage.
     *
     * @param int $id
     * @param UpdateBillsReadingsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillsReadingsRequest $request)
    {
        $billsReadings = $this->billsReadingsRepository->find($id);

        if (empty($billsReadings)) {
            Flash::error('Bills Readings not found');

            return redirect(route('billsReadings.index'));
        }

        $billsReadings = $this->billsReadingsRepository->update($request->all(), $id);

        Flash::success('Bills Readings updated successfully.');

        return redirect(route('billsReadings.index'));
    }

    /**
     * Remove the specified BillsReadings from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billsReadings = $this->billsReadingsRepository->find($id);

        if (empty($billsReadings)) {
            Flash::error('Bills Readings not found');

            return redirect(route('billsReadings.index'));
        }

        $this->billsReadingsRepository->delete($id);

        Flash::success('Bills Readings deleted successfully.');

        return redirect(route('billsReadings.index'));
    }
}
