<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMetersRequest;
use App\Http\Requests\UpdateMetersRequest;
use App\Repositories\MetersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Meters;
use App\Models\ServiceConnections;
use App\Models\AccountMaster;
use Flash;
use Response;

class MetersController extends AppBaseController
{
    /** @var  MetersRepository */
    private $metersRepository;

    public function __construct(MetersRepository $metersRepo)
    {
        $this->middleware('auth');
        $this->metersRepository = $metersRepo;
    }

    /**
     * Display a listing of the Meters.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $meters = $this->metersRepository->all();

        return view('meters.index')
            ->with('meters', $meters);
    }

    /**
     * Show the form for creating a new Meters.
     *
     * @return Response
     */
    public function create()
    {
        return view('meters.create');
    }

    /**
     * Store a newly created Meters in storage.
     *
     * @param CreateMetersRequest $request
     *
     * @return Response
     */
    public function store(CreateMetersRequest $request)
    {
        $input = $request->all();
        $scId = $input['ServiceConnectionId'];
        $acctNo = $input['AccountNumber'];

        $serviceConnection = ServiceConnections::find($scId);

        $meter = Meters::find($input['MeterNumber']);
        if ($meter != null) {
            Flash::error('Meter number already exists!');

            return redirect(route('accountMasters.account-migration-step-two', [$acctNo, $scId]));
        } else {
            $meters = $this->metersRepository->create($input);

            $account = AccountMaster::find($acctNo);
            if ($account != null) {
                $account->MeterNumber = $input['MeterNumber'];
                $account->save();
            }

            Flash::success('Meters saved successfully.');

            return redirect(route('accountMasters.account-migration-step-three', [$acctNo, $scId]));
        }        
    }

    /**
     * Display the specified Meters.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $meters = $this->metersRepository->find($id);

        if (empty($meters)) {
            Flash::error('Meters not found');

            return redirect(route('meters.index'));
        }

        return view('meters.show')->with('meters', $meters);
    }

    /**
     * Show the form for editing the specified Meters.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $meters = $this->metersRepository->find($id);

        if (empty($meters)) {
            Flash::error('Meters not found');

            return redirect(route('meters.index'));
        }

        return view('meters.edit')->with('meters', $meters);
    }

    /**
     * Update the specified Meters in storage.
     *
     * @param int $id
     * @param UpdateMetersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMetersRequest $request)
    {
        $meters = $this->metersRepository->find($id);

        if (empty($meters)) {
            Flash::error('Meters not found');

            return redirect(route('meters.index'));
        }

        $meters = $this->metersRepository->update($request->all(), $id);

        Flash::success('Meters updated successfully.');

        return redirect(route('meters.index'));
    }

    /**
     * Remove the specified Meters from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $meters = $this->metersRepository->find($id);

        if (empty($meters)) {
            Flash::error('Meters not found');

            return redirect(route('meters.index'));
        }

        $this->metersRepository->delete($id);

        Flash::success('Meters deleted successfully.');

        return redirect(route('meters.index'));
    }
}
