<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionPayTransactionRequest;
use App\Http\Requests\UpdateServiceConnectionPayTransactionRequest;
use App\Repositories\ServiceConnectionPayTransactionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnectionMatPayables;
use App\Models\ServiceConnectionPayParticulars;
use App\Models\ServiceConnectionMatPayments;
use App\Models\ServiceConnections;
use App\Models\Electricians;
use App\Models\IDGenerator;
use App\Models\BillDeposits;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\ServiceConnectionTimeframes;
use App\Models\SMSNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class ServiceConnectionPayTransactionController extends AppBaseController
{
    /** @var  ServiceConnectionPayTransactionRepository */
    private $serviceConnectionPayTransactionRepository;

    public function __construct(ServiceConnectionPayTransactionRepository $serviceConnectionPayTransactionRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionPayTransactionRepository = $serviceConnectionPayTransactionRepo;
    }

    /**
     * Display a listing of the ServiceConnectionPayTransaction.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionPayTransactions = $this->serviceConnectionPayTransactionRepository->all();

        return view('service_connection_pay_transactions.index')
            ->with('serviceConnectionPayTransactions', $serviceConnectionPayTransactions);
    }

    /**
     * Show the form for creating a new ServiceConnectionPayTransaction.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_pay_transactions.create');
    }

    /**
     * Store a newly created ServiceConnectionPayTransaction in storage.
     *
     * @param CreateServiceConnectionPayTransactionRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionPayTransactionRequest $request)
    {
        $input = $request->all();

        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->create($input);

        Flash::success('Service Connection Pay Transaction saved successfully.');

        // return redirect(route('serviceConnectionPayTransactions.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$request['ServiceConnectionId']]);
    }

    /**
     * Display the specified ServiceConnectionPayTransaction.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            Flash::error('Service Connection Pay Transaction not found');

            return redirect(route('serviceConnectionPayTransactions.index'));
        }

        return view('service_connection_pay_transactions.show')->with('serviceConnectionPayTransaction', $serviceConnectionPayTransaction);
    }

    /**
     * Show the form for editing the specified ServiceConnectionPayTransaction.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            Flash::error('Service Connection Pay Transaction not found');

            return redirect(route('serviceConnectionPayTransactions.index'));
        }

        return view('service_connection_pay_transactions.edit')->with('serviceConnectionPayTransaction', $serviceConnectionPayTransaction);
    }

    /**
     * Update the specified ServiceConnectionPayTransaction in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionPayTransactionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionPayTransactionRequest $request)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            Flash::error('Service Connection Pay Transaction not found');

            return redirect(route('serviceConnectionPayTransactions.index'));
        }

        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->update($request->all(), $id);

        Flash::success('Service Connection Pay Transaction updated successfully.');

        return redirect(route('serviceConnectionPayTransactions.index'));
    }

    /**
     * Remove the specified ServiceConnectionPayTransaction from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            // Flash::error('Service Connection Pay Transaction not found');

            // return redirect(route('serviceConnectionPayTransactions.index'));
        }

        $this->serviceConnectionPayTransactionRepository->delete($id);

        // Flash::success('Service Connection Pay Transaction deleted successfully.');

        // return redirect(route('serviceConnectionPayTransactions.index'));
        return json_encode([
            'result' => 'ok',
        ]);
    }

    public function createStepFour($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.LoadCategory',
                    'CRM_ServiceConnections.Phase',
                    'CRM_ServiceConnections.Indigent',
                    'CRM_ServiceConnections.AccountType',
                    'CRM_ServiceConnections.ElectricianId',
                    'CRM_ServiceConnections.ElectricianName',
                    'CRM_ServiceConnections.ElectricianAddress',
                    'CRM_ServiceConnections.ElectricianContactNo',
                    'CRM_ServiceConnections.ElectricianAcredited',
                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountTypeName',
                    'CRM_ServiceConnectionAccountTypes.Alias',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $particulars = ServiceConnectionPayParticulars::all();

        $particularPayments = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
                    ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
                    ->select('CRM_ServiceConnectionParticularPaymentsTransactions.id',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Vat',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Total',
                            'CRM_ServiceConnectionPaymentParticulars.Particular')
                    ->where('CRM_ServiceConnectionParticularPaymentsTransactions.ServiceConnectionId', $scId)
                    ->get();

        $totalPayments = ServiceConnectionTotalPayments::where('ServiceConnectionId', $scId)->first();

        $electricians = Electricians::orderBy('Name')->get();

        $laborPayables = DB::table('CRM_ServiceConnectionMaterialPayables')
            ->where('BuildingType', $serviceConnection->BuildingType)
            ->select('CRM_ServiceConnectionMaterialPayables.*',
                DB::raw("(SELECT TOP 1 Quantity FROM CRM_ServiceConnectionMaterialPayments WHERE Material=CRM_ServiceConnectionMaterialPayables.id AND ServiceConnectionId='" . $serviceConnection->id . "') AS Qty"),
                DB::raw("(SELECT TOP 1 Vat FROM CRM_ServiceConnectionMaterialPayments WHERE Material=CRM_ServiceConnectionMaterialPayables.id AND ServiceConnectionId='" . $serviceConnection->id . "') AS Vat"),
                DB::raw("(SELECT TOP 1 BOHECOIShare FROM CRM_ServiceConnectionMaterialPayments WHERE Material=CRM_ServiceConnectionMaterialPayables.id AND ServiceConnectionId='" . $serviceConnection->id . "') AS BOHECOIShare"),
                DB::raw("(SELECT TOP 1 Total FROM CRM_ServiceConnectionMaterialPayments WHERE Material=CRM_ServiceConnectionMaterialPayables.id AND ServiceConnectionId='" . $serviceConnection->id . "') AS Total")      
            )
            ->orderBy('Material')
            ->get();

        $billDeposit = BillDeposits::where('ServiceConnectionId', $serviceConnection->id)
            ->first();

        return view('service_connection_pay_transactions\create_step_four', [
            'serviceConnection' => $serviceConnection, 
            'electricians' => $electricians,
            'totalPayments' => $totalPayments,
            'laborPayables' => $laborPayables,
            'billDeposit' => $billDeposit,
            'particulars' => $particulars,
            'particularPayments' => $particularPayments,
        ]);
    }

    public function saveWiringLabor(Request $request) {
        $scId = $request['id'];
        $materialId = $request['MaterialId'];
        $qty = $request['Quantity'];
        $vat = $request['VAT'];
        $bohecoShare = $request['BOHECOIShare'];
        $total = $request['Total'];

        // DELETE PREV RECORD
        ServiceConnectionMatPayments::where('ServiceConnectionId', $scId)->where('Material', $materialId)->delete();

        // SAVE
        $payment = new ServiceConnectionMatPayments;
        $payment->id = IDGenerator::generateIDandRandString();
        $payment->ServiceConnectionId = $scId;
        $payment->Material = $materialId;
        $payment->Quantity = $qty;
        $payment->Vat = $vat;
        $payment->BOHECOIShare = $bohecoShare;
        $payment->Total = $total;
        $payment->save();

        return response()->json('ok', 200);
    }

    public function saveBillDeposits(Request $request) {
        $scId = $request['id'];

        BillDeposits::where('ServiceConnectionId', $scId)->delete();

        $bd = new BillDeposits;
        $bd->id = IDGenerator::generateIDandRandString();
        $bd->ServiceConnectionId = $scId;
        $bd->Load = $request['Load'];
        $bd->PowerFactor = $request['PowerFactor'];
        $bd->DemandFactor = $request['DemandFactor'];
        $bd->Hours = $request['Hours'];
        $bd->AverageRate = $request['AverageRate'];
        $bd->AverageTransmission = $request['AverageTransmission'];
        $bd->AverageDemand = $request['AverageDemand'];
        $bd->BillDepositAmount = $request['BillDepositAmount'];
        $bd->save();

        return response()->json('ok', 200);
    }

    public function saveServiceConnectionTransaction(Request $request) {
        $scId = $request['id'];

        ServiceConnectionTotalPayments::where('ServiceConnectionId', $scId)->delete();

        $total = new ServiceConnectionTotalPayments;
        $total->id = IDGenerator::generateIDandRandString();
        $total->ServiceConnectionId = $scId;
        $total->SubTotal = $request['SubTotal'];
        $total->Form2307TwoPercent = $request['Form2307TwoPercent'];
        $total->Form2307FivePercent = $request['Form2307FivePercent'];
        $total->TotalVat = $request['TotalVat'];
        $total->Total = $request['Total'];
        $total->ServiceConnectionFee = $request['ServiceConnectionFee'];
        $total->BillDeposit = $request['BillDeposit'];
        $total->WitholdableVat = $request['WitholdableVat'];
        $total->BOHECOShare = $request['BOHECOShare'];
        $total->LaborCharge = $request['LaborCharge'];
        $total->Particulars = $request['Particulars'];
        $total->save();

        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $scId;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Payment Updated!';
        $timeFrame->Notes = 'Payment updated with and amount of ' . number_format($request['Total'], 2);
        $timeFrame->save();

        $serviceConnection = ServiceConnection::find($id);

        if ($serviceConnection != null) {
            // SEND SMS
            if ($serviceConnection->ContactNumber != null) {
                $msg = "MR./MS. " . $serviceConnection->ServiceAccountName . ", \n\nHere are your BOHECO I Service Connection Fees:\nElectrician's Labor Charge - P" . number_format($total->LaborCharge, 2) . "\n" .
                    "Bill Deposit - P" . number_format($total->BillDeposit, 2) . "\n " .
                    "Others (Meter, etc.) - P" . number_format($total->Particulars, 2) . "\n " .
                    "Withholdable - (P" . number_format(round($total->Form2307TwoPercent, 2) + round($total->Form2307FivePercent, 2), 2) . ")\n " .
                    "VAT - (P" . number_format($total->TotalVat, 2) . ")\n " .
                    "OVERALL TOTAL - (P" . number_format($total->Total, 2) . ")\n\n " .
                    "NOTE that you can only pay this once BOHECO I has completed the installation inspection process. \n\nHave a gret day!";
                SMSNotifications::createFreshSms($serviceConnection->ContactNumber, $msg, 'SERVICE CONNECTIONS', $serviceConnection->id);
            }  
        }

        return response()->json('ok', 200);
    }
}
