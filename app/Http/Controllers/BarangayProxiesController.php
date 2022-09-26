<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBarangayProxiesRequest;
use App\Http\Requests\UpdateBarangayProxiesRequest;
use App\Repositories\BarangayProxiesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BarangayProxiesController extends AppBaseController
{
    /** @var  BarangayProxiesRepository */
    private $barangayProxiesRepository;

    public function __construct(BarangayProxiesRepository $barangayProxiesRepo)
    {
        $this->barangayProxiesRepository = $barangayProxiesRepo;
    }

    /**
     * Display a listing of the BarangayProxies.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $barangayProxies = $this->barangayProxiesRepository->all();

        return view('barangay_proxies.index')
            ->with('barangayProxies', $barangayProxies);
    }

    /**
     * Show the form for creating a new BarangayProxies.
     *
     * @return Response
     */
    public function create()
    {
        return view('barangay_proxies.create');
    }

    /**
     * Store a newly created BarangayProxies in storage.
     *
     * @param CreateBarangayProxiesRequest $request
     *
     * @return Response
     */
    public function store(CreateBarangayProxiesRequest $request)
    {
        $input = $request->all();

        $barangayProxies = $this->barangayProxiesRepository->create($input);

        Flash::success('Barangay Proxies saved successfully.');

        return redirect(route('barangayProxies.index'));
    }

    /**
     * Display the specified BarangayProxies.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $barangayProxies = $this->barangayProxiesRepository->find($id);

        if (empty($barangayProxies)) {
            Flash::error('Barangay Proxies not found');

            return redirect(route('barangayProxies.index'));
        }

        return view('barangay_proxies.show')->with('barangayProxies', $barangayProxies);
    }

    /**
     * Show the form for editing the specified BarangayProxies.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $barangayProxies = $this->barangayProxiesRepository->find($id);

        if (empty($barangayProxies)) {
            Flash::error('Barangay Proxies not found');

            return redirect(route('barangayProxies.index'));
        }

        return view('barangay_proxies.edit')->with('barangayProxies', $barangayProxies);
    }

    /**
     * Update the specified BarangayProxies in storage.
     *
     * @param int $id
     * @param UpdateBarangayProxiesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBarangayProxiesRequest $request)
    {
        $barangayProxies = $this->barangayProxiesRepository->find($id);

        if (empty($barangayProxies)) {
            Flash::error('Barangay Proxies not found');

            return redirect(route('barangayProxies.index'));
        }

        $barangayProxies = $this->barangayProxiesRepository->update($request->all(), $id);

        Flash::success('Barangay Proxies updated successfully.');

        return redirect(route('barangayProxies.index'));
    }

    /**
     * Remove the specified BarangayProxies from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $barangayProxies = $this->barangayProxiesRepository->find($id);

        if (empty($barangayProxies)) {
            Flash::error('Barangay Proxies not found');

            return redirect(route('barangayProxies.index'));
        }

        $this->barangayProxiesRepository->delete($id);

        Flash::success('Barangay Proxies deleted successfully.');

        return redirect(route('barangayProxies.index'));
    }
}
