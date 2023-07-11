<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDisconnectionSchedulesRequest;
use App\Http\Requests\UpdateDisconnectionSchedulesRequest;
use App\Repositories\DisconnectionSchedulesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\DisconnectionSchedules;
use App\Models\DisconnectionRoutes;
use App\Models\UnbundledRates;
use App\Models\User;
use App\Models\Users;
use App\Models\AccountMaster;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') 
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

        $schedId = $userid . "-" . $day . $period;

        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $schedId . "'")
            ->orderBy('Route')
            ->get();

        $totalCount = 0;
        $totalAmount = 0;
        foreach($routes as $item) {
            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $period . "' AND AccountMaster.Route='" . $item->Route . "' AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        DB::raw("COUNT(Bills.AccountNumber) AS Count"),
                        DB::raw("SUM(Bills.NetAmount) AS TotalAmount"),
                    )
                    ->first();
            } else {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $period . "' AND AccountMaster.Route='" . $item->Route . "' AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        DB::raw("COUNT(Bills.AccountNumber) AS Count"),
                        DB::raw("SUM(Bills.NetAmount) AS TotalAmount"),
                    )
                    ->first();
            }  
            
            $totalCount += floatval($count->Count);
            $totalAmount += floatval($count->TotalAmount);
        }

        $data = [
            'TotalCount' => $totalCount,
            'TotalAmount' => $totalAmount,
        ];

        return response()->json($data, 200);
    }

    public function viewDisconnectionConsumers($id, $day, $period) {
        $schedId = $id . "-" . $day . $period;

        $routes = DisconnectionRoutes::whereRaw("ScheduleId='" . $schedId . "'")
            ->orderBy('Route')
            ->get();

        $schedule = DisconnectionSchedules::find($schedId);

        $data = [];
        foreach($routes as $item) {
            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $period . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        'Bills.AccountNumber',
                        'ServicePeriodEnd',
                        'PowerKWH',
                        'ConsumerName',
                        'ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType'
                    )
                    ->orderBy('Bills.AccountNumber')
                    ->get();
            } else {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $period . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        'Bills.AccountNumber',
                        'ServicePeriodEnd',
                        'PowerKWH',
                        'ConsumerName',
                        'ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType'
                    )
                    ->orderBy('Bills.AccountNumber')
                    ->get();
            }  
            
            $data = array_merge($data, $count->toArray());
        }

        return view('/disconnection_schedules/view_disconnection_consumers', [
            'data' => $data,
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

        $data = [];
        foreach($routes as $item) {
            if ($item->SequenceFrom == null | $item->SequenceTo == null) {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        'Bills.AccountNumber',
                        'ServicePeriodEnd',
                        'PowerKWH',
                        'ConsumerName',
                        'ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType'
                    )
                    ->orderBy('Bills.AccountNumber')
                    ->get();
            } else {
                $count = DB::connection("sqlsrvbilling")
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->whereRaw("ServicePeriodEnd<='" . $schedule->ServicePeriodEnd . "' AND AccountMaster.Route='" . $item->Route . "'  AND GETDATE() > DueDate AND AccountStatus IN ('ACTIVE') 
                        AND (AccountMaster.SequenceNumber BETWEEN '" . $item->SequenceFrom . "' AND '" . $item->SequenceTo . "') 
                        AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select(
                        'Bills.AccountNumber',
                        'ServicePeriodEnd',
                        'PowerKWH',
                        'ConsumerName',
                        'ConsumerAddress',
                        'AccountMaster.MeterNumber',
                        'NetAmount',
                        'AccountMaster.AccountStatus',
                        'AccountMaster.ConsumerType'
                    )
                    ->orderBy('Bills.AccountNumber')
                    ->get();
            }  
            
            $data = array_merge($data, $count->toArray());
        }

        $output = "";
        $i=1;
        foreach($data as $itemx) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $itemx->AccountNumber . "</td>
                            <td>" . $itemx->ConsumerName . "</td>
                            <td>" . $itemx->ConsumerAddress . "</td>
                            <td>" . $itemx->MeterNumber . "</td>
                            <td>" . $itemx->ConsumerType . "</td>
                            <td>" . $itemx->AccountStatus . "</td>
                            <td>" . date('F Y', strtotime($itemx->ServicePeriodEnd)) . "</td>
                            <td class='text-right text-danger'><strong>" . number_format($itemx->NetAmount, 2) . "</strong></td>
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }
}