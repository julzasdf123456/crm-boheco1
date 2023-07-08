<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDisconnectionRoutesRequest;
use App\Http\Requests\UpdateDisconnectionRoutesRequest;
use App\Repositories\DisconnectionRoutesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\DisconnectionSchedules;
use Flash;
use Response;

class DisconnectionRoutesController extends AppBaseController
{
    /** @var  DisconnectionRoutesRepository */
    private $disconnectionRoutesRepository;

    public function __construct(DisconnectionRoutesRepository $disconnectionRoutesRepo)
    {
        $this->middleware('auth');
        $this->disconnectionRoutesRepository = $disconnectionRoutesRepo;
    }

    /**
     * Display a listing of the DisconnectionRoutes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $disconnectionRoutes = $this->disconnectionRoutesRepository->all();

        return view('disconnection_routes.index')
            ->with('disconnectionRoutes', $disconnectionRoutes);
    }

    /**
     * Show the form for creating a new DisconnectionRoutes.
     *
     * @return Response
     */
    public function create()
    {
        return view('disconnection_routes.create');
    }

    /**
     * Store a newly created DisconnectionRoutes in storage.
     *
     * @param CreateDisconnectionRoutesRequest $request
     *
     * @return Response
     */
    public function store(CreateDisconnectionRoutesRequest $request)
    {
        $input = $request->all();

        $disconnectionRoutes = $this->disconnectionRoutesRepository->create($input);

        Flash::success('Disconnection Routes saved successfully.');

        return redirect(route('disconnectionRoutes.index'));
    }

    /**
     * Display the specified DisconnectionRoutes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $disconnectionRoutes = $this->disconnectionRoutesRepository->find($id);

        if (empty($disconnectionRoutes)) {
            Flash::error('Disconnection Routes not found');

            return redirect(route('disconnectionRoutes.index'));
        }

        return view('disconnection_routes.show')->with('disconnectionRoutes', $disconnectionRoutes);
    }

    /**
     * Show the form for editing the specified DisconnectionRoutes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $disconnectionRoutes = $this->disconnectionRoutesRepository->find($id);

        if (empty($disconnectionRoutes)) {
            Flash::error('Disconnection Routes not found');

            return redirect(route('disconnectionRoutes.index'));
        }

        return view('disconnection_routes.edit')->with('disconnectionRoutes', $disconnectionRoutes);
    }

    /**
     * Update the specified DisconnectionRoutes in storage.
     *
     * @param int $id
     * @param UpdateDisconnectionRoutesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDisconnectionRoutesRequest $request)
    {
        $disconnectionRoutes = $this->disconnectionRoutesRepository->find($id);

        if (empty($disconnectionRoutes)) {
            Flash::error('Disconnection Routes not found');

            return redirect(route('disconnectionRoutes.index'));
        }

        $disconnectionRoutes = $this->disconnectionRoutesRepository->update($request->all(), $id);

        $schedule = DisconnectionSchedules::find($disconnectionRoutes->ScheduleId);

        // Flash::success('Disconnection Routes updated successfully.');

        // return redirect(route('disconnectionRoutes.index'));
        return response()->json($schedule, 200);
    }

    /**
     * Remove the specified DisconnectionRoutes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $disconnectionRoutes = $this->disconnectionRoutesRepository->find($id);

        if (empty($disconnectionRoutes)) {
            Flash::error('Disconnection Routes not found');

            return redirect(route('disconnectionRoutes.index'));
        }

        $schedule = DisconnectionSchedules::find($disconnectionRoutes->ScheduleId);

        $this->disconnectionRoutesRepository->delete($id);

        // Flash::success('Disconnection Routes deleted successfully.');

        // return redirect(route('disconnectionRoutes.index'));
        return response()->json($schedule, 200);
    }
}
