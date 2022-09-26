<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateElectriciansRequest;
use App\Http\Requests\UpdateElectriciansRequest;
use App\Repositories\ElectriciansRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Electricians;
use Flash;
use Response;

class ElectriciansController extends AppBaseController
{
    /** @var  ElectriciansRepository */
    private $electriciansRepository;

    public function __construct(ElectriciansRepository $electriciansRepo)
    {
        $this->middleware('auth');
        $this->electriciansRepository = $electriciansRepo;
    }

    /**
     * Display a listing of the Electricians.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $electricians = $this->electriciansRepository->all();

        return view('electricians.index')
            ->with('electricians', $electricians);
    }

    /**
     * Show the form for creating a new Electricians.
     *
     * @return Response
     */
    public function create()
    {
        return view('electricians.create');
    }

    /**
     * Store a newly created Electricians in storage.
     *
     * @param CreateElectriciansRequest $request
     *
     * @return Response
     */
    public function store(CreateElectriciansRequest $request)
    {
        $input = $request->all();

        $electricians = $this->electriciansRepository->create($input);

        Flash::success('Electricians saved successfully.');

        return redirect(route('electricians.index'));
    }

    /**
     * Display the specified Electricians.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        return view('electricians.show')->with('electricians', $electricians);
    }

    /**
     * Show the form for editing the specified Electricians.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        return view('electricians.edit')->with('electricians', $electricians);
    }

    /**
     * Update the specified Electricians in storage.
     *
     * @param int $id
     * @param UpdateElectriciansRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateElectriciansRequest $request)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        $electricians = $this->electriciansRepository->update($request->all(), $id);

        Flash::success('Electricians updated successfully.');

        return redirect(route('electricians.index'));
    }

    /**
     * Remove the specified Electricians from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $electricians = $this->electriciansRepository->find($id);

        if (empty($electricians)) {
            Flash::error('Electricians not found');

            return redirect(route('electricians.index'));
        }

        $this->electriciansRepository->delete($id);

        Flash::success('Electricians deleted successfully.');

        return redirect(route('electricians.index'));
    }

    public function getElectricianAjax(Request $request) {
        return response()->json(Electricians::find($request['id']), 200);
    }
}
