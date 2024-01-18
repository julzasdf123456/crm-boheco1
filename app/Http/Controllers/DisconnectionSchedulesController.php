<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDisconnectionSchedulesRequest;
use App\Http\Requests\UpdateDisconnectionSchedulesRequest;
use App\Repositories\DisconnectionSchedulesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\DisconnectionSchedules;
use App\Models\DisconnectionData;
use App\Models\DisconnectionRoutes;
use App\Models\UnbundledRates;
use App\Models\User;
use App\Models\Users;
use App\Models\AccountMaster;
use App\Models\IDGenerator;
use App\Exports\DiscoWeeklyReportExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Flash;
use Response;

class DisconnectionSchedulesController extends AppBaseController
{
    /** @var  DisconnectionSchedulesRepository */
    private $disconnectionSchedulesRepository;

    public function __construct(DisconnectionSchedulesRepository $disconnectionSchedulesRepo)
    {
        $this->middleware('auth');
        $this->disconnectionSchedulesRepository = $disconnectionSchedulesRepo;
    }

    /**
     * Display a listing of the DisconnectionSchedules.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $disconnectionSchedules = $this->disconnectionSchedulesRepository->all();

        return view('disconnection_schedules.index')
            ->with('disconnectionSchedules', $disconnectionSchedules);
    }

    /**
     * Show the form for creating a new DisconnectionSchedules.
     *
     * @return Response
     */
    public function create()
    {
        $currentMonth = UnbundledRates::orderByDesc('ServicePeriodEnd')->first();
        $meterReaders = User::role('Disconnector')->get();

        return view('disconnection_schedules.create', [
            'currentMonth' => $currentMonth,
            'meterReaders' => $meterReaders,
        ]);
    }

    /**
     * Store a newly created DisconnectionSchedules in storage.
     *
     * @param CreateDisconnectionSchedulesRequest $request
     *
     * @return Response
     */
    public function store(CreateDisconnectionSchedulesRequest $request)
    {
        $input = $request->all();

        $disconnectionSchedules = $this->disconnectionSchedulesRepository->create($input);

        // Flash::success('Disconnection Schedules saved successfully.');

        // return redirect(route('disconnectionSchedules.index'));
        return response()->json($disconnectionSchedules, 200);
    }

    /**
     * Display the specified DisconnectionSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $disconnectionSchedules = $this->disconnectionSchedulesRepository->find($id);

        if (empty($disconnectionSchedules)) {
            Flash::error('Disconnection Schedules not found');

            return redirect(route('disconnectionSchedules.index'));
        }

        return view('disconnection_schedules.show', [
            'disconnectionSchedules' => $disconnectionSchedules,

        ]);
    }

    /**
     * Show the form for editing the specified DisconnectionSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $disconnectionSchedules = $this->disconnectionSchedulesRepository->find($id);

        if (empty($disconnectionSchedules)) {
            Flash::error('Disconnection Schedules not found');

            return redirect(route('disconnectionSchedules.index'));
        }

        return view('disconnection_schedules.edit')->with('disconnectionSchedules', $disconnectionSchedules);
    }

    /**
     * Update the specified DisconnectionSchedules in storage.
     *
     * @param int $id
     * @param UpdateDisconnectionSchedulesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDisconnectionSchedulesRequest $request)
    {
        $disconnectionSchedules = $this->disconnectionSchedulesRepository->find($id);

        if (empty($disconnectionSchedules)) {
            Flash::error('Disconnection Schedules not found');

            return redirect(route('disconnectionSchedules.index'));
        }

        $disconnectionSchedules = $this->disconnectionSchedulesRepository->update($request->all(), $id);

        Flash::success('Disconnection Schedules updated successfully.');

        return redirect(route('disconnectionSchedules.index'));
    }

    /**
     * Remove the specified DisconnectionSchedules from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $disconnectionSchedules = $this->disconnectionSchedulesRepository->find($id);

        if (empty($disconnectionSchedules)) {
            Flash::error('Disconnection Schedules not found');

            return redirect(route('disconnectionSchedules.index'));
        }

        $this->disconnectionSchedulesRepository->delete($id);

        Flash::success('Disconnection Schedules deleted successfully.');

        return redirect(route('disconnectionSchedules.index'));
    }

    public function setSchedule(Request $request) {
        $from = $request['From'] ;
        $to = $request['To'];
        $day = $request['Day'];
        $userid = $request['UserId'];
        $period = $request['Period'];

        $schedId = $userid . "-" . $day . $period;
        $user = Users::find($userid);

        // CREATE SCHEDULE PARENT
        $schedule = DisconnectionSchedules::find($schedId);
        if ($schedule != null) {

        } else {
            $schedule = new DisconnectionSchedules;
            $schedule->id = $schedId;
            $schedule->DisconnectorName = $user != null ? $user->name : '';
            $schedule->DisconnectorId = $userid;
            $schedule->Day = $day;
            $schedule->ServicePeriodEnd = $period;
            $schedule->save();
        }

        $routes = DB::connection("sqlsrvbilling")
            ->table("AccountMaster")
            ->whereRaw("Route BETWEEN '" . $from. "' AND '" . $to . "'")
            ->select('Route')
            ->groupBy("Route")
            ->orderBy("Route")
            ->get(); 

        foreach($routes as $item) {
            $route = DisconnectionRoutes::whereRaw("ScheduleId='" . $schedId . "' AND Route='" . $item->Route . "'")->first();

            if ($route == null) {
                $route = new DisconnectionRoutes;
                $route->id = IDGenerator::generateIDandRandString();
                $route->ScheduleId = $schedId;
                $route->Route = $item->Route;
                $route->save();
            }            
        }

        return response()->json($schedule, 200);
    }

    public function getRoutes(Request $request) {
        $schedId = $request['ScheduleId'];

        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $schedId . "'")
            ->orderBy('Route')
            ->get();

        $schedule = DisconnectionSchedules::find($schedId);

        $output = "";
        foreach($routes as $item) {
            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        DB::raw("COUNT(Bills.AccountNumber) AS Count"),
                    )
                    ->first();
            } else {
                $townCode = substr($item->Route, 0, 2);
                $acctFrom = $townCode . $item->Route . sprintf("%04d", $item->SequenceFrom);
                $acctTo = $townCode . $item->Route . sprintf("%04d", $item->SequenceTo);

                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND (AccountMaster.AccountNumber BETWEEN '" . $acctFrom . "' AND '" . $acctTo . "') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        DB::raw("COUNT(Bills.AccountNumber) AS Count")
                    )
                    ->first();
            }            

            $output .= "<tr id='" . $item->id . "'>
                <td><strong>" . $item->Route . "</strong></td>
                <td>
                    <input type='number' id='from-" . $item->id . "' class='form-control form-control-sm' placeholder='Sequence From' value='" . $item->SequenceFrom . "'>
                </td>
                <td>
                    <input type='number' id='to-" . $item->id . "' class='form-control form-control-sm' placeholder='Sequence To' value='" . $item->SequenceTo . "'>
                </td>
                <td>" . ($count != null ? $count->Count : '0') . "</td>
                <td>
                    <button onclick='saveRoute(`" . $item->id . "`)' class='btn btn-sm btn-success'><i class='fas fa-check-circle'> </i> Save</button>
                    <button onclick='removeRoute(`" . $item->id . "`)' class='btn btn-sm btn-danger'><i class='fas fa-trash'> </i> Delete</button>
                </td>
            </tr>";
            
        }

        return response()->json($output, 200);
    }

    public function getStats(Request $request) {
        $userid = $request['UserId'];
        $day = $request['Day'];
        $period = $request['Period'];
        $refreshData = $request['RefreshData'] != null ? $request['RefreshData'] : 'false';

        $schedId = $userid . "-" . $day . $period;

        $schedule = DisconnectionSchedules::find($schedId);

        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $schedId . "'")
            ->orderBy('Route')
            ->get();

        // DELETE SCHED IF THERE ARE NO MORE ROUTES
        if (count($routes) == 0) {
            if ($schedule != null) {
                $schedule->delete();
            }

            // DELETE DISCO DATA
            DisconnectionData::where('ScheduleId', $schedId)
                ->delete();

            $dataSet = [
                'TotalCount' => 0,
                'TotalAmount' => 0,
            ];
        } else {
            $totalCount = 0;
            $totalAmount = 0;
            $i=1;
            $query = "";

            foreach($routes as $item) {
                $townCode = substr($item->Route, 0, 2);

                if ($item->SequenceFrom == null | $item->SequenceTo == null) {                    
                    $acctFrom = $townCode . $item->Route . '0000';
                    $acctTo = $townCode . $item->Route . '9999';

                    if ($i < count($routes)) {
                        $query .= " (AccountMaster.Route='" . $item->Route . "' AND (AccountMaster.AccountNumber BETWEEN '" . $acctFrom . "' AND '" . $acctTo . "') ) OR ";
                    } else {
                        $query .= " (AccountMaster.Route='" . $item->Route . "' AND (AccountMaster.AccountNumber BETWEEN '" . $acctFrom . "' AND '" . $acctTo . "') ) ";
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
                        ->whereRaw("ServicePeriodEnd<='" . $period . "' " . $query . " AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                            AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                        ->select(
                            DB::raw("(COUNT(Bills.AccountNumber)) AS TotalCount"),
                            DB::raw("(SUM(NetAmount)) AS TotalAmount"),
                        )
                        ->first();

            if ($refreshData == 'true') {               
                DisconnectionData::where('ScheduleId', $schedId)
                ->delete();

                $dataMerge = DB::connection("sqlsrvbilling")
                        ->table('Bills')
                        ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                        ->whereRaw("ServicePeriodEnd<='" . $period . "' " . $query . " AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                                AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd) ")
                        ->select(
                            DB::raw("NEWID() AS id"),
                            DB::raw("'" . $schedId .  "' AS ScheduleId"),
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
                            'Bills.NetAmount',
                        )
                        ->orderBy('Bills.AccountNumber')
                        ->get();

                // SAVE DISCO DATA
                foreach ($dataMerge as $item) {
                    $discoData = DisconnectionData::where('AccountNumber', $item->AccountNumber)
                        ->where('ServicePeriodEnd', $item->ServicePeriodEnd)
                        ->where('DisconnectorName', $item->DisconnectorName)
                        ->where('ScheduleId', $item->ScheduleId)
                        ->first();

                    if ($discoData != null) {

                    } else {
                        $discoData = new DisconnectionData;
                        $discoData->id = IDGenerator::generateID() . '-' . $item->id;
                        $discoData->ScheduleId = $item->ScheduleId;
                        $discoData->DisconnectorName = $item->DisconnectorName;
                        $discoData->UserId = $item->UserId;
                        $discoData->AccountNumber = $item->AccountNumber;
                        $discoData->ServicePeriodEnd = $item->ServicePeriodEnd;
                        $discoData->AccountCoordinates = $item->AccountCoordinates;
                        $discoData->ConsumerName = $item->ConsumerName;
                        $discoData->ConsumerAddress = $item->ConsumerAddress;
                        $discoData->MeterNumber = $item->MeterNumber;
                        $discoData->NetAmount = $item->NetAmount;
                        $discoData->PoleNumber = $item->PoleNumber;
                        $discoData->save();
                    }
                }
            }

            $dataSet = [
                'TotalCount' => $data->TotalCount,
                'TotalAmount' => $data->TotalAmount,
            ];
        }

        return response()->json($dataSet, 200);
    }

    public function viewDisconnectionConsumers($id, $day, $period) {
        $schedId = $id . "-" . $day . $period;

        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $schedId . "'")
            ->orderBy('Route')
            ->get();

        $schedule = DisconnectionSchedules::find($schedId);

        $data = DB::connection("sqlsrvbilling")
                    ->table('DisconnectionData')
                    ->leftJoin('AccountMaster', 'DisconnectionData.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->select(
                        'AccountMaster.AccountNumber',
                        'ServicePeriodEnd',
                        'DisconnectionData.ConsumerName',
                        'DisconnectionData.ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType'
                    )
                    ->whereRaw("ScheduleId='" . $schedId . "'")
                    ->orderBy('AccountMaster.AccountNumber')
                    ->get();

        $groupedData = DB::connection("sqlsrvbilling")
                    ->table('DisconnectionData')
                    ->leftJoin('AccountMaster', 'DisconnectionData.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->select(
                        'AccountMaster.AccountNumber', 
                        'AccountMaster.MeterNumber', 
                        'DisconnectionData.ConsumerName', 
                        'DisconnectionData.ConsumerAddress',
                        'DisconnectionData.PaymentNotes',
                        'DisconnectionData.Notes',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType',
                        'AccountMaster.Pole',
                        DB::raw("SUM(NetAmount) AS TotalAmountDue"),
                        DB::raw("COUNT(NetAmount) AS NoOfMonths")
                    )
                    ->whereRaw("ScheduleId='" . $schedId . "'")
                    ->groupBy(
                        'AccountMaster.AccountNumber', 
                        'AccountMaster.MeterNumber', 
                        'DisconnectionData.ConsumerName', 
                        'DisconnectionData.ConsumerAddress',
                        'DisconnectionData.PaymentNotes',
                        'DisconnectionData.Notes',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType',
                        'AccountMaster.Pole')
                    ->get();

        return view('/disconnection_schedules/view_disconnection_consumers', [
            'data' => $data,
            'groupedData' => $groupedData,
            'schedule' => $schedule,
            'routes' => $routes,
        ]);
    }

    public function getSchedulesData(Request $request) {
        // $month = $request['Month'];
        // $from = date('Y-m-d', strtotime('first day of ' . $month));
        // $to = date('Y-m-d', strtotime('last day of ' . $month));

        // $schedules = DisconnectionSchedules::whereRaw("Day BETWEEN '" . $from . "' AND '" . $to . "'")->get();
        $schedules = DisconnectionSchedules::all();

        return response()->json($schedules, 200);
    }

    public function getAccountsFromSchedule(Request $request) {
        $id = $request['id'];
     
        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $id . "'")
            ->orderBy('Route')
            ->get();

        $schedule = DisconnectionSchedules::find($id);

        // DELETE SCHED IF THERE ARE NO MORE ROUTES
        if (count($routes) == 0) {
            $output = "";
        } else {
            $i = 1;
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

            // $data = DB::connection("sqlsrvbilling")
            //             ->table('Bills')
            //             ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
            //             ->whereRaw("ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' " . $query . " AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
            //                 AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
            //             ->select(
            //                 'Bills.AccountNumber',
            //                 'ServicePeriodEnd',
            //                 'PowerKWH',
            //                 'ConsumerName',
            //                 'ConsumerAddress',
            //                 'AccountMaster.MeterNumber',
            //                 'NetAmount',
            //                 'AccountMaster.AccountStatus',
            //                 'AccountMaster.ConsumerType'
            //             )
            //             ->orderBy('Bills.AccountNumber')
            //             ->get();

            $data = DB::connection("sqlsrvbilling")
                    ->table('DisconnectionData')
                    ->leftJoin('AccountMaster', 'DisconnectionData.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->select(
                        'AccountMaster.AccountNumber',
                        'DisconnectionData.ConsumerName',
                        'DisconnectionData.ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType',
                        DB::raw("SUM(NetAmount) AS NetAmount"),
                        DB::raw("COUNT(AccountMaster.AccountNumber) AS Count"),
                    )
                    ->whereRaw("ScheduleId='" . $id . "'")
                    ->orderBy('AccountMaster.AccountNumber')
                    ->groupBy('AccountMaster.AccountNumber',
                        'DisconnectionData.ConsumerName',
                        'DisconnectionData.ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType')
                    ->get();

            $output = "";
            $x=1;
            foreach($data as $itemx) {
                $output .= "<tr>
                                <td>" . $x . "</td>
                                <td>" . $itemx->AccountNumber . "</td>
                                <td>" . $itemx->ConsumerName . "</td>
                                <td>" . $itemx->ConsumerAddress . "</td>
                                <td>" . $itemx->MeterNumber . "</td>
                                <td>" . $itemx->ConsumerType . "</td>
                                <td>" . $itemx->AccountStatus . "</td>
                                <td class='text-right text-danger'><strong>" . $itemx->Count . "</strong></td>
                                <td class='text-right text-danger'><strong>" . number_format($itemx->NetAmount, 2) . "</strong></td>
                            </tr>";
                $x++;
            }
        }

        

        return response()->json($output, 200);
    }

    public function monitor(Request $request) {
        return view('/disconnection_schedules/monitor', [

        ]);
    }

    public function monitorView($id) {
        $disconnectionSchedules = $this->disconnectionSchedulesRepository->find($id);

        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $id . "'")
            ->orderBy('Route')
            ->get();

        if ($disconnectionSchedules->Status == 'Downloaded') {
            $data = DB::connection("sqlsrvbilling")
                    ->table('DisconnectionData')
                    ->leftJoin('AccountMaster', 'DisconnectionData.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("DisconnectionData.ScheduleId='" . $id . "'")
                    ->select(
                        'DisconnectionData.AccountNumber',
                        'DisconnectionData.ServicePeriodEnd',
                        'AccountMaster.ConsumerName',
                        'AccountMaster.ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'DisconnectionData.NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType',
                        "DisconnectionData.Status",
                        "DisconnectionData.Notes",
                        "DisconnectionData.LastReading",
                        "DisconnectionData.PaidAmount as AmountPaid",
                        "DisconnectionData.DisconnectionDate",
                    )
                    ->orderByDesc('DisconnectionData.Status')
                    ->get();
        } else {
            $i = 1;
        $query = "";
        foreach($routes as $item) {
            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                if ($i < count($routes)) {
                    $query .= " (AccountMaster.Route='" . $item->Route . "') OR ";
                } else {
                    $query .= " (AccountMaster.Route='" . $item->Route . "') ";
                }                
            } else {
                if ($i < count($routes)) {
                    $query .= " (AccountMaster.Route='" . $item->Route . "' AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') ) OR ";
                } else {
                    $query .= " (AccountMaster.Route='" . $item->Route . "' AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') ) ";
                }
            } 
            $i++;
        }

        $data = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("Bills.ServicePeriodEnd<='" . $disconnectionSchedules->ServicePeriodEnd . "' AND (" . $query . ") AND GETDATE() > DueDate AND 
                        (AccountMaster.AccountStatus IN ('ACTIVE') OR AccountMaster.AccountNumber IN (SELECT AccountNumber FROM DisconnectionData WHERE ScheduleId='" . $disconnectionSchedules->id . "' AND Status='Disconnected'))
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE Teller NOT IN ('" . $disconnectionSchedules->DisconnectorName . "') AND AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        'Bills.AccountNumber',
                        'Bills.ServicePeriodEnd',
                        'PowerKWH',
                        'AccountMaster.ConsumerName',
                        'AccountMaster.ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'Bills.NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType',
                        DB::raw("'' AS Notes"),
                        DB::raw("(SELECT TOP 1 Status FROM DisconnectionData WHERE ScheduleId='" . $disconnectionSchedules->id . "' AND AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd) AS Status"),
                        DB::raw("(SELECT TOP 1 PaidAmount FROM DisconnectionData WHERE ScheduleId='" . $disconnectionSchedules->id . "' AND AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd) AS AmountPaid"),
                        DB::raw("(SELECT TOP 1 DisconnectionDate FROM DisconnectionData WHERE ScheduleId='" . $disconnectionSchedules->id . "' AND AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd) AS DisconnectionDate"),
                    )
                    ->orderBy('Bills.AccountNumber')
                    ->get();
                    
            $data = $data->toArray();
            usort($data, function($a, $b) {
                return strcmp($b->Status, $a->Status);
            });
        }

        $totalCollection = DB::connection("sqlsrvbilling")
            ->table('DisconnectionData')
            ->whereRaw("ScheduleId='" . $disconnectionSchedules->id . "'")
            ->select(
                DB::raw("SUM(PaidAmount) AS PaidAmount"),                
                DB::raw("COUNT(PaidAmount) AS PaidCount")
            )
            ->first();

        $poll = DB::connection("sqlsrvbilling")
            ->table('DisconnectionData')
            ->whereRaw("ScheduleId='" . $disconnectionSchedules->id . "'")
            ->select(
                DB::raw("ISNULL(Status, 'Unfinished') AS Status"),
                DB::raw("COUNT(DISTINCT AccountNumber) AS TotalCount")
            )
            ->groupBy('Status')
            ->orderBy('Status')
            ->get();
        
        return view('/disconnection_schedules/monitor_view', [
            'disconnectionSchedules' => $disconnectionSchedules,
            'routes' => $routes,
            'data' => $data,
            'totalCollection' => $totalCollection,
            'poll' => $poll,
            'totalAccounts' => DB::connection("sqlsrvbilling")->table('DisconnectionData')->where('ScheduleId', $disconnectionSchedules->id)->select(DB::raw("COUNT(DISTINCT ACcountNumber) AS TotalCount"))->first()
        ]);
    }

    public function disconnectionMapData(Request $request) {
        $id = $request['ScheduleId'];

        $data = DisconnectionData::where("ScheduleId", $id)->get();

        return response()->json($data, 200);
    }

    public function getSchedulesCollectionCalendarData(Request $request) {
        // $month = $request['Month'];
        // $from = date('Y-m-d', strtotime('first day of ' . $month));
        // $to = date('Y-m-d', strtotime('last day of ' . $month));

        // $schedules = DisconnectionSchedules::whereRaw("Day BETWEEN '" . $from . "' AND '" . $to . "'")->get();
        $schedules = DB::connection("sqlsrvbilling")
            ->table("DisconnectionData")
            // ->whereRaw("PaidAmount > 0 AND ORNumber IS NULL")
            ->select(
                "DisconnectorName",
                DB::raw("TRY_CAST(DisconnectionDate AS DATE) AS DisconnectionDate"),
                'ORNumber'
            )
            ->groupBy("DisconnectorName")
            ->groupByRaw("TRY_CAST(DisconnectionDate AS DATE), ORNumber")
            ->get();

        return response()->json($schedules, 200);
    }

    public function addPaymentNotes(Request $request) {
        $acctNo = $request['AccountNumber'];
        $schedId = $request['ScheduleId'];
        $paymentNotes = $request['PaymentNotes'];
        $notes = $request['Notes'];

        DisconnectionData::where('AccountNumber', $acctNo)
            ->where('ScheduleId', $schedId)
            ->update(['PaymentNotes' => $paymentNotes, 'Notes' => $notes]);

        return response()->json('ok', 200);
    }

    public function weeklyReport(Request $request) {
        $from = $request['From'];
        $to = $request['To'];

        $data = DB::connection('sqlsrvbilling')
            ->table('DisconnectionSchedules')
            ->whereRaw("Day BETWEEN '" . $from . "' AND '" . $to . "'")
            ->select(
                'Day',
                'DisconnectorName',
                DB::raw("(SELECT SUBSTRING((SELECT ',' + Route AS 'data()' FROM DisconnectionRoutes WHERE ScheduleId=DisconnectionSchedules.id GROUP BY Route FOR XML PATH('')), 2 , 9999)) As Blocks"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id) AS Accounts"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND (PaidAmount IS NOT NULL AND PaidAmount > 0)) AS BillsPaid"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status='Disconnected') AS Disconnected"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status IS NOT NULL AND Status NOT IN ('Paid', 'Disconnected')) AS WithRemarks"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status IS NULL) AS Unfinished"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status IS NOT NULL) AS Finished"),
                DB::raw("(SELECT SUBSTRING((SELECT ';' + TRY_CAST(COUNT(AccountNumber) AS VARCHAR(30)) + ' - ' + Status AS 'data()' FROM DisconnectionData WHERE Status NOT IN ('Paid', 'Disconnected') AND ScheduleId=DisconnectionSchedules.id GROUP BY Status FOR XML PATH('')), 2 , 9999)) As RemarksPoll"),
            )
            ->orderBy('Day')
            ->orderBy('Blocks')
            ->get();

        return view('/disconnection_schedules/weekly_report', [
            'data' => $data,
        ]);
    }

    public function downloadWeeklyReport($from, $to) {
        $data = DB::connection('sqlsrvbilling')
            ->table('DisconnectionSchedules')
            ->whereRaw("Day BETWEEN '" . $from . "' AND '" . $to . "'")
            ->select(
                'Day',
                'DisconnectorName',
                DB::raw("(SELECT SUBSTRING((SELECT ',' + Route AS 'data()' FROM DisconnectionRoutes WHERE ScheduleId=DisconnectionSchedules.id GROUP BY Route FOR XML PATH('')), 2 , 9999)) As Blocks"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id) AS Accounts"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND (PaidAmount IS NOT NULL AND PaidAmount > 0)) AS BillsPaid"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status='Disconnected') AS Disconnected"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status IS NOT NULL AND Status NOT IN ('Paid', 'Disconnected')) AS WithRemarks"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status IS NULL) AS Unfinished"),
                DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM DisconnectionData WHERE ScheduleId=DisconnectionSchedules.id AND Status IS NOT NULL) AS Finished"),
                DB::raw("(SELECT SUBSTRING((SELECT ';' + TRY_CAST(COUNT(AccountNumber) AS VARCHAR(30)) + ' - ' + Status AS 'data()' FROM DisconnectionData WHERE Status NOT IN ('Paid', 'Disconnected') AND ScheduleId=DisconnectionSchedules.id GROUP BY Status FOR XML PATH('')), 2 , 9999)) As RemarksPoll"),
            )
            ->orderBy('Day')
            ->orderBy('Blocks')
            ->get();

        $export = new DiscoWeeklyReportExport($data, $from, $to);

        return Excel::download($export, 'Disconnection-Weekly-Report.xlsx');
    }

    public function getDiscoDataFromStatus(Request $request) {
        $status = $request['Status'];
        $schedId = $request['ScheduleId'];

        if ($status == 'Unfinished') {
            $data = DB::connection("sqlsrvbilling")
                ->table('DisconnectionData')
                ->where('ScheduleId', $schedId)
                ->whereNull('Status')
                ->select(
                    'AccountNumber',
                    'ConsumerName',
                    'ConsumerAddress',
                    DB::raw("SUM(NetAmount) AS TotalAmountDue"),
                    'Status'
                )
                ->groupBy('AccountNumber', 'ConsumerName', 'ConsumerAddress', 'Status')
                ->orderBy('AccountNumber')
                ->get();
        } else {
            $data = DB::connection("sqlsrvbilling")
                ->table('DisconnectionData')
                ->where('ScheduleId', $schedId)
                ->where('Status', $status)
                ->select(
                    'AccountNumber',
                    'ConsumerName',
                    'ConsumerAddress',
                    DB::raw("SUM(NetAmount) AS TotalAmountDue"),
                    'Status'
                )
                ->groupBy('AccountNumber', 'ConsumerName', 'ConsumerAddress', 'Status')
                ->orderBy('AccountNumber')
                ->get();
        }
        
        $output = "";
        $i = 1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $item->AccountNumber . "</td>
                            <td>" . $item->ConsumerName . "</td>
                            <td>" . $item->ConsumerAddress . "</td>
                            <td class='text-right'>" . (number_format($item->TotalAmountDue, 2)) . "</td>
                            <td>" . $item->Status . "</td>
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }
}
