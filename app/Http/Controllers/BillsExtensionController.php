<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillsExtensionRequest;
use App\Http\Requests\UpdateBillsExtensionRequest;
use App\Repositories\BillsExtensionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillsExtensionController extends AppBaseController
{
    /** @var  BillsExtensionRepository */
    private $billsExtensionRepository;

    public function __construct(BillsExtensionRepository $billsExtensionRepo)
    {
        $this->middleware('auth');
        $this->billsExtensionRepository = $billsExtensionRepo;
    }

    /**
     * Display a listing of the BillsExtension.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billsExtensions = $this->billsExtensionRepository->all();

        return view('bills_extensions.index')
            ->with('billsExtensions', $billsExtensions);
    }

    /**
     * Show the form for creating a new BillsExtension.
     *
     * @return Response
     */
    public function create()
    {
        return view('bills_extensions.create');
    }

    /**
     * Store a newly created BillsExtension in storage.
     *
     * @param CreateBillsExtensionRequest $request
     *
     * @return Response
     */
    public function store(CreateBillsExtensionRequest $request)
    {
        $input = $request->all();

        $billsExtension = $this->billsExtensionRepository->create($input);

        Flash::success('Bills Extension saved successfully.');

        return redirect(route('billsExtensions.index'));
    }

    /**
     * Display the specified BillsExtension.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billsExtension = $this->billsExtensionRepository->find($id);

        if (empty($billsExtension)) {
            Flash::error('Bills Extension not found');

            return redirect(route('billsExtensions.index'));
        }

        return view('bills_extensions.show')->with('billsExtension', $billsExtension);
    }

    /**
     * Show the form for editing the specified BillsExtension.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billsExtension = $this->billsExtensionRepository->find($id);

        if (empty($billsExtension)) {
            Flash::error('Bills Extension not found');

            return redirect(route('billsExtensions.index'));
        }

        return view('bills_extensions.edit')->with('billsExtension', $billsExtension);
    }

    /**
     * Update the specified BillsExtension in storage.
     *
     * @param int $id
     * @param UpdateBillsExtensionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillsExtensionRequest $request)
    {
        $billsExtension = $this->billsExtensionRepository->find($id);

        if (empty($billsExtension)) {
            Flash::error('Bills Extension not found');

            return redirect(route('billsExtensions.index'));
        }

        $billsExtension = $this->billsExtensionRepository->update($request->all(), $id);

        Flash::success('Bills Extension updated successfully.');

        return redirect(route('billsExtensions.index'));
    }

    /**
     * Remove the specified BillsExtension from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billsExtension = $this->billsExtensionRepository->find($id);

        if (empty($billsExtension)) {
            Flash::error('Bills Extension not found');

            return redirect(route('billsExtensions.index'));
        }

        $this->billsExtensionRepository->delete($id);

        Flash::success('Bills Extension deleted successfully.');

        return redirect(route('billsExtensions.index'));
    }
}
