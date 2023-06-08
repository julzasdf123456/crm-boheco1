<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCRMDetailsRequest;
use App\Http\Requests\UpdateCRMDetailsRequest;
use App\Repositories\CRMDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CRMDetailsController extends AppBaseController
{
    /** @var  CRMDetailsRepository */
    private $cRMDetailsRepository;

    public function __construct(CRMDetailsRepository $cRMDetailsRepo)
    {
        $this->middleware('auth');
        $this->cRMDetailsRepository = $cRMDetailsRepo;
    }

    /**
     * Display a listing of the CRMDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $cRMDetails = $this->cRMDetailsRepository->all();

        return view('c_r_m_details.index')
            ->with('cRMDetails', $cRMDetails);
    }

    /**
     * Show the form for creating a new CRMDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('c_r_m_details.create');
    }

    /**
     * Store a newly created CRMDetails in storage.
     *
     * @param CreateCRMDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreateCRMDetailsRequest $request)
    {
        $input = $request->all();

        $cRMDetails = $this->cRMDetailsRepository->create($input);

        Flash::success('C R M Details saved successfully.');

        return redirect(route('cRMDetails.index'));
    }

    /**
     * Display the specified CRMDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cRMDetails = $this->cRMDetailsRepository->find($id);

        if (empty($cRMDetails)) {
            Flash::error('C R M Details not found');

            return redirect(route('cRMDetails.index'));
        }

        return view('c_r_m_details.show')->with('cRMDetails', $cRMDetails);
    }

    /**
     * Show the form for editing the specified CRMDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cRMDetails = $this->cRMDetailsRepository->find($id);

        if (empty($cRMDetails)) {
            Flash::error('C R M Details not found');

            return redirect(route('cRMDetails.index'));
        }

        return view('c_r_m_details.edit')->with('cRMDetails', $cRMDetails);
    }

    /**
     * Update the specified CRMDetails in storage.
     *
     * @param int $id
     * @param UpdateCRMDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCRMDetailsRequest $request)
    {
        $cRMDetails = $this->cRMDetailsRepository->find($id);

        if (empty($cRMDetails)) {
            Flash::error('C R M Details not found');

            return redirect(route('cRMDetails.index'));
        }

        $cRMDetails = $this->cRMDetailsRepository->update($request->all(), $id);

        Flash::success('C R M Details updated successfully.');

        return redirect(route('cRMDetails.index'));
    }

    /**
     * Remove the specified CRMDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cRMDetails = $this->cRMDetailsRepository->find($id);

        if (empty($cRMDetails)) {
            Flash::error('C R M Details not found');

            return redirect(route('cRMDetails.index'));
        }

        $this->cRMDetailsRepository->delete($id);

        Flash::success('C R M Details deleted successfully.');

        return redirect(route('cRMDetails.index'));
    }
}
