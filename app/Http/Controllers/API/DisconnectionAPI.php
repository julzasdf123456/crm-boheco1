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
use App\Models\Bills;
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
        $id = $request['id'];
     
        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $id . "'")
            ->orderBy('Route')
            ->get();

        $schedule = DisconnectionSchedules::find($id);

        $data = [];
        foreach($routes as $item) {
            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->leftJoin('BillsExtension', function($join) {
                        $join->on('Bills.AccountNumber', '=', 'BillsExtension.AccountNumber')
                            ->on('Bills.ServicePeriodEnd', '=', 'BillsExtension.ServicePeriodEnd');
                    })
                    ->whereRaw("Bills.ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
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
            } else {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->leftJoin('BillsExtension', function($join) {
                        $join->on('Bills.AccountNumber', '=', 'BillsExtension.AccountNumber')
                            ->on('Bills.ServicePeriodEnd', '=', 'BillsExtension.ServicePeriodEnd');
                    })
                    ->whereRaw("Bills.ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') 
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
            }  
            
            $data = array_merge($data, $count->toArray());
        }

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
        $account = ServiceAccounts::find($request['AccountNumber']);

        if ($account != null) {
            $account->AccountStatus = 'DISCONNECTED';
            $account->DateDisconnected = $request['DateDisconnected'];
            $account->save();
        }

        // CREATE DISCONNECTION HISTORY
        $discoHist = new DisconnectionHistory;
        $discoHist->id = IDGenerator::generateIDandRandString();
        $discoHist->AccountNumber = $request['AccountNumber'];
        $discoHist->ServicePeriod = $request['ServicePeriod'];
        $discoHist->Latitude = $request['LatitudeCaptured'];
        $discoHist->Longitude = $request['LongitudeCaptured'];
        $discoHist->Status = 'DISCONNECTED';
        $discoHist->UserId = $request['UserId'];
        $discoHist->DateDisconnected = $request['DateDisconnected'];
        $discoHist->TimeDisconnected = $request['TimeDisconnected'];
        $discoHist->BillId = $request['LastReading'];
        $discoHist->save();

        // UPDATE TICKETS
        $ticket = Tickets::find($request['TicketId']);

        if ($ticket != null) {
            $ticket->DateTimeLinemanArrived = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
            $ticket->DateTimeLinemanExecuted = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
            $ticket->Status = 'Executed';
            // ASSIGN CREW LATER
            $ticket->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateIDandRandString();
            $ticketLog->TicketId = $ticket->id;
            $ticketLog->Log = "Disconnected and Uploaded";
            $ticketLog->LogDetails = "Ticket automatically updated via Disconnection App Upload Module";
            $ticketLog->UserId = $request['UserId'];
            $ticketLog->save();
        }

        // CREATE DISCONNECTION TICKET
        // $ticket = new Tickets;
        // $ticket->id = IDGenerator::generateIDandRandString();
        // $ticket->AccountNumber = $request['AccountNumber'];
        // $ticket->ConsumerName = $request['ServiceAccountName'];
        // $ticket->Town = $request['Town'];
        // $ticket->Barangay = $request['Barangay'];
        // $ticket->Sitio = $request['Purok'];
        // $ticket->Ticket = Tickets::getDisconnectionDelinquencyId();
        // $ticket->Reason = 'Delinquency';
        // $ticket->GeoLocation = $request['LatitudeCaptured'] . ',' . $request['LongitudeCaptured'];
        // $ticket->Status = 'Executed';
        // $ticket->DateTimeDownloaded = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
        // $ticket->DateTimeLinemanArrived = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
        // $ticket->DateTimeLinemanExecuted = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
        // $ticket->UserId = $request['UserId'];
        // $ticket->Office = env('APP_LOCATION');
        // $ticket->save();

        return response()->json($discoHist, $this->successStatus);
    }
}