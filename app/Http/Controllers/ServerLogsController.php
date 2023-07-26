<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServerLogsRequest;
use App\Http\Requests\UpdateServerLogsRequest;
use App\Repositories\ServerLogsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServerLogsController extends AppBaseController
{
    /** @var ServerLogsRepository $serverLogsRepository*/
    private $serverLogsRepository;

    public function __construct(ServerLogsRepository $serverLogsRepo)
    {
        $this->middleware('auth');
        $this->serverLogsRepository = $serverLogsRepo;
    }

    /**
     * Display a listing of the ServerLogs.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serverLogs = $this->serverLogsRepository->all();

        return view('server_logs.index')
            ->with('serverLogs', $serverLogs);
    }

    /**
     * Show the form for creating a new ServerLogs.
     *
     * @return Response
     */
    public function create()
    {
        return view('server_logs.create');
    }

    /**
     * Store a newly created ServerLogs in storage.
     *
     * @param CreateServerLogsRequest $request
     *
     * @return Response
     */
    public function store(CreateServerLogsRequest $request)
    {
        $input = $request->all();

        $serverLogs = $this->serverLogsRepository->create($input);

        Flash::success('Server Logs saved successfully.');

        return redirect(route('serverLogs.index'));
    }

    /**
     * Display the specified ServerLogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serverLogs = $this->serverLogsRepository->find($id);

        if (empty($serverLogs)) {
            Flash::error('Server Logs not found');

            return redirect(route('serverLogs.index'));
        }

        return view('server_logs.show')->with('serverLogs', $serverLogs);
    }

    /**
     * Show the form for editing the specified ServerLogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serverLogs = $this->serverLogsRepository->find($id);

        if (empty($serverLogs)) {
            Flash::error('Server Logs not found');

            return redirect(route('serverLogs.index'));
        }

        return view('server_logs.edit')->with('serverLogs', $serverLogs);
    }

    /**
     * Update the specified ServerLogs in storage.
     *
     * @param int $id
     * @param UpdateServerLogsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServerLogsRequest $request)
    {
        $serverLogs = $this->serverLogsRepository->find($id);

        if (empty($serverLogs)) {
            Flash::error('Server Logs not found');

            return redirect(route('serverLogs.index'));
        }

        $serverLogs = $this->serverLogsRepository->update($request->all(), $id);

        Flash::success('Server Logs updated successfully.');

        return redirect(route('serverLogs.index'));
    }

    /**
     * Remove the specified ServerLogs from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serverLogs = $this->serverLogsRepository->find($id);

        if (empty($serverLogs)) {
            Flash::error('Server Logs not found');

            return redirect(route('serverLogs.index'));
        }

        $this->serverLogsRepository->delete($id);

        Flash::success('Server Logs deleted successfully.');

        return redirect(route('serverLogs.index'));
    }
}
