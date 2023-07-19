<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChangeMeterRequest;
use App\Http\Requests\UpdateChangeMeterRequest;
use App\Repositories\ChangeMeterRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChangeMeterController extends AppBaseController
{
    /** @var ChangeMeterRepository $changeMeterRepository*/
    private $changeMeterRepository;

    public function __construct(ChangeMeterRepository $changeMeterRepo)
    {
        $this->middleware('auth');
        $this->changeMeterRepository = $changeMeterRepo;
    }

    /**
     * Display a listing of the ChangeMeter.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $changeMeters = $this->changeMeterRepository->all();

        return view('change_meters.index')
            ->with('changeMeters', $changeMeters);
    }

    /**
     * Show the form for creating a new ChangeMeter.
     *
     * @return Response
     */
    public function create()
    {
        return view('change_meters.create');
    }

    /**
     * Store a newly created ChangeMeter in storage.
     *
     * @param CreateChangeMeterRequest $request
     *
     * @return Response
     */
    public function store(CreateChangeMeterRequest $request)
    {
        $input = $request->all();

        $changeMeter = $this->changeMeterRepository->create($input);

        Flash::success('Change Meter saved successfully.');

        return redirect(route('changeMeters.index'));
    }

    /**
     * Display the specified ChangeMeter.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $changeMeter = $this->changeMeterRepository->find($id);

        if (empty($changeMeter)) {
            Flash::error('Change Meter not found');

            return redirect(route('changeMeters.index'));
        }

        return view('change_meters.show')->with('changeMeter', $changeMeter);
    }

    /**
     * Show the form for editing the specified ChangeMeter.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $changeMeter = $this->changeMeterRepository->find($id);

        if (empty($changeMeter)) {
            Flash::error('Change Meter not found');

            return redirect(route('changeMeters.index'));
        }

        return view('change_meters.edit')->with('changeMeter', $changeMeter);
    }

    /**
     * Update the specified ChangeMeter in storage.
     *
     * @param int $id
     * @param UpdateChangeMeterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChangeMeterRequest $request)
    {
        $changeMeter = $this->changeMeterRepository->find($id);

        if (empty($changeMeter)) {
            Flash::error('Change Meter not found');

            return redirect(route('changeMeters.index'));
        }

        $changeMeter = $this->changeMeterRepository->update($request->all(), $id);

        Flash::success('Change Meter updated successfully.');

        return redirect(route('changeMeters.index'));
    }

    /**
     * Remove the specified ChangeMeter from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $changeMeter = $this->changeMeterRepository->find($id);

        if (empty($changeMeter)) {
            Flash::error('Change Meter not found');

            return redirect(route('changeMeters.index'));
        }

        $this->changeMeterRepository->delete($id);

        Flash::success('Change Meter deleted successfully.');

        return redirect(route('changeMeters.index'));
    }
}
