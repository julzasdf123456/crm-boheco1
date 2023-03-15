<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Models\IDGenerator;
use App\Models\ServiceConnectionCrew;
use App\Models\ServiceAccounts;
use App\Models\DisconnectionHistory;
use Illuminate\Support\Facades\DB;
use App\Models\BillingMeters;
use App\Models\Bills;
use App\Models\ChangeMeterLogs;
use App\Models\SMSNotifications;
use Validator;

class TicketsController extends Controller {

    public $successStatus = 200;

    public function getDownloadableTickets(Request $request) {
        $tickets = Tickets::where('CrewAssigned', $request['CrewAssigned'])
            ->whereNotNull('CrewAssigned')
            ->whereRaw("Status IN ('Acted', 'Received') AND (Trash IS NULL OR Trash='No')")
            ->get();

        if ($tickets) {
            return response()->json($tickets, $this->successStatus);
        } else {
            return response()->json(['response' => 'Error fetching tickets', 401]);
        }
    }

    public function getArchiveTickets(Request $request) {
        $endDate = date('Y-m-d', strtotime('today -11 days'));
        $tickets = Tickets::whereRaw("created_at > '" . $endDate . "' AND CrewAssigned NOT IN ('" . $request['CrewAssigned'] . "')")
            ->get();

        if ($tickets) {
            return response()->json($tickets, $this->successStatus);
        } else {
            return response()->json(['response' => 'Error fetching tickets', 401]);
        }
    }

    public function updateDownloadedTicketsStatus(Request $request) {
        $tickets = Tickets::where('CrewAssigned', $request['CrewAssigned'])
            ->whereNotNull('CrewAssigned')
            ->whereRaw("Status IN ('Acted', 'Received') AND (Trash IS NULL OR Trash='No')")
            ->get();

        $crew = ServiceConnectionCrew::find($request['CrewAssigned']);

        $dateTimeDownloaded = date('Y-m-d H:i:s');

        foreach($tickets as $item) {
            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateRandString();
            $ticketLog->TicketId = $item->id;
            $ticketLog->Log = "Ticket downloaded by lineman";
            $ticketLog->LogDetails = "Downloaded by " . ($crew != null ? $crew->StationName : "-") . " at " . $dateTimeDownloaded;
            $ticketLog->UserId = $request['UserId'];
            $ticketLog->save();

            // SEND SMS
            if ($item->ContactNumber != null) {
                if (strlen($item->ContactNumber) > 10 && strlen($item->ContactNumber) < 13) {
                    $msg = "Good day, " . $item->ConsumerName . ", \n\nYour complaint/request with ticket number " . $item->id . " has been received by BOHECO I Technical Team." .
                        "Expect the team's arrival in your premises within the next 48 hours. \n\nHave a graet day!";
                    SMSNotifications::createFreshSms($item->ContactNumber, $msg, 'TICKETS', $item->id);
                }
            } 
        }

        DB::table('CRM_Tickets')
            ->where('CrewAssigned', $request['CrewAssigned'])
            ->where('Status', 'Received')
            ->where(function ($query) {
                $query->where('Trash', 'No')
                    ->orWhereNull('Trash');
            })
            ->update(['Status' => 'Downloaded by Crew', 'DateTimeDownloaded' => $dateTimeDownloaded]);

        return response()->json(['response' => 'ok'], $this->successStatus);
    }

    public function uploadTickets(Request $request) {
        $tickets = Tickets::find($request['id']);

        if ($tickets != null) {
            $tickets->DateTimeLinemanArrived = $request['DateTimeLinemanArrived'];
            $tickets->DateTimeLinemanExecuted = $request['DateTimeLinemanExecuted'];
            $tickets->Status = $request['Status'];
            $tickets->Notes = $request['Notes'];
            $tickets->CurrentMeterReading = $request['CurrentMeterReading'];
            $tickets->NewMeterBrand = $request['NewMeterBrand'];
            $tickets->NewMeterNo = $request['NewMeterNo'];
            $tickets->NewMeterReading = $request['NewMeterReading'];
            $tickets->PercentError = $request['PercentError'];
            $tickets->LinemanCrewExecuted = $request['LinemanCrewExecuted'];
            $tickets->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateIDandRandString();
            $ticketLog->TicketId = $request['id'];
            $ticketLog->Log = $request['Status'];
            $ticketLog->LogDetails = $tickets->Notes;          
            $ticketLog->UserId = $request['UserId'];
            $ticketLog->save();

            // SEND SMS
            if ($tickets->ContactNumber != null) {
                if (strlen($tickets->ContactNumber) > 10 && strlen($tickets->ContactNumber) < 13) {
                    if ($request['Status'] == 'Executed') {
                        $msg = "Good day, " . $tickets->ConsumerName . ", \n\nThis is to inform you that your complaint/request with ticket number " . $tickets->id . " has been successfully acted by BOHECO I Technical Team." .
                        "\n\nHave a great day!";
                    } elseif ($request['Status'] == 'Acted') {
                        $msg = "Good day, " . $tickets->ConsumerName . ", \n\nYour complaint/request with ticket number " . $tickets->id . " was not success carried out by BOHECO I Technical Team due to the following findings: \n\n " .
                        $request['Notes'] . "\n\nShould these findings have been addressed, please notify us through our hotlines so we can schedule our re-visit." .
                        "\n\nHave a great day!";
                    } else {
                        $msg = "Good day, " . $tickets->ConsumerName . ", \n\nYour complaint/request with ticket number " . $tickets->id . " was not success carried out by BOHECO I Technical Team due to the following findings: \n\n " .
                        $request['Notes'] . "\n\nShould these findings have been addressed, please notify us through our hotlines so we can schedule our re-visit." .
                        "\n\nHave a great day!";
                    }
                    SMSNotifications::createFreshSms($tickets->ContactNumber, $msg, 'TICKETS', $tickets->id);
                }
            } 

            // FILTER TICKETS
            // if ($tickets->Ticket == Tickets::getDisconnectionDelinquencyId()) {
            //     /*
            //      * -----------------------
            //      * FOR DISCONNECTION
            //      * -----------------------
            //      */
            //     // UPDATE ACCOUNT
            //     $account = ServiceAccounts::find($tickets->AccountNumber);

            //     if ($account != null) {
            //         $account->AccountStatus = 'DISCONNECTED';
            //         $account->DateDisconnected = date('Y-m-d', strtotime($tickets->DateTimeLinemanExecuted));
            //         $account->save();

            //         // CREATE DISCONNECTION HISTORY
            //         $discoHist = new DisconnectionHistory;
            //         $discoHist->id = IDGenerator::generateIDandRandString();
            //         $discoHist->AccountNumber = $account->id;
            //         $discoHist->ServicePeriod = $tickets->ServicePeriod;
            //         $discoHist->Latitude = $account->Latitude;
            //         $discoHist->Longitude = $account->Longitude;
            //         $discoHist->Status = 'DISCONNECTED';
            //         $discoHist->UserId = $request['UserId'];
            //         $discoHist->DateDisconnected = date('Y-m-d', strtotime($tickets->DateTimeLinemanExecuted));
            //         $discoHist->TimeDisconnected = date('H:i:s', strtotime($tickets->DateTimeLinemanExecuted));
            //         $discoHist->save();
            //     }
            // } else if ($tickets->Ticket == Tickets::getChangeMeter()) {
            //     /**
            //      * -----------------------
            //      * FOR CHANGE METERS
            //      * -----------------------
            //      */
            //     // SAVE NEW METER
            //     $meter = new BillingMeters;
            //     $meter->id = IDGenerator::generateIDandRandString();
            //     $meter->ServiceAccountId = $tickets->AccountNumber;
            //     $meter->SerialNumber = $tickets->NewMeterNo;
            //     $meter->Brand = $tickets->NewMeterBrand;
            //     $meter->Multiplier = "1";
            //     $meter->InitialReading = $tickets->NewMeterReading;
            //     $meter->ConnectionDate = $tickets->DateTimeLinemanExecuted;
            //     $meter->save();

            //     if ($tickets->PercentError=='FOR AVERAGING') {
            //         // ------------------------------------
            //         // 1. GET LATEST BILL
            //         $latestBill = Bills::where('AccountNumber', $tickets->AccountNumber)
            //             ->orderByDesc('ServicePeriod')
            //             ->first();

            //         if ($latestBill != null) {
            //             // ------------------------------------
            //             // 2. AVERAGE LATEST BILLS
            //             $latestBills = Bills::where('AccountNumber', $tickets->AccountNumber)
            //             ->orderByDesc('ServicePeriod')
            //             ->limit(3)
            //             ->get();

            //             $averageKwh = 0;
            //             foreach($latestBills as $item) {
            //                 $averageKwh += floatval($item->KwhUsed);
            //             }
            //             $averageKwh = $averageKwh/count($latestBills);
                        
            //             // ------------------------------------
            //             // 3. COMPUTE DAYS INCURED
            //             $lastReadingDate = strtotime($latestBill->BillingDate);
            //             $now = strtotime($tickets->DateTimeLinemanExecuted);
            //             $daysIncured = $now - $lastReadingDate;
            //             $daysIncured = round($daysIncured / (60 * 60 * 24));

            //             // ------------------------------------
            //             // 4. GET DAILY AVERAGE
            //             $averageDaily = ($averageKwh/30) * $daysIncured;

            //             // ------------------------------------
            //             // 5. CREATE CHANGE METER LOGS
            //             $changeMeterLogs = new ChangeMeterLogs;
            //             $changeMeterLogs->id = IDGenerator::generateIDandRandString();
            //             $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
            //             $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
            //             $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
            //             $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
            //             $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;  
            //             $changeMeterLogs->AdditionalKwhForNextBilling = round($averageDaily, 2);
            //             $changeMeterLogs->ServicePeriod = date('Y-m-01', strtotime($latestBill->ServicePeriod . ' +1 month')); 
            //             $changeMeterLogs->save(); 
            //         } else {
            //             $svPeriod = date('Y-m-01');

            //             $changeMeterLogs = new ChangeMeterLogs;
            //             $changeMeterLogs->id = IDGenerator::generateIDandRandString();
            //             $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
            //             $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
            //             $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
            //             $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
            //             $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;   
            //             $changeMeterLogs->AdditionalKwhForNextBilling = $tickets->NewMeterReading;
            //             $changeMeterLogs->ServicePeriod = $svPeriod; 
            //             $changeMeterLogs->save(); 
            //         }                    
            //     } else {
            //         // ------------------------------------
            //         // 1. GET LATEST BILL
            //         $latestBill = Bills::where('AccountNumber', $tickets->AccountNumber)
            //             ->orderByDesc('ServicePeriod')
            //             ->first();

            //         if ($latestBill != null) {
            //             // ------------------------------------
            //             // 2. Get KWH Difference
            //             $dif = floatval($tickets->CurrentMeterReading) - floatval($latestBill->KwhUsed);

            //             $changeMeterLogs = new ChangeMeterLogs;
            //             $changeMeterLogs->id = IDGenerator::generateIDandRandString();
            //             $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
            //             $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
            //             $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
            //             $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
            //             $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;   
            //             $changeMeterLogs->AdditionalKwhForNextBilling = round($dif, 2);
            //             $changeMeterLogs->ServicePeriod = date('Y-m-01', strtotime($latestBill->ServicePeriod . ' +1 month')); 
            //             $changeMeterLogs->save(); 
            //         } else {
            //             $svPeriod = date('Y-m-01');

            //             $changeMeterLogs = new ChangeMeterLogs;
            //             $changeMeterLogs->id = IDGenerator::generateIDandRandString();
            //             $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
            //             $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
            //             $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
            //             $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
            //             $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;  
            //             $changeMeterLogs->AdditionalKwhForNextBilling = $tickets->NewMeterReading;
            //             $changeMeterLogs->ServicePeriod = $svPeriod; 
            //             $changeMeterLogs->save(); 
            //         }
            //     }
            // } else if ($tickets->Ticket == Tickets::getReconnection() && $tickets->Status == 'Executed') {
            //     $account = ServiceAccounts::find($tickets->AccountNumber);
            //     if ($account != null) {
            //         $account->AccountStatus = 'ACTIVE';
            //         $account->save();

            //         // ADD TO DISCO/RECO HISTORY
            //         $recoHist = new DisconnectionHistory;
            //         $recoHist->id = IDGenerator::generateIDandRandString();
            //         $recoHist->AccountNumber = $account->id;
            //         // $recoHist->ServicePeriod = $ticket->ServicePeriod;
            //         $recoHist->Status = 'RECONNECTED';
            //         $recoHist->UserId = $request['UserId'];
            //         $recoHist->DateDisconnected = date('Y-m-d', strtotime($tickets->DateTimeLinemanExecuted));
            //         $recoHist->TimeDisconnected = date('H:i:s', strtotime($tickets->DateTimeLinemanExecuted));
            //         $recoHist->save();
            //     }
            // }
        }

        return response()->json($tickets, $this->successStatus);
    }

    public function getCrewsFromStation(Request $request) {
        $station = $request['CrewLeader'];

        $crews = ServiceConnectionCrew::all();

        return response()->json($crews, 200);
    }
}