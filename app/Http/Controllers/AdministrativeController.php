<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountLocationHistoryRequest;
use App\Http\Requests\UpdateAccountLocationHistoryRequest;
use App\Repositories\AccountLocationHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Servers;
use Flash;
use Response;

class AdministrativeController extends AppBaseController
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function serverMonitor(Request $request) {
      return view('/administrative/server_monitor', [
         'servers' => Servers::all(),
      ]);
   }
}
