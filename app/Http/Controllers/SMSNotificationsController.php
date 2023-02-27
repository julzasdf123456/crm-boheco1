<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSMSNotificationsRequest;
use App\Http\Requests\UpdateSMSNotificationsRequest;
use App\Repositories\SMSNotificationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SMSNotificationsController extends AppBaseController
{
    /** @var  SMSNotificationsRepository */
    private $sMSNotificationsRepository;

    public function __construct(SMSNotificationsRepository $sMSNotificationsRepo)
    {
        $this->middleware('auth');
        $this->sMSNotificationsRepository = $sMSNotificationsRepo;
    }

    /**
     * Display a listing of the SMSNotifications.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $sMSNotifications = $this->sMSNotificationsRepository->all();

        return view('s_m_s_notifications.index')
            ->with('sMSNotifications', $sMSNotifications);
    }

    /**
     * Show the form for creating a new SMSNotifications.
     *
     * @return Response
     */
    public function create()
    {
        return view('s_m_s_notifications.create');
    }

    /**
     * Store a newly created SMSNotifications in storage.
     *
     * @param CreateSMSNotificationsRequest $request
     *
     * @return Response
     */
    public function store(CreateSMSNotificationsRequest $request)
    {
        $input = $request->all();

        $sMSNotifications = $this->sMSNotificationsRepository->create($input);

        Flash::success('S M S Notifications saved successfully.');

        return redirect(route('sMSNotifications.index'));
    }

    /**
     * Display the specified SMSNotifications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sMSNotifications = $this->sMSNotificationsRepository->find($id);

        if (empty($sMSNotifications)) {
            Flash::error('S M S Notifications not found');

            return redirect(route('sMSNotifications.index'));
        }

        return view('s_m_s_notifications.show')->with('sMSNotifications', $sMSNotifications);
    }

    /**
     * Show the form for editing the specified SMSNotifications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sMSNotifications = $this->sMSNotificationsRepository->find($id);

        if (empty($sMSNotifications)) {
            Flash::error('S M S Notifications not found');

            return redirect(route('sMSNotifications.index'));
        }

        return view('s_m_s_notifications.edit')->with('sMSNotifications', $sMSNotifications);
    }

    /**
     * Update the specified SMSNotifications in storage.
     *
     * @param int $id
     * @param UpdateSMSNotificationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSMSNotificationsRequest $request)
    {
        $sMSNotifications = $this->sMSNotificationsRepository->find($id);

        if (empty($sMSNotifications)) {
            Flash::error('S M S Notifications not found');

            return redirect(route('sMSNotifications.index'));
        }

        $sMSNotifications = $this->sMSNotificationsRepository->update($request->all(), $id);

        Flash::success('S M S Notifications updated successfully.');

        return redirect(route('sMSNotifications.index'));
    }

    /**
     * Remove the specified SMSNotifications from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $sMSNotifications = $this->sMSNotificationsRepository->find($id);

        if (empty($sMSNotifications)) {
            Flash::error('S M S Notifications not found');

            return redirect(route('sMSNotifications.index'));
        }

        $this->sMSNotificationsRepository->delete($id);

        Flash::success('S M S Notifications deleted successfully.');

        return redirect(route('sMSNotifications.index'));
    }
}
