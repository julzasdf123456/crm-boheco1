<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSignatoriesRequest;
use App\Http\Requests\UpdateSignatoriesRequest;
use App\Repositories\SignatoriesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SignatoriesController extends AppBaseController
{
    /** @var  SignatoriesRepository */
    private $signatoriesRepository;

    public function __construct(SignatoriesRepository $signatoriesRepo)
    {
        $this->middleware('auth');
        $this->signatoriesRepository = $signatoriesRepo;
    }

    /**
     * Display a listing of the Signatories.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $signatories = $this->signatoriesRepository->all();

        return view('signatories.index')
            ->with('signatories', $signatories);
    }

    /**
     * Show the form for creating a new Signatories.
     *
     * @return Response
     */
    public function create()
    {
        return view('signatories.create');
    }

    /**
     * Store a newly created Signatories in storage.
     *
     * @param CreateSignatoriesRequest $request
     *
     * @return Response
     */
    public function store(CreateSignatoriesRequest $request)
    {
        $input = $request->all();

        $signatories = $this->signatoriesRepository->create($input);

        Flash::success('Signatories saved successfully.');

        return redirect(route('signatories.index'));
    }

    /**
     * Display the specified Signatories.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        return view('signatories.show')->with('signatories', $signatories);
    }

    /**
     * Show the form for editing the specified Signatories.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        return view('signatories.edit')->with('signatories', $signatories);
    }

    /**
     * Update the specified Signatories in storage.
     *
     * @param int $id
     * @param UpdateSignatoriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSignatoriesRequest $request)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        $signatories = $this->signatoriesRepository->update($request->all(), $id);

        Flash::success('Signatories updated successfully.');

        return redirect(route('signatories.index'));
    }

    /**
     * Remove the specified Signatories from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        $this->signatoriesRepository->delete($id);

        Flash::success('Signatories deleted successfully.');

        return redirect(route('signatories.index'));
    }
}
