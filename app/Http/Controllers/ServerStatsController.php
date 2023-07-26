<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServerStatsRequest;
use App\Http\Requests\UpdateServerStatsRequest;
use App\Repositories\ServerStatsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServerStatsController extends AppBaseController
{
    /** @var ServerStatsRepository $serverStatsRepository*/
    private $serverStatsRepository;

    public function __construct(ServerStatsRepository $serverStatsRepo)
    {
        $this->middleware('auth');
        $this->serverStatsRepository = $serverStatsRepo;
    }

    /**
     * Display a listing of the ServerStats.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serverStats = $this->serverStatsRepository->all();

        return view('server_stats.index')
            ->with('serverStats', $serverStats);
    }

    /**
     * Show the form for creating a new ServerStats.
     *
     * @return Response
     */
    public function create()
    {
        return view('server_stats.create');
    }

    /**
     * Store a newly created ServerStats in storage.
     *
     * @param CreateServerStatsRequest $request
     *
     * @return Response
     */
    public function store(CreateServerStatsRequest $request)
    {
        $input = $request->all();

        $serverStats = $this->serverStatsRepository->create($input);

        Flash::success('Server Stats saved successfully.');

        return redirect(route('serverStats.index'));
    }

    /**
     * Display the specified ServerStats.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serverStats = $this->serverStatsRepository->find($id);

        if (empty($serverStats)) {
            Flash::error('Server Stats not found');

            return redirect(route('serverStats.index'));
        }

        return view('server_stats.show')->with('serverStats', $serverStats);
    }

    /**
     * Show the form for editing the specified ServerStats.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serverStats = $this->serverStatsRepository->find($id);

        if (empty($serverStats)) {
            Flash::error('Server Stats not found');

            return redirect(route('serverStats.index'));
        }

        return view('server_stats.edit')->with('serverStats', $serverStats);
    }

    /**
     * Update the specified ServerStats in storage.
     *
     * @param int $id
     * @param UpdateServerStatsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServerStatsRequest $request)
    {
        $serverStats = $this->serverStatsRepository->find($id);

        if (empty($serverStats)) {
            Flash::error('Server Stats not found');

            return redirect(route('serverStats.index'));
        }

        $serverStats = $this->serverStatsRepository->update($request->all(), $id);

        Flash::success('Server Stats updated successfully.');

        return redirect(route('serverStats.index'));
    }

    /**
     * Remove the specified ServerStats from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serverStats = $this->serverStatsRepository->find($id);

        if (empty($serverStats)) {
            Flash::error('Server Stats not found');

            return redirect(route('serverStats.index'));
        }

        $this->serverStatsRepository->delete($id);

        Flash::success('Server Stats deleted successfully.');

        return redirect(route('serverStats.index'));
    }
}
