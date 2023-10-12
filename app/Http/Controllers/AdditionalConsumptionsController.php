<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdditionalConsumptionsRequest;
use App\Http\Requests\UpdateAdditionalConsumptionsRequest;
use App\Repositories\AdditionalConsumptionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AdditionalConsumptionsController extends AppBaseController
{
    /** @var AdditionalConsumptionsRepository $additionalConsumptionsRepository*/
    private $additionalConsumptionsRepository;

    public function __construct(AdditionalConsumptionsRepository $additionalConsumptionsRepo)
    {
        $this->middleware('auth');
        $this->additionalConsumptionsRepository = $additionalConsumptionsRepo;
    }

    /**
     * Display a listing of the AdditionalConsumptions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $additionalConsumptions = $this->additionalConsumptionsRepository->all();

        return view('additional_consumptions.index')
            ->with('additionalConsumptions', $additionalConsumptions);
    }

    /**
     * Show the form for creating a new AdditionalConsumptions.
     *
     * @return Response
     */
    public function create()
    {
        return view('additional_consumptions.create');
    }

    /**
     * Store a newly created AdditionalConsumptions in storage.
     *
     * @param CreateAdditionalConsumptionsRequest $request
     *
     * @return Response
     */
    public function store(CreateAdditionalConsumptionsRequest $request)
    {
        $input = $request->all();

        $additionalConsumptions = $this->additionalConsumptionsRepository->create($input);

        Flash::success('Additional Consumptions saved successfully.');

        return redirect(route('additionalConsumptions.index'));
    }

    /**
     * Display the specified AdditionalConsumptions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $additionalConsumptions = $this->additionalConsumptionsRepository->find($id);

        if (empty($additionalConsumptions)) {
            Flash::error('Additional Consumptions not found');

            return redirect(route('additionalConsumptions.index'));
        }

        return view('additional_consumptions.show')->with('additionalConsumptions', $additionalConsumptions);
    }

    /**
     * Show the form for editing the specified AdditionalConsumptions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $additionalConsumptions = $this->additionalConsumptionsRepository->find($id);

        if (empty($additionalConsumptions)) {
            Flash::error('Additional Consumptions not found');

            return redirect(route('additionalConsumptions.index'));
        }

        return view('additional_consumptions.edit')->with('additionalConsumptions', $additionalConsumptions);
    }

    /**
     * Update the specified AdditionalConsumptions in storage.
     *
     * @param int $id
     * @param UpdateAdditionalConsumptionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdditionalConsumptionsRequest $request)
    {
        $additionalConsumptions = $this->additionalConsumptionsRepository->find($id);

        if (empty($additionalConsumptions)) {
            Flash::error('Additional Consumptions not found');

            return redirect(route('additionalConsumptions.index'));
        }

        $additionalConsumptions = $this->additionalConsumptionsRepository->update($request->all(), $id);

        Flash::success('Additional Consumptions updated successfully.');

        return redirect(route('additionalConsumptions.index'));
    }

    /**
     * Remove the specified AdditionalConsumptions from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $additionalConsumptions = $this->additionalConsumptionsRepository->find($id);

        if (empty($additionalConsumptions)) {
            Flash::error('Additional Consumptions not found');

            return redirect(route('additionalConsumptions.index'));
        }

        $this->additionalConsumptionsRepository->delete($id);

        Flash::success('Additional Consumptions deleted successfully.');

        return redirect(route('additionalConsumptions.index'));
    }
}
