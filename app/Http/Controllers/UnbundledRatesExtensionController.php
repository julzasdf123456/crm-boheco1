<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnbundledRatesExtensionRequest;
use App\Http\Requests\UpdateUnbundledRatesExtensionRequest;
use App\Repositories\UnbundledRatesExtensionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class UnbundledRatesExtensionController extends AppBaseController
{
    /** @var  UnbundledRatesExtensionRepository */
    private $unbundledRatesExtensionRepository;

    public function __construct(UnbundledRatesExtensionRepository $unbundledRatesExtensionRepo)
    {
        $this->middleware('auth');
        $this->unbundledRatesExtensionRepository = $unbundledRatesExtensionRepo;
    }

    /**
     * Display a listing of the UnbundledRatesExtension.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $unbundledRatesExtensions = $this->unbundledRatesExtensionRepository->all();

        return view('unbundled_rates_extensions.index')
            ->with('unbundledRatesExtensions', $unbundledRatesExtensions);
    }

    /**
     * Show the form for creating a new UnbundledRatesExtension.
     *
     * @return Response
     */
    public function create()
    {
        return view('unbundled_rates_extensions.create');
    }

    /**
     * Store a newly created UnbundledRatesExtension in storage.
     *
     * @param CreateUnbundledRatesExtensionRequest $request
     *
     * @return Response
     */
    public function store(CreateUnbundledRatesExtensionRequest $request)
    {
        $input = $request->all();

        $unbundledRatesExtension = $this->unbundledRatesExtensionRepository->create($input);

        Flash::success('Unbundled Rates Extension saved successfully.');

        return redirect(route('unbundledRatesExtensions.index'));
    }

    /**
     * Display the specified UnbundledRatesExtension.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $unbundledRatesExtension = $this->unbundledRatesExtensionRepository->find($id);

        if (empty($unbundledRatesExtension)) {
            Flash::error('Unbundled Rates Extension not found');

            return redirect(route('unbundledRatesExtensions.index'));
        }

        return view('unbundled_rates_extensions.show')->with('unbundledRatesExtension', $unbundledRatesExtension);
    }

    /**
     * Show the form for editing the specified UnbundledRatesExtension.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $unbundledRatesExtension = $this->unbundledRatesExtensionRepository->find($id);

        if (empty($unbundledRatesExtension)) {
            Flash::error('Unbundled Rates Extension not found');

            return redirect(route('unbundledRatesExtensions.index'));
        }

        return view('unbundled_rates_extensions.edit')->with('unbundledRatesExtension', $unbundledRatesExtension);
    }

    /**
     * Update the specified UnbundledRatesExtension in storage.
     *
     * @param int $id
     * @param UpdateUnbundledRatesExtensionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUnbundledRatesExtensionRequest $request)
    {
        $unbundledRatesExtension = $this->unbundledRatesExtensionRepository->find($id);

        if (empty($unbundledRatesExtension)) {
            Flash::error('Unbundled Rates Extension not found');

            return redirect(route('unbundledRatesExtensions.index'));
        }

        $unbundledRatesExtension = $this->unbundledRatesExtensionRepository->update($request->all(), $id);

        Flash::success('Unbundled Rates Extension updated successfully.');

        return redirect(route('unbundledRatesExtensions.index'));
    }

    /**
     * Remove the specified UnbundledRatesExtension from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $unbundledRatesExtension = $this->unbundledRatesExtensionRepository->find($id);

        if (empty($unbundledRatesExtension)) {
            Flash::error('Unbundled Rates Extension not found');

            return redirect(route('unbundledRatesExtensions.index'));
        }

        $this->unbundledRatesExtensionRepository->delete($id);

        Flash::success('Unbundled Rates Extension deleted successfully.');

        return redirect(route('unbundledRatesExtensions.index'));
    }
}
