<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTempReadingsRequest;
use App\Http\Requests\UpdateTempReadingsRequest;
use App\Repositories\TempReadingsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TempReadingsController extends AppBaseController
{
    /** @var TempReadingsRepository $tempReadingsRepository*/
    private $tempReadingsRepository;

    public function __construct(TempReadingsRepository $tempReadingsRepo)
    {
        $this->middleware('auth');
        $this->tempReadingsRepository = $tempReadingsRepo;
    }

    /**
     * Display a listing of the TempReadings.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tempReadings = $this->tempReadingsRepository->all();

        return view('temp_readings.index')
            ->with('tempReadings', $tempReadings);
    }

    /**
     * Show the form for creating a new TempReadings.
     *
     * @return Response
     */
    public function create()
    {
        return view('temp_readings.create');
    }

    /**
     * Store a newly created TempReadings in storage.
     *
     * @param CreateTempReadingsRequest $request
     *
     * @return Response
     */
    public function store(CreateTempReadingsRequest $request)
    {
        $input = $request->all();

        $tempReadings = $this->tempReadingsRepository->create($input);

        Flash::success('Temp Readings saved successfully.');

        return redirect(route('tempReadings.index'));
    }

    /**
     * Display the specified TempReadings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tempReadings = $this->tempReadingsRepository->find($id);

        if (empty($tempReadings)) {
            Flash::error('Temp Readings not found');

            return redirect(route('tempReadings.index'));
        }

        return view('temp_readings.show')->with('tempReadings', $tempReadings);
    }

    /**
     * Show the form for editing the specified TempReadings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tempReadings = $this->tempReadingsRepository->find($id);

        if (empty($tempReadings)) {
            Flash::error('Temp Readings not found');

            return redirect(route('tempReadings.index'));
        }

        return view('temp_readings.edit')->with('tempReadings', $tempReadings);
    }

    /**
     * Update the specified TempReadings in storage.
     *
     * @param int $id
     * @param UpdateTempReadingsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTempReadingsRequest $request)
    {
        $tempReadings = $this->tempReadingsRepository->find($id);

        if (empty($tempReadings)) {
            Flash::error('Temp Readings not found');

            return redirect(route('tempReadings.index'));
        }

        $tempReadings = $this->tempReadingsRepository->update($request->all(), $id);

        Flash::success('Temp Readings updated successfully.');

        return redirect(route('tempReadings.index'));
    }

    /**
     * Remove the specified TempReadings from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tempReadings = $this->tempReadingsRepository->find($id);

        if (empty($tempReadings)) {
            Flash::error('Temp Readings not found');

            return redirect(route('tempReadings.index'));
        }

        $this->tempReadingsRepository->delete($id);

        Flash::success('Temp Readings deleted successfully.');

        return redirect(route('tempReadings.index'));
    }
}
