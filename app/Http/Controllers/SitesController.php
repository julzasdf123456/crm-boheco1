<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSitesRequest;
use App\Http\Requests\UpdateSitesRequest;
use App\Repositories\SitesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SitesController extends AppBaseController
{
    /** @var SitesRepository $sitesRepository*/
    private $sitesRepository;

    public function __construct(SitesRepository $sitesRepo)
    {
        $this->middleware('auth');
        $this->sitesRepository = $sitesRepo;
    }

    /**
     * Display a listing of the Sites.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $sites = $this->sitesRepository->all();

        return view('sites.index')
            ->with('sites', $sites);
    }

    /**
     * Show the form for creating a new Sites.
     *
     * @return Response
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Store a newly created Sites in storage.
     *
     * @param CreateSitesRequest $request
     *
     * @return Response
     */
    public function store(CreateSitesRequest $request)
    {
        $input = $request->all();

        $sites = $this->sitesRepository->create($input);

        Flash::success('Sites saved successfully.');

        return redirect(route('sites.index'));
    }

    /**
     * Display the specified Sites.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sites = $this->sitesRepository->find($id);

        if (empty($sites)) {
            Flash::error('Sites not found');

            return redirect(route('sites.index'));
        }

        return view('sites.show')->with('sites', $sites);
    }

    /**
     * Show the form for editing the specified Sites.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sites = $this->sitesRepository->find($id);

        if (empty($sites)) {
            Flash::error('Sites not found');

            return redirect(route('sites.index'));
        }

        return view('sites.edit')->with('sites', $sites);
    }

    /**
     * Update the specified Sites in storage.
     *
     * @param int $id
     * @param UpdateSitesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSitesRequest $request)
    {
        $sites = $this->sitesRepository->find($id);

        if (empty($sites)) {
            Flash::error('Sites not found');

            return redirect(route('sites.index'));
        }

        $sites = $this->sitesRepository->update($request->all(), $id);

        Flash::success('Sites updated successfully.');

        return redirect(route('sites.index'));
    }

    /**
     * Remove the specified Sites from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $sites = $this->sitesRepository->find($id);

        if (empty($sites)) {
            Flash::error('Sites not found');

            return redirect(route('sites.index'));
        }

        $this->sitesRepository->delete($id);

        Flash::success('Sites deleted successfully.');

        return redirect(route('sites.index'));
    }
}
