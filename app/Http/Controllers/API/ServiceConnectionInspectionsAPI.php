<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionInspections;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Models\Notifiers;
use App\Models\ServiceConnectionPayTransaction;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\SMSNotifications;
use Illuminate\Support\Facades\DB;
use Validator;

class ServiceConnectionInspectionsAPI extends Controller {

    public $successStatus = 200;

    public function getServiceConnections(Request $request) {
        $serviceConnections = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->select('CRM_ServiceConnections.*')
            ->where(function($query) {
                $query->where('CRM_ServiceConnections.Status', "For Inspection")
                    ->orWhere('CRM_ServiceConnections.Status', "Re-Inspection");
            })
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->where('CRM_ServiceConnectionInspections.Inspector', $request['userid'])
            ->get(); 

        if ($serviceConnections == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($serviceConnections, $this->successStatus); 
        }  
    }

    public function getServiceInspections(Request $request) {
        $serviceConnections = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->select('CRM_ServiceConnectionInspections.*')
            ->where(function($query) {
                $query->where('CRM_ServiceConnections.Status', "For Inspection")
                    ->orWhere('CRM_ServiceConnections.Status', "Re-Inspection");
            })
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->where('CRM_ServiceConnectionInspections.Inspector', $request['userid'])
            ->get(); 

        if ($serviceConnections == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($serviceConnections, $this->successStatus); 
        }  
    }
    
    public function notifyDownloadedInspections(Request $request) {
        $name = $request['ServiceAccountName'];
        $contactNumber = $request['ContactNumber'];
        // CREATE NOTIFICATION
        if ($contactNumber != null) {
            if (strlen($contactNumber > 10)) {
                $msg = "MR./MS. " . $name . ", \n\nA BOHECO I inspector is on its way to inspect the electrical installation of your house within the day. Please ensure to have a representative in your house during the inspection process. \n\nHave a gret day!";
                SMSNotifications::createFreshSms($contactNumber, $msg, 'SERVICE CONNECTIONS', $request['id']);
            }
        }

        return response()->json('ok', 200);
    }

    public function updateServiceInspections(Request $request) {
        $serviceConnectionInspections = ServiceConnectionInspections::find($request['id']);
        $serviceConnections = ServiceConnections::find($request['ServiceConnectionId']);

        $serviceConnectionInspections->SEMainCircuitBreakerAsInstalled = $request['SEMainCircuitBreakerAsInstalled'];
        $serviceConnectionInspections->SENoOfBranchesAsInstalled = $request['SENoOfBranchesAsInstalled'];
        $serviceConnectionInspections->PoleGIEstimatedDiameter = $request['PoleGIEstimatedDiameter'];
        $serviceConnectionInspections->PoleGIHeight = $request['PoleGIHeight'];
        $serviceConnectionInspections->PoleGINoOfLiftPoles = $request['PoleGINoOfLiftPoles'];
        $serviceConnectionInspections->PoleConcreteEstimatedDiameter = $request['PoleConcreteEstimatedDiameter'];
        $serviceConnectionInspections->PoleConcreteHeight = $request['PoleConcreteHeight'];
        $serviceConnectionInspections->PoleConcreteNoOfLiftPoles = $request['PoleConcreteNoOfLiftPoles'];
        $serviceConnectionInspections->PoleHardwoodEstimatedDiameter = $request['PoleHardwoodEstimatedDiameter'];
        $serviceConnectionInspections->PoleHardwoodHeight = $request['PoleHardwoodHeight'];
        $serviceConnectionInspections->PoleHardwoodNoOfLiftPoles = $request['PoleHardwoodNoOfLiftPoles'];
        $serviceConnectionInspections->PoleRemarks = $request['PoleRemarks'];
        $serviceConnectionInspections->SDWSizeAsInstalled = $request['SDWSizeAsInstalled'];
        $serviceConnectionInspections->SDWLengthAsInstalled = $request['SDWLengthAsInstalled'];
        $serviceConnectionInspections->GeoBuilding = $request['GeoBuilding'];
        $serviceConnectionInspections->GeoTappingPole = $request['GeoTappingPole'];
        $serviceConnectionInspections->GeoMeteringPole = $request['GeoMeteringPole'];
        $serviceConnectionInspections->GeoSEPole = $request['GeoSEPole'];
        $serviceConnectionInspections->FirstNeighborName = $request['FirstNeighborName'];
        $serviceConnectionInspections->FirstNeighborMeterSerial = $request['FirstNeighborMeterSerial'];
        $serviceConnectionInspections->SecondNeighborName = $request['SecondNeighborName'];
        $serviceConnectionInspections->SecondNeighborMeterSerial = $request['SecondNeighborMeterSerial'];
        $serviceConnectionInspections->Status = $request['Status'];
        $serviceConnectionInspections->DateOfVerification = $request['DateOfVerification'];
        $serviceConnectionInspections->EstimatedDateForReinspection = $request['EstimatedDateForReinspection'];
        $serviceConnectionInspections->Notes = $request['Notes'];
        $serviceConnectionInspections->Inspector = $request['Inspector'];

        $serviceConnections->Status = $request['Status'];

        if ($serviceConnectionInspections->save()) {
            $serviceConnections->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
            $timeFrame->UserId = $request['Inspector'];
            $timeFrame->Status = $request['Status'];

            // UPDATE TICKETS IF INSPECTION
            $ticket = Tickets::where('InspectionId', $request['id'])->first();
            if ($request['Status'] == 'Approved') {
                $timeFrame->Notes = 'Inspection approved and is waiting for payment';
                
                if ($ticket != null) {
                    $ticket->Status = 'For Payment';
                    $ticket->save();

                    // CREATE LOG
                    $ticketLog = new TicketLogs;
                    $ticketLog->id = IDGenerator::generateID();
                    $ticketLog->TicketId = $ticket->id;
                    $ticketLog->Log = "Inspection Performed";
                    $ticketLog->LogDetails = 'Inspection approved and is waiting for payment';
                    $ticketLog->UserId = $request['Inspector'];
                    $ticketLog->save();

                    // CREATE PAYMENT IF RELOCATION
                    $trans = new ServiceConnectionPayTransaction;
                    $trans->id = IDGenerator::generateIDandRandString();
                    $trans->ServiceConnectionId = $serviceConnections->id;
                    $trans->Particular = '1666677846332';
                    $trans->Amount = '15';
                    $trans->Vat = '1.18';
                    $trans->Total = '16.18';
                    $trans->save();

                    $ttl = new ServiceConnectionTotalPayments;
                    $ttl->id = IDGenerator::generateIDandRandString();
                    $ttl->ServiceConnectionId = $serviceConnections->id;
                    $ttl->SubTotal = '15';
                    $ttl->TotalVat = '1.18';
                    $ttl->Total = '16.18';
                    $ttl->save();
                }
            } else {
                $timeFrame->Notes = 'Application is not approved. ' . $request['Notes'];

                if ($ticket != null) {
                    // CREATE LOG
                    $ticketLog = new TicketLogs;
                    $ticketLog->id = IDGenerator::generateID();
                    $ticketLog->TicketId = $ticket->id;
                    $ticketLog->Log = "Inspection Performed";
                    $ticketLog->LogDetails = 'Inspection disapproved because of: ' . $request['Notes'];
                    $ticketLog->UserId = $request['Inspector'];
                    $ticketLog->save();
                }
            }
            
            $timeFrame->save();

            return response()->json(['ok' => 'ok'], $this->successStatus);
        } else {
            return response()->json(['error' => 'Error updating data'], 401);
        }
    }
}