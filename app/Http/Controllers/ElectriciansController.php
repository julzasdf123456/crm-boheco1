<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateElectriciansRequest;
use App\Http\Requests\UpdateElectriciansRequest;
use App\Repositories\ElectriciansRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Electricians;
use App\Models\ServiceConnections;
use App\Exports\DynamicExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IDGenerator;
use Flash;
use Response;

class ElectriciansController extends AppBaseController
{
    /** @var  ElectriciansRepository */
    private $electriciansRepository;

    public function __construct(ElectriciansRepository $electriciansRepo)
    {
        $this->middleware('auth');
        $this->electriciansRepository = $electriciansRepo;
    }

    /**
     * Display a listing of the Electricians.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $electricians = $this->electriciansRepository->all();

        return view('electricians.index')
            ->with('electricians', $electricians);
    }

    /**
     * Show the form for creating a new Electricians.
     *
     * @return Response
     */
    public function create()
    {
        return view('electricians.create');
    }

    /**
     * Store a newly created Electricians in storage.
     *
     * @param CreateElectriciansRequest $request
     *
     * @return Response
     */
    public function store(CreateElectriciansRequest $request)
    {
        $input['id'] = IDGenerator::generateID();
        $input = $request->all();

        $electricians = $this->electriciansRepository->create($input);

        Flash::success('Electricians saved successfully.');

        return redirect(route('electricians.index'));
    }

    /**
     * Display the specified Electricians.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        return view('electricians.show')->with('electricians', $electricians);
    }

    /**
     * Show the form for editing the specified Electricians.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        return view('electricians.edit')->with('electricians', $electricians);
    }

    /**
     * Update the specified Electricians in storage.
     *
     * @param int $id
     * @param UpdateElectriciansRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateElectriciansRequest $request)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        $electricians = $this->electriciansRepository->update($request->all(), $id);

        Flash::success('Electricians updated successfully.');

        return redirect(route('electricians.index'));
    }

    /**
     * Remove the specified Electricians from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        $this->electriciansRepository->delete($id);

        Flash::success('Electricians deleted successfully.');

        return redirect(route('electricians.index'));
    }

    public function getElectricianAjax(Request $request) {
        return response()->json(Electricians::find($request['id']), 200);
    }

    public function housewiringLabor(Request $request) {
        $office = $request['Office'];
        $month = $request['Month'];
        $term = $request['Term'];
        $year = $request['Year'];

        if ($month != null && $year != null && $term != null) {
            if ($term == '1') {
                $from = $year . '-' . $month . '-01';
                $to = $year . '-' . $month . '-15';
            } elseif ($term == '2') {
                $from = $year . '-' . $month . '-16';
                $to = date('Y-m-d', strtotime('last day of ' . $from));
            } else {
                $from = '1997-01-01';
                $to = '1997-01-01';
            }
        } else {
            $from = '1997-01-01';
            $to = '1997-01-01';
        }

        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionTotalPayments', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId')
            ->whereRaw("CRM_ServiceConnections.ORDate IS NOT NULL AND (ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND ElectricianAcredited='Yes' 
                AND (CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No')")
            ->select(
                'CRM_ServiceConnections.id',
                'ORDate',
                'ORNumber',
                'ServiceAccountName',
                'Sitio',
                'CRM_Barangays.Barangay',
                'CRM_Towns.Town',
                'ElectricianName',
                DB::raw("(SELECT SUM(TRY_CAST(Quantity AS INTEGER)) FROM CRM_ServiceConnectionMaterialPayments WHERE ServiceConnectionId=CRM_ServiceConnections.id AND Material IN ('1659509010096', '1659509088407')) AS Breakers"),
                DB::raw("(SELECT SUM(TRY_CAST(Quantity AS INTEGER)) FROM CRM_ServiceConnectionMaterialPayments WHERE ServiceConnectionId=CRM_ServiceConnections.id AND Material IN ('1629263248908', '1629263170665')) AS Outlets"),
                DB::raw("(SELECT SUM(TRY_CAST(Quantity AS INTEGER)) FROM CRM_ServiceConnectionMaterialPayments WHERE ServiceConnectionId=CRM_ServiceConnections.id AND Material IN ('1629263231086', '1629263060281')) AS Lights"),
                'LaborCharge',
                'BOHECOShare'
            )
            ->orderBy('ORDate')
            ->orderBy('ServiceAccountName')
            ->get();

        return view('/electricians/housewiring_labor', [
            'data' => $data,
        ]);
    }

    public function downloadHousewiringLabor($month, $term, $year, $office) {
        if ($month != null && $year != null && $term != null) {
            if ($term == '1') {
                $from = $year . '-' . $month . '-01';
                $to = $year . '-' . $month . '-15';
            } elseif ($term == '2') {
                $from = $year . '-' . $month . '-16';
                $to = date('Y-m-d', strtotime('last day of ' . $from));
            } else {
                $from = '1997-01-01';
                $to = '1997-01-01';
            }
        } else {
            $from = '1997-01-01';
            $to = '1997-01-01';
        }

        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionTotalPayments', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId')
            ->whereRaw("CRM_ServiceConnections.ORDate IS NOT NULL AND (ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND ElectricianAcredited='Yes' 
                AND (CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No')")
            ->select(
                'CRM_ServiceConnections.id',
                'ORDate',
                'ORNumber',
                'ServiceAccountName',
                'Sitio',
                'CRM_Barangays.Barangay',
                'CRM_Towns.Town',
                'ElectricianName',
                DB::raw("(SELECT SUM(TRY_CAST(Quantity AS INTEGER)) FROM CRM_ServiceConnectionMaterialPayments WHERE ServiceConnectionId=CRM_ServiceConnections.id AND Material IN ('1659509010096', '1659509088407')) AS Breakers"),
                DB::raw("(SELECT SUM(TRY_CAST(Quantity AS INTEGER)) FROM CRM_ServiceConnectionMaterialPayments WHERE ServiceConnectionId=CRM_ServiceConnections.id AND Material IN ('1629263248908', '1629263170665')) AS Outlets"),
                DB::raw("(SELECT SUM(TRY_CAST(Quantity AS INTEGER)) FROM CRM_ServiceConnectionMaterialPayments WHERE ServiceConnectionId=CRM_ServiceConnections.id AND Material IN ('1629263231086', '1629263060281')) AS Lights"),
                'LaborCharge',
                'BOHECOShare'
            )
            ->orderBy('ORDate')
            ->orderBy('ServiceAccountName')
            ->get();

        $arr = [];
        $i=1;
        foreach ($data as $item) {
            array_push($arr, [
                'No' => $i,
                'ORDate' => $item->ORDate,
                'ORNumber' => $item->ORNumber,
                'ServiceAccountName' => $item->ServiceAccountName,
                'Address' => ServiceConnections::getAddress($item),
                'ElecName' => strtoupper($item->ElectricianName),
                'ES' => $item->Breakers,
                'LO' => $item->Outlets,
                'CO' => $item->Lights,
                'BOHECO I Share' => is_numeric($item->BOHECOShare) ? number_format($item->BOHECOShare, 2) : $item->BOHECOShare,
                'Labor' => is_numeric($item->LaborCharge) ? number_format($item->LaborCharge, 2) : $item->LaborCharge,
                'Total' => number_format((is_numeric($item->LaborCharge) ? round($item->LaborCharge, 2) : 0) + (is_numeric($item->BOHECOShare) ? round($item->BOHECOShare, 2) : 0), 2),
            ]);
            $i++;
        }

        $headers = [
            '#',
            'OR Date',
            'OR Number',
            'Service Account Name',
            'Address',
            'Electrician',
            'ES',
            'LO',
            'CO',
            'BOHECO I Share',
            'Labor Charge',
            'Total',
        ];

        $export = new DynamicExport($arr, $headers, null, 'Housewiring Labor Data for ' . date('F d, Y', strtotime($from)) . ' - ' . date('F d, Y', strtotime($to)));

        return Excel::download($export, 'Housewiring-Labor-Data'  . date('F d, Y', strtotime($from)) . '-' . date('F d, Y', strtotime($to)) . '.xlsx');
    }

    public function laborSummary(Request $request) {
        $office = $request['Office'];
        $month = $request['Month'];
        $term = $request['Term'];
        $year = $request['Year'];

        if ($month != null && $year != null && $term != null) {
            if ($term == '1') {
                $from = $year . '-' . $month . '-01';
                $to = $year . '-' . $month . '-15';
            } elseif ($term == '2') {
                $from = $year . '-' . $month . '-16';
                $to = date('Y-m-d', strtotime('last day of ' . $from));
            } else {
                $from = '1997-01-01';
                $to = '1997-01-01';
            }
        } else {
            $from = '1997-01-01';
            $to = '1997-01-01';
        }

        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionTotalPayments', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId')
            ->whereRaw("CRM_ServiceConnections.ORDate IS NOT NULL AND (ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND ElectricianAcredited='Yes' 
                AND (CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No')")
            ->select(
                'ElectricianName',
                DB::raw("COUNT(CRM_ServiceConnections.id) AS ConsumerCount"),
                DB::raw("SUM(TRY_CAST(LaborCharge AS DECIMAL(15,2))) AS LaborCharge")
            )
            ->groupBy('ElectricianId', 'ElectricianName')
            ->orderBy('ElectricianName')
            ->get();

        return view('/electricians/labor_summary', [
            'data' => $data, 
        ]);
    }

    public function downloadLaborShare($month, $term, $year, $office) {
        if ($month != null && $year != null && $term != null) {
            if ($term == '1') {
                $from = $year . '-' . $month . '-01';
                $to = $year . '-' . $month . '-15';
            } elseif ($term == '2') {
                $from = $year . '-' . $month . '-16';
                $to = date('Y-m-d', strtotime('last day of ' . $from));
            } else {
                $from = '1997-01-01';
                $to = '1997-01-01';
            }
        } else {
            $from = '1997-01-01';
            $to = '1997-01-01';
        }

        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionTotalPayments', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId')
            ->leftJoin('CRM_Electricians', 'CRM_ServiceConnections.ElectricianId', '=', 'CRM_Electricians.id')
            ->whereRaw("CRM_ServiceConnections.ORDate IS NOT NULL AND (ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND ElectricianAcredited='Yes' 
                AND (CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No')")
            ->select(
                'ElectricianName',
                DB::raw("COUNT(CRM_ServiceConnections.id) AS ConsumerCount"),
                DB::raw("SUM(TRY_CAST(LaborCharge AS DECIMAL(15,2))) AS LaborCharge"),
                'BankNumber'
            )
            ->groupBy('ElectricianId', 'ElectricianName', 'BankNumber')
            ->orderBy('ElectricianName')
            ->get();

        $arr = [];
        $i=1;
        foreach ($data as $item) {
            array_push($arr, [
                'No' => $i,
                'ElecName' => strtoupper($item->ElectricianName),
                'Count' => $item->ConsumerCount,
                'Labor' => is_numeric($item->LaborCharge) ? number_format($item->LaborCharge, 2) : $item->LaborCharge,
                'BankNumber' => $item->BankNumber,
                'Signature' => ''
            ]);
            $i++;
        }

        $headers = [
            '#',
            'Electrician',
            'No. of Applications',
            'Labor Charge',
            'Pitakard No.',
            'Signature'
        ];

        $export = new DynamicExport($arr, $headers, null, 'Labor Share Summary for ' . date('F d, Y', strtotime($from)) . ' - ' . date('F d, Y', strtotime($to)));

        return Excel::download($export, 'Labor-Share-Summary'  . date('F d, Y', strtotime($from)) . '-' . date('F d, Y', strtotime($to)) . '.xlsx');
    }

    public function printLaborShare($month, $term, $year, $office) {
        if ($month != null && $year != null && $term != null) {
            if ($term == '1') {
                $from = $year . '-' . $month . '-01';
                $to = $year . '-' . $month . '-15';
            } elseif ($term == '2') {
                $from = $year . '-' . $month . '-16';
                $to = date('Y-m-d', strtotime('last day of ' . $from));
            } else {
                $from = '1997-01-01';
                $to = '1997-01-01';
            }
        } else {
            $from = '1997-01-01';
            $to = '1997-01-01';
        }

        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionTotalPayments', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId')
            ->leftJoin('CRM_Electricians', 'CRM_ServiceConnections.ElectricianId', '=', 'CRM_Electricians.id')
            ->whereRaw("CRM_ServiceConnections.ORDate IS NOT NULL AND (ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND ElectricianAcredited='Yes' 
                AND (CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No') AND ElectricianId IS NOT NULL")
            ->select(
                'ElectricianName',
                DB::raw("COUNT(CRM_ServiceConnections.id) AS ConsumerCount"),
                DB::raw("SUM(TRY_CAST(LaborCharge AS DECIMAL(15,2))) AS LaborCharge"),
                'BankNumber'
            )
            ->groupBy('ElectricianId', 'ElectricianName', 'BankNumber')
            ->orderBy('ElectricianName')
            ->get();

        return view('/electricians/print_labor_summary', [
            'data' => $data,
            'month' => $month,
            'term' => $term,
            'year' => $year,
            'office' => $office,
            'from' => $from,
            'to' => $to
        ]);
    }
}
