<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountMasterRequest;
use App\Http\Requests\UpdateAccountMasterRequest;
use App\Repositories\AccountMasterRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccountMasterController extends AppBaseController
{
    /** @var  AccountMasterRepository */
    private $accountMasterRepository;

    public function __construct(AccountMasterRepository $accountMasterRepo)
    {
        $this->accountMasterRepository = $accountMasterRepo;
    }

    /**
     * Display a listing of the AccountMaster.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accountMasters = $this->accountMasterRepository->all();

        return view('account_masters.index')
            ->with('accountMasters', $accountMasters);
    }

    /**
     * Show the form for creating a new AccountMaster.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_masters.create');
    }

    /**
     * Store a newly created AccountMaster in storage.
     *
     * @param CreateAccountMasterRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountMasterRequest $request)
    {
        $input = $request->all();

        $accountMaster = $this->accountMasterRepository->create($input);

        Flash::success('Account Master saved successfully.');

        return redirect(route('accountMasters.index'));
    }

    /**
     * Display the specified AccountMaster.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        return view('account_masters.show')->with('accountMaster', $accountMaster);
    }

    /**
     * Show the form for editing the specified AccountMaster.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        return view('account_masters.edit')->with('accountMaster', $accountMaster);
    }

    /**
     * Update the specified AccountMaster in storage.
     *
     * @param int $id
     * @param UpdateAccountMasterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountMasterRequest $request)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        $accountMaster = $this->accountMasterRepository->update($request->all(), $id);

        Flash::success('Account Master updated successfully.');

        return redirect(route('accountMasters.index'));
    }

    /**
     * Remove the specified AccountMaster from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountMaster = $this->accountMasterRepository->find($id);

        if (empty($accountMaster)) {
            Flash::error('Account Master not found');

            return redirect(route('accountMasters.index'));
        }

        $this->accountMasterRepository->delete($id);

        Flash::success('Account Master deleted successfully.');

        return redirect(route('accountMasters.index'));
    }
}
