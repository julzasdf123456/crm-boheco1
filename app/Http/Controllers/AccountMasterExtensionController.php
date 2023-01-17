<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountMasterExtensionRequest;
use App\Http\Requests\UpdateAccountMasterExtensionRequest;
use App\Repositories\AccountMasterExtensionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccountMasterExtensionController extends AppBaseController
{
    /** @var  AccountMasterExtensionRepository */
    private $accountMasterExtensionRepository;

    public function __construct(AccountMasterExtensionRepository $accountMasterExtensionRepo)
    {
        $this->middleware('auth');
        $this->accountMasterExtensionRepository = $accountMasterExtensionRepo;
    }

    /**
     * Display a listing of the AccountMasterExtension.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accountMasterExtensions = $this->accountMasterExtensionRepository->all();

        return view('account_master_extensions.index')
            ->with('accountMasterExtensions', $accountMasterExtensions);
    }

    /**
     * Show the form for creating a new AccountMasterExtension.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_master_extensions.create');
    }

    /**
     * Store a newly created AccountMasterExtension in storage.
     *
     * @param CreateAccountMasterExtensionRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountMasterExtensionRequest $request)
    {
        $input = $request->all();

        $accountMasterExtension = $this->accountMasterExtensionRepository->create($input);

        Flash::success('Account Master Extension saved successfully.');

        return redirect(route('accountMasterExtensions.index'));
    }

    /**
     * Display the specified AccountMasterExtension.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountMasterExtension = $this->accountMasterExtensionRepository->find($id);

        if (empty($accountMasterExtension)) {
            Flash::error('Account Master Extension not found');

            return redirect(route('accountMasterExtensions.index'));
        }

        return view('account_master_extensions.show')->with('accountMasterExtension', $accountMasterExtension);
    }

    /**
     * Show the form for editing the specified AccountMasterExtension.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountMasterExtension = $this->accountMasterExtensionRepository->find($id);

        if (empty($accountMasterExtension)) {
            Flash::error('Account Master Extension not found');

            return redirect(route('accountMasterExtensions.index'));
        }

        return view('account_master_extensions.edit')->with('accountMasterExtension', $accountMasterExtension);
    }

    /**
     * Update the specified AccountMasterExtension in storage.
     *
     * @param int $id
     * @param UpdateAccountMasterExtensionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountMasterExtensionRequest $request)
    {
        $accountMasterExtension = $this->accountMasterExtensionRepository->find($id);

        if (empty($accountMasterExtension)) {
            Flash::error('Account Master Extension not found');

            return redirect(route('accountMasterExtensions.index'));
        }

        $accountMasterExtension = $this->accountMasterExtensionRepository->update($request->all(), $id);

        Flash::success('Account Master Extension updated successfully.');

        return redirect(route('accountMasterExtensions.index'));
    }

    /**
     * Remove the specified AccountMasterExtension from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountMasterExtension = $this->accountMasterExtensionRepository->find($id);

        if (empty($accountMasterExtension)) {
            Flash::error('Account Master Extension not found');

            return redirect(route('accountMasterExtensions.index'));
        }

        $this->accountMasterExtensionRepository->delete($id);

        Flash::success('Account Master Extension deleted successfully.');

        return redirect(route('accountMasterExtensions.index'));
    }
}
