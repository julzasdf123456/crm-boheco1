<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillsRequest;
use App\Http\Requests\UpdateBillsRequest;
use App\Repositories\BillsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Bills;
use App\Models\BillsExtension;
use App\Models\UnbundledRates;
use App\Models\UnbundledRatesExtension;
use App\Models\PaidBills;
use App\Models\Meters;
use App\Models\AccountMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Flash;
use Response;

class BillsController extends AppBaseController
{
    /** @var  BillsRepository */
    private $billsRepository;

    public function __construct(BillsRepository $billsRepo)
    {
        $this->middleware('auth');
        $this->billsRepository = $billsRepo;
    }

    /**
     * Display a listing of the Bills.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $bills = $this->billsRepository->all();

        return view('bills.index')
            ->with('bills', $bills);
    }

    /**
     * Show the form for creating a new Bills.
     *
     * @return Response
     */
    public function create()
    {
        return view('bills.create');
    }

    /**
     * Store a newly created Bills in storage.
     *
     * @param CreateBillsRequest $request
     *
     * @return Response
     */
    public function store(CreateBillsRequest $request)
    {
        $input = $request->all();

        $bills = $this->billsRepository->create($input);

        Flash::success('Bills saved successfully.');

        return redirect(route('bills.index'));
    }

    /**
     * Display the specified Bills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bills = $this->billsRepository->find($id);

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        return view('bills.show')->with('bills', $bills);
    }

    /**
     * Show the form for editing the specified Bills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bills = $this->billsRepository->find($id);

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        return view('bills.edit')->with('bills', $bills);
    }

    /**
     * Update the specified Bills in storage.
     *
     * @param int $id
     * @param UpdateBillsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillsRequest $request)
    {
        $bills = $this->billsRepository->find($id);

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        $bills = $this->billsRepository->update($request->all(), $id);

        Flash::success('Bills updated successfully.');

        return redirect(route('bills.index'));
    }

    /**
     * Remove the specified Bills from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bills = $this->billsRepository->find($id);

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        $this->billsRepository->delete($id);

        Flash::success('Bills deleted successfully.');

        return redirect(route('bills.index'));
    }

    public function showBill($accountNumber, $period) {
        // $billDetails = DB::connection('sqlsrvbilling')
        //     ->select("EXEC sp_SOA @ServicePeriodEnd='" . date('Y-m-d', strtotime($period)) . "', @WhereClause='Bi.AccountNumber=''" . $accountNumber . "'''");
        // dd($billDetails);
        $bills = Bills::where('AccountNumber', $accountNumber)
            ->where('ServicePeriodEnd', $period)
            ->first();
        $billsExtension = BillsExtension::where('AccountNumber', $accountNumber)
            ->where('ServicePeriodEnd', $period)
            ->first();
        $paidBill = PaidBills::where('AccountNumber', $accountNumber)
            ->where('ServicePeriodEnd', $period)
            ->first();
        $account = AccountMaster::where('AccountNumber', $accountNumber)->first();
        $rate = UnbundledRates::where('ServicePeriodEnd', $period)
            ->where('ConsumerType', $bills->ConsumerType)
            ->first();
        $rateExtension = UnbundledRatesExtension::where('ServicePeriodEnd', $period)
            ->where('ConsumerType', $bills->ConsumerType)
            ->first();
        $meter = Meters::where('MeterNumber', $account->MeterNumber)->first();

        return view('/bills/show', [
            'bills' => $bills,
            'billsExtension' => $billsExtension,
            'paidBill' => $paidBill,
            'account' => $account,
            'rate' => $rate,
            'rateExtension' => $rateExtension,
            'meter' => $meter,
        ]);
        
    }
}
