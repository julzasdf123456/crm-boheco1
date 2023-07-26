<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServersRequest;
use App\Http\Requests\UpdateServersRequest;
use App\Repositories\ServersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServersController extends AppBaseController
{
    /** @var ServersRepository $serversRepository*/
    private $serversRepository;

    public function __construct(ServersRepository $serversRepo)
    {
        $this->middleware('auth');
        $this->serversRepository = $serversRepo;
    }

    /**
     * Display a listing of the Servers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $servers = $this->serversRepository->all();

        return view('servers.index')
            ->with('servers', $servers);
    }

    /**
     * Show the form for creating a new Servers.
     *
     * @return Response
     */
    public function create()
    {
        return view('servers.create');
    }

    /**
     * Store a newly created Servers in storage.
     *
     * @param CreateServersRequest $request
     *
     * @return Response
     */
    public function store(CreateServersRequest $request)
    {
        $input = $request->all();

        $servers = $this->serversRepository->create($input);

        Flash::success('Servers saved successfully.');

        return redirect(route('servers.index'));
    }

    /**
     * Display the specified Servers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $servers = $this->serversRepository->find($id);

        if (empty($servers)) {
            Flash::error('Servers not found');

            return redirect(route('servers.index'));
        }

        return view('servers.show')->with('servers', $servers);
    }

    /**
     * Show the form for editing the specified Servers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $servers = $this->serversRepository->find($id);

        if (empty($servers)) {
            Flash::error('Servers not found');

            return redirect(route('servers.index'));
        }

        return view('servers.edit')->with('servers', $servers);
    }

    /**
     * Update the specified Servers in storage.
     *
     * @param int $id
     * @param UpdateServersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServersRequest $request)
    {
        $servers = $this->serversRepository->find($id);

        if (empty($servers)) {
            Flash::error('Servers not found');

            return redirect(route('servers.index'));
        }

        $servers = $this->serversRepository->update($request->all(), $id);

        Flash::success('Servers updated successfully.');

        return redirect(route('servers.index'));
    }

    /**
     * Remove the specified Servers from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $servers = $this->serversRepository->find($id);

        if (empty($servers)) {
            Flash::error('Servers not found');

            return redirect(route('servers.index'));
        }

        $this->serversRepository->delete($id);

        Flash::success('Servers deleted successfully.');

        return redirect(route('servers.index'));
    }
}
