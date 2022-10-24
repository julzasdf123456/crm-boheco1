<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePreRegEntriesRequest;
use App\Http\Requests\UpdatePreRegEntriesRequest;
use App\Repositories\PreRegEntriesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\PreRegEntries;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class PreRegEntriesController extends AppBaseController
{
    /** @var  PreRegEntriesRepository */
    private $preRegEntriesRepository;

    public function __construct(PreRegEntriesRepository $preRegEntriesRepo)
    {
        $this->middleware('auth');
        $this->preRegEntriesRepository = $preRegEntriesRepo;
    }

    /**
     * Display a listing of the PreRegEntries.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request['params'] == null) {
            $data = DB::connection('sqlsrvagma')
                        ->table('Entries')
                        ->select('*')
                        ->paginate(20);
        } else {
            $data = DB::connection('sqlsrvagma')
                        ->table('Entries')
                        ->whereRaw("Name LIKE '%" . $request['params'] . "%' OR AccountNumber LIKE '" . $request['params'] . "%'")
                        ->select('*')
                        ->paginate(20);
        }

        return view('pre_reg_entries.index', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new PreRegEntries.
     *
     * @return Response
     */
    public function create()
    {
        return view('pre_reg_entries.create');
    }

    /**
     * Store a newly created PreRegEntries in storage.
     *
     * @param CreatePreRegEntriesRequest $request
     *
     * @return Response
     */
    public function store(CreatePreRegEntriesRequest $request)
    {
        $input = $request->all();

        $preRegEntries = $this->preRegEntriesRepository->create($input);

        Flash::success('Pre Reg Entries saved successfully.');

        return redirect(route('preRegEntries.index'));
    }

    /**
     * Display the specified PreRegEntries.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $preRegEntries = $this->preRegEntriesRepository->find($id);

        if (empty($preRegEntries)) {
            Flash::error('Pre Reg Entries not found');

            return redirect(route('preRegEntries.index'));
        }

        return view('pre_reg_entries.show')->with('preRegEntries', $preRegEntries);
    }

    /**
     * Show the form for editing the specified PreRegEntries.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $preRegEntries = $this->preRegEntriesRepository->find($id);

        if (empty($preRegEntries)) {
            Flash::error('Pre Reg Entries not found');

            return redirect(route('preRegEntries.index'));
        }

        return view('pre_reg_entries.edit')->with('preRegEntries', $preRegEntries);
    }

    /**
     * Update the specified PreRegEntries in storage.
     *
     * @param int $id
     * @param UpdatePreRegEntriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePreRegEntriesRequest $request)
    {
        $preRegEntries = PreRegEntries::find($id);

        if (empty($preRegEntries)) {
            Flash::error('Pre Reg Entries not found');

            return redirect(route('preRegEntries.index'));
        }

        $preRegEntries = PreRegEntries::where('Id', $id)->update(['ContactNumber' => $request['ContactNumber'], 'Email' => $request['Email']]);

        Flash::success('Pre Reg Entries updated successfully.');

        return redirect(route('preRegEntries.index'));
    }

    /**
     * Remove the specified PreRegEntries from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $preRegEntries = $this->preRegEntriesRepository->find($id);

        if (empty($preRegEntries)) {
            Flash::error('Pre Reg Entries not found');

            return redirect(route('preRegEntries.index'));
        }

        $this->preRegEntriesRepository->delete($id);

        Flash::success('Pre Reg Entries deleted successfully.');

        return redirect(route('preRegEntries.index'));
    }
}
