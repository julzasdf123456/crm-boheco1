<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnbundledRatesRequest;
use App\Http\Requests\UpdateUnbundledRatesRequest;
use App\Repositories\UnbundledRatesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class UnbundledRatesController extends AppBaseController
{
    /** @var  UnbundledRatesRepository */
    private $unbundledRatesRepository;

    public function __construct(UnbundledRatesRepository $unbundledRatesRepo)
    {
        $this->middleware('auth');
        $this->unbundledRatesRepository = $unbundledRatesRepo;
    }

    /**
     * Display a listing of the UnbundledRates.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $unbundledRates = $this->unbundledRatesRepository->all();

        return view('unbundled_rates.index')
            ->with('unbundledRates', $unbundledRates);
    }

    /**
     * Show the form for creating a new UnbundledRates.
     *
     * @return Response
     */
    public function create()
    {
        return view('unbundled_rates.create');
    }

    /**
     * Store a newly created UnbundledRates in storage.
     *
     * @param CreateUnbundledRatesRequest $request
     *
     * @return Response
     */
    public function store(CreateUnbundledRatesRequest $request)
    {
        $input = $request->all();

        $unbundledRates = $this->unbundledRatesRepository->create($input);

        Flash::success('Unbundled Rates saved successfully.');

        return redirect(route('unbundledRates.index'));
    }

    /**
     * Display the specified UnbundledRates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $unbundledRates = $this->unbundledRatesRepository->find($id);

        if (empty($unbundledRates)) {
            Flash::error('Unbundled Rates not found');

            return redirect(route('unbundledRates.index'));
        }

        return view('unbundled_rates.show')->with('unbundledRates', $unbundledRates);
    }

    /**
     * Show the form for editing the specified UnbundledRates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $unbundledRates = $this->unbundledRatesRepository->find($id);

        if (empty($unbundledRates)) {
            Flash::error('Unbundled Rates not found');

            return redirect(route('unbundledRates.index'));
        }

        return view('unbundled_rates.edit')->with('unbundledRates', $unbundledRates);
    }

    /**
     * Update the specified UnbundledRates in storage.
     *
     * @param int $id
     * @param UpdateUnbundledRatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUnbundledRatesRequest $request)
    {
        $unbundledRates = $this->unbundledRatesRepository->find($id);

        if (empty($unbundledRates)) {
            Flash::error('Unbundled Rates not found');

            return redirect(route('unbundledRates.index'));
        }

        $unbundledRates = $this->unbundledRatesRepository->update($request->all(), $id);

        Flash::success('Unbundled Rates updated successfully.');

        return redirect(route('unbundledRates.index'));
    }

    /**
     * Remove the specified UnbundledRates from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $unbundledRates = $this->unbundledRatesRepository->find($id);

        if (empty($unbundledRates)) {
            Flash::error('Unbundled Rates not found');

            return redirect(route('unbundledRates.index'));
        }

        $this->unbundledRatesRepository->delete($id);

        Flash::success('Unbundled Rates deleted successfully.');

        return redirect(route('unbundledRates.index'));
    }
}
