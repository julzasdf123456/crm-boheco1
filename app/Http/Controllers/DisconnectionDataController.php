<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDisconnectionDataRequest;
use App\Http\Requests\UpdateDisconnectionDataRequest;
use App\Repositories\DisconnectionDataRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DisconnectionDataController extends AppBaseController
{
    /** @var  DisconnectionDataRepository */
    private $disconnectionDataRepository;

    public function __construct(DisconnectionDataRepository $disconnectionDataRepo)
    {
        $this->middleware('auth');
        $this->disconnectionDataRepository = $disconnectionDataRepo;
    }

    /**
     * Display a listing of the DisconnectionData.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $disconnectionDatas = $this->disconnectionDataRepository->all();

        return view('disconnection_datas.index')
            ->with('disconnectionDatas', $disconnectionDatas);
    }

    /**
     * Show the form for creating a new DisconnectionData.
     *
     * @return Response
     */
    public function create()
    {
        return view('disconnection_datas.create');
    }

    /**
     * Store a newly created DisconnectionData in storage.
     *
     * @param CreateDisconnectionDataRequest $request
     *
     * @return Response
     */
    public function store(CreateDisconnectionDataRequest $request)
    {
        $input = $request->all();

        $disconnectionData = $this->disconnectionDataRepository->create($input);

        Flash::success('Disconnection Data saved successfully.');

        return redirect(route('disconnectionDatas.index'));
    }

    /**
     * Display the specified DisconnectionData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        return view('disconnection_datas.show')->with('disconnectionData', $disconnectionData);
    }

    /**
     * Show the form for editing the specified DisconnectionData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        return view('disconnection_datas.edit')->with('disconnectionData', $disconnectionData);
    }

    /**
     * Update the specified DisconnectionData in storage.
     *
     * @param int $id
     * @param UpdateDisconnectionDataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDisconnectionDataRequest $request)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        $disconnectionData = $this->disconnectionDataRepository->update($request->all(), $id);

        Flash::success('Disconnection Data updated successfully.');

        return redirect(route('disconnectionDatas.index'));
    }

    /**
     * Remove the specified DisconnectionData from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $disconnectionData = $this->disconnectionDataRepository->find($id);

        if (empty($disconnectionData)) {
            Flash::error('Disconnection Data not found');

            return redirect(route('disconnectionDatas.index'));
        }

        $this->disconnectionDataRepository->delete($id);

        Flash::success('Disconnection Data deleted successfully.');

        return redirect(route('disconnectionDatas.index'));
    }
}