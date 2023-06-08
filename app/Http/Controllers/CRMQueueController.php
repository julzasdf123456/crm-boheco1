<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCRMQueueRequest;
use App\Http\Requests\UpdateCRMQueueRequest;
use App\Repositories\CRMQueueRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CRMQueueController extends AppBaseController
{
    /** @var  CRMQueueRepository */
    private $cRMQueueRepository;

    public function __construct(CRMQueueRepository $cRMQueueRepo)
    {
        $this->middleware('auth');
        $this->cRMQueueRepository = $cRMQueueRepo;
    }

    /**
     * Display a listing of the CRMQueue.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $cRMQueues = $this->cRMQueueRepository->all();

        return view('c_r_m_queues.index')
            ->with('cRMQueues', $cRMQueues);
    }

    /**
     * Show the form for creating a new CRMQueue.
     *
     * @return Response
     */
    public function create()
    {
        return view('c_r_m_queues.create');
    }

    /**
     * Store a newly created CRMQueue in storage.
     *
     * @param CreateCRMQueueRequest $request
     *
     * @return Response
     */
    public function store(CreateCRMQueueRequest $request)
    {
        $input = $request->all();

        $cRMQueue = $this->cRMQueueRepository->create($input);

        Flash::success('C R M Queue saved successfully.');

        return redirect(route('cRMQueues.index'));
    }

    /**
     * Display the specified CRMQueue.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cRMQueue = $this->cRMQueueRepository->find($id);

        if (empty($cRMQueue)) {
            Flash::error('C R M Queue not found');

            return redirect(route('cRMQueues.index'));
        }

        return view('c_r_m_queues.show')->with('cRMQueue', $cRMQueue);
    }

    /**
     * Show the form for editing the specified CRMQueue.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cRMQueue = $this->cRMQueueRepository->find($id);

        if (empty($cRMQueue)) {
            Flash::error('C R M Queue not found');

            return redirect(route('cRMQueues.index'));
        }

        return view('c_r_m_queues.edit')->with('cRMQueue', $cRMQueue);
    }

    /**
     * Update the specified CRMQueue in storage.
     *
     * @param int $id
     * @param UpdateCRMQueueRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCRMQueueRequest $request)
    {
        $cRMQueue = $this->cRMQueueRepository->find($id);

        if (empty($cRMQueue)) {
            Flash::error('C R M Queue not found');

            return redirect(route('cRMQueues.index'));
        }

        $cRMQueue = $this->cRMQueueRepository->update($request->all(), $id);

        Flash::success('C R M Queue updated successfully.');

        return redirect(route('cRMQueues.index'));
    }

    /**
     * Remove the specified CRMQueue from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cRMQueue = $this->cRMQueueRepository->find($id);

        if (empty($cRMQueue)) {
            Flash::error('C R M Queue not found');

            return redirect(route('cRMQueues.index'));
        }

        $this->cRMQueueRepository->delete($id);

        Flash::success('C R M Queue deleted successfully.');

        return redirect(route('cRMQueues.index'));
    }
}
