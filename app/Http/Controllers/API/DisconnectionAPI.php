<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DisconnectionSchedules;
use App\Models\IDGenerator;
use App\Models\AccountMaster;
use App\Models\DisconnectionRoutes;
use App\Models\DisconnectionData;
use App\Models\Bills;
use App\Models\PaidBills;
use App\Http\Requests\CreateReadingsRequest;

class DisconnectionAPI extends Controller {
    public $successStatus = 200;

    public function getDisconnectionListSchedule(Request $request) {
        $userid = $request['UserId'];

        $schedules = DisconnectionSchedules::whereRaw("DisconnectorId='" . $userid . "' AND Status IS NULL")
            ->get();

        return response()->json($schedules, 200);
    }

    public function getDisconnectionList(Request $request) {
        set_time_limit(360);
        $id = $request['id'];
     
        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $id . "'")
            ->orderBy('Route')
            ->get();

        $schedule = DisconnectionSchedules::find($id);

        $i=1;
        $query = "";
        foreach($routes as $item) {
            $townCode = substr($item->Route, 0, 2);

            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                if ($i < count($routes)) {
                    $query .= " (AccountMaster.Route='" . $item->Route . "') OR ";
                } else {
                    $query .= " (AccountMaster.Route='" . $item->Route . "') ";
                }                
            } else {
                $acctFrom = $townCode . $item->Route . sprintf("%04d", $item->SequenceFrom);
                $acctTo = $townCode . $item->Route . sprintf("%04d", $item->SequenceTo);

                if ($i < count($routes)) {
                    $query .= " (AccountMaster.Route='" . $item->Route . "' AND (AccountMaster.AccountNumber BETWEEN '" . $acctFrom . "' AND '" . $acctTo . "') ) OR ";
                } else {
                    $query .= " (AccountMaster.Route='" . $item->Route . "' AND (AccountMaster.AccountNumber BETWEEN '" . $acctFrom . "' AND '" . $acctTo . "') ) ";
                }
            } 
            $i++;
        }

        if (strlen($query) > 0) {
            $query = " AND (" . $query . ")";
        } else {
            $query = "";
        }

        $data = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->leftJoin('BillsExtension', function($join) {
                        $join->on('Bills.AccountNumber', '=', 'BillsExtension.AccountNumber')
                            ->on('Bills.ServicePeriodEnd', '=', 'BillsExtension.ServicePeriodEnd');
                    })
                    ->whereRaw("Bills.ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' " . $query . " AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        DB::raw("NEWID() AS id"),
                        DB::raw("'" . $id .  "' AS ScheduleId"),
                        DB::raw("'" . $schedule->DisconnectorName .  "' AS DisconnectorName"),
                        DB::raw("'" . $schedule->DisconnectorId .  "' AS UserId"),
                        'Bills.AccountNumber',
                        'Bills.ServicePeriodEnd',
                        'AccountMaster.Item1 AS AccountCoordinates',
                        'ConsumerName',
                        'ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'NetAmount',
                        'AccountMaster.Pole AS PoleNumber',

                        'Bills.DAA_GRAM',
                        'Bills.DAA_ICERA',
                        'Bills.ACRM_TAFPPCA',
                        'Bills.ACRM_TAFxA',
                        'Bills.DAA_VAT',
                        'Bills.ACRM_VAT',
                        'Bills.NetPresReading',
                        'Bills.NetPowerKWH',
                        'Bills.NetGenerationAmount',
                        'Bills.CreditKWH',
                        'Bills.CreditAmount',
                        'Bills.NetMeteringSystemAmt',
                        'Bills.Item3',
                        'Bills.Item4',
                        'Bills.SeniorCitizenDiscount',
                        'Bills.SeniorCitizenSubsidy',
                        'Bills.UCMERefund',
                        'Bills.NetPrevReading',
                        'Bills.CrossSubsidyCreditAmt',
                        'Bills.MissionaryElectrificationAmt',
                        'Bills.EnvironmentalAmt',
                        'Bills.LifelineSubsidyAmt',
                        'Bills.Item1',
                        'Bills.Item2',
                        'Bills.DistributionSystemAmt',
                        'Bills.SupplyRetailCustomerAmt',
                        'Bills.SupplySystemAmt',
                        'Bills.MeteringRetailCustomerAmt',
                        'Bills.MeteringSystemAmt',
                        'Bills.SystemLossAmt',
                        'Bills.FBHCAmt',
                        'Bills.FPCAAdjustmentAmt',
                        'Bills.ForexAdjustmentAmt',
                        'Bills.TransmissionDemandAmt',
                        'Bills.TransmissionSystemAmt',
                        'Bills.DistributionDemandAmt',
                        'Bills.EPAmount',
                        'Bills.PCAmount',
                        'Bills.LoanCondonation',
                        'Bills.BillingPeriod',
                        'Bills.UnbundledTag',
                        'Bills.GenerationSystemAmt',
                        'Bills.PPCAAmount',
                        'Bills.UCAmount',
                        'Bills.MeterNumber',
                        'Bills.ConsumerType',
                        'Bills.BillType',
                        'Bills.QCAmount',
                        'Bills.PPA',
                        'Bills.PPAAmount',
                        'Bills.BasicAmount',
                        'Bills.PRADiscount',
                        'Bills.PRAAmount',
                        'Bills.PPCADiscount',
                        'Bills.AverageKWDemand',
                        'Bills.CoreLoss',
                        'Bills.Meter',
                        'Bills.PR',
                        'Bills.SDW',
                        'Bills.Others',
                        'Bills.ServiceDateFrom',
                        'Bills.ServiceDateTo',
                        'Bills.DueDate',
                        'Bills.BillNumber',
                        'Bills.Remarks',
                        'Bills.AverageKWH',
                        'Bills.Charges',
                        'Bills.Deductions',
                        'Bills.NetAmount',
                        'Bills.PowerRate',
                        'Bills.DemandRate',
                        'Bills.BillingDate',
                        'Bills.AdditionalKWH',
                        'Bills.AdditionalKWDemand',
                        'Bills.PowerKWH',
                        'Bills.KWHAmount',
                        'Bills.DemandKW',
                        'Bills.KWAmount',
                        'BillsExtension.GenerationVAT',
                        'BillsExtension.TransmissionVAT',
                        'BillsExtension.SLVAT',
                        'BillsExtension.DistributionVAT',
                        'BillsExtension.OthersVAT',
                        'BillsExtension.Item5',
                        'BillsExtension.Item6',
                        'BillsExtension.Item7',
                        'BillsExtension.Item8',
                        'BillsExtension.Item9',
                        'BillsExtension.Item10',
                        'BillsExtension.Item11',
                        'BillsExtension.Item12',
                        'BillsExtension.Item13',
                        'BillsExtension.Item14',
                        'BillsExtension.Item15',
                        'BillsExtension.Item16',
                        'BillsExtension.Item17',
                        'BillsExtension.Item18',
                        'BillsExtension.Item19',
                        'BillsExtension.Item20',
                        'BillsExtension.Item21',
                        'BillsExtension.Item22',
                        'BillsExtension.Item23',
                        'BillsExtension.Item24',
                    )
                    ->orderBy('Bills.AccountNumber')
                    ->get();

        $finalData = [];
        foreach ($data as $item) {
            array_push($finalData, [
                'id' => $item->id,
                'ScheduleId' => $item->ScheduleId,
                'DisconnectorName' => $item->DisconnectorName,
                'UserId' => $item->UserId,
                'AccountNumber' => $item->AccountNumber,
                'ServicePeriodEnd' => $item->ServicePeriodEnd,
                'AccountCoordinates' => $item->AccountCoordinates,
                'ConsumerName' => $item->ConsumerName,
                'ConsumerAddress' => $item->ConsumerAddress,
                'MeterNumber' => $item->MeterNumber,
                'NetAmount' => $item->NetAmount,
                'PoleNumber' => $item->PoleNumber,
                'Surcharge' => Bills::getSurcharge($item),
            ]);

            // $discoData = new DisconnectionData;
            // $discoData->id = $item->id;
            // $discoData->ScheduleId = $item->ScheduleId;
            // $discoData->DisconnectorName = $item->DisconnectorName;
            // $discoData->UserId = $item->UserId;
            // $discoData->AccountNumber = $item->AccountNumber;
            // $discoData->ServicePeriodEnd = $item->ServicePeriodEnd;
            // $discoData->AccountCoordinates = $item->AccountCoordinates;
            // $discoData->ConsumerName = $item->ConsumerName;
            // $discoData->ConsumerAddress = $item->ConsumerAddress;
            // $discoData->MeterNumber = $item->MeterNumber;
            // $discoData->NetAmount = $item->NetAmount;
            // $discoData->PoleNumber = $item->PoleNumber;
            // $discoData->save();
        }

        return response()->json($finalData, 200);
    }

    public function updateDownloadedSched(Request $request) {
        $id = $request['id'];
        $phoneModel = $request['PhoneModel'];

        $schedule = DisconnectionSchedules::find($id);

        if ($schedule != null) {
            $schedule->DatetimeDownloaded = date('Y-m-d H:i:s');
            $schedule->Status = 'Downloaded';
            $schedule->PhoneModel = $phoneModel;
            $schedule->save();
        }

        return response()->json($schedule, 200);
    }

    public function receiveDisconnectionUploads(Request $request) {
        // UPDATE ACCOUNT
        $account = AccountMaster::where('AccountNumber', $request['AccountNumber'])->first();

        $discoData = DisconnectionData::find($request['id']);

        if ($discoData != null) {            
            $discoData->Latitude = $request['Latitude'];
            $discoData->Longitude = $request['Longitude'];
            $discoData->Notes = $request['Notes'];
            $discoData->NetAmount = $request['NetAmount'];
            $discoData->Surcharge = $request['Surcharge'];
            $discoData->ServiceFee = $request['ServiceFee'];
            $discoData->Others = $request['Others'];
            $discoData->Status = $request['Status'];
            $discoData->PaidAmount = $request['PaidAmount'];
            $discoData->DisconnectionDate = $request['DisconnectionDate'];
            $discoData->LastReading = $request['LastReading'];
        } else {
            $discoData = new DisconnectionData;
            $discoData->id = $request['id'];
            $discoData->ScheduleId = $request['ScheduleId'];
            $discoData->DisconnectorName = $request['DisconnectorName'];
            $discoData->UserId = $request['UserId'];
            $discoData->AccountNumber = $request['AccountNumber'];
            $discoData->ServicePeriodEnd = $request['ServicePeriodEnd'];
            $discoData->AccountCoordinates = $request['AccountCoordinates'];
            $discoData->Latitude = $request['Latitude'];
            $discoData->Longitude = $request['Longitude'];
            $discoData->Notes = $request['Notes'];
            $discoData->NetAmount = $request['NetAmount'];
            $discoData->Surcharge = $request['Surcharge'];
            $discoData->ServiceFee = $request['ServiceFee'];
            $discoData->Others = $request['Others'];
            $discoData->Status = $request['Status'];
            $discoData->PaidAmount = $request['PaidAmount'];
            $discoData->ConsumerName = $request['ConsumerName'];
            $discoData->ConsumerAddress = $request['ConsumerAddress'];
            $discoData->MeterNumber = $request['MeterNumber'];
            $discoData->PoleNumber = $request['PoleNumber'];
            $discoData->DisconnectionDate = $request['DisconnectionDate'];
            $discoData->LastReading = $request['LastReading'];
        }        

        if ($account != null) {
            if ($request['Status'] == 'Disconnected') {
                $account->AccountStatus = 'DISCO';
                $account->save();
            } elseif ($request['Status'] == 'Paid') {
                // INSERT TO PAIDBILLS
                $paidBills = PaidBills::where('AccountNumber', $request['AccountNumber'])
                    ->where('ServicePeriodEnd', $request['ServicePeriodEnd'])
                    ->first();

                if ($paidBills != null) {
                    // MARK AS DOUBLE PAYMENT
                    $discoData->PaymentNotes = 'DOUBLE PAYMENT';
                } else {
                    // CREATE NEW PAYMENT
                    if (floatval($request['Surcharge']) > 0) {
                        $sVat = floatval($request['Surcharge']) - (floatval($request['Surcharge']) / 1.12);
                        $surcharge = (floatval($request['Surcharge']) / 1.12);
                    } else {
                        $sVat = 0;
                        $surcharge = 0;
                    }

                    $bill = Bills::where('AccountNumber', $request['AccountNumber'])
                        ->where('ServicePeriodEnd', $request['ServicePeriodEnd'])
                        ->first();

                    $paidBill = new PaidBills;
                    $paidBill->AccountNumber = $request['AccountNumber'];
                    $paidBill->BillNumber = $bill->BillNumber;
                    $paidBill->ServicePeriodEnd = $request['ServicePeriodEnd'];
                    $paidBill->Power = $bill->KWHAmount;
                    $paidBill->Meter = round(floatval($bill->Item2) + $sVat, 2);
                    $paidBill->PR = $bill->PR;
                    $paidBill->Others = $bill->Others;
                    $paidBill->NetAmount = $request['PaidAmount'];
                    $paidBill->PaymentType = 'SUB-OFFICE/STATION';
                    $paidBill->ORNumber = null;
                    $paidBill->Teller = $request['DisconnectorName'];
                    $paidBill->DCRNumber = "";
                    $paidBill->PostingDate = $request['DisconnectionDate'];
                    $paidBill->PostingSequence = '1';
                    $paidBill->PromptPayment = '0';
                    $paidBill->Surcharge = round($surcharge, 2);
                    $paidBill->save();
                }
            }
        }

        $discoData->save();

        return response()->json($discoData, $this->successStatus);
    }
}