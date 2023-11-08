<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ServiceConnectionsController;
use App\Http\Controllers\MemberConsumersController;
use App\Http\Controllers\DisconnectionSchedulesController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ServiceAccountsController;
use App\Http\Controllers\DisconnectionDataController;
use App\Http\Controllers\AdministrativeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('cors');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('cors');
Route::get('/home/get-unassigned-meters', [HomeController::class, 'fetchUnassignedMeters'])->name('home.get-unassigned-meters');
Route::get('/home/get-new-service-connections', [HomeController::class, 'fetchNewServiceConnections'])->name('home.get-new-service-connections');
Route::get('/home/get-approved-service-connections', [HomeController::class, 'fetchApprovedServiceConnections'])->name('home.get-approved-service-connections');
Route::get('/home/get-for-engergization', [HomeController::class, 'fetchForEnergization'])->name('home.get-for-engergization');
Route::get('/home/get-inspection-report', [HomeController::class, 'fetchInspectionReport'])->name('home.get-inspection-report');
Route::get('/home/get-inspection-large-load', [HomeController::class, 'fetchInspectionLargeLoad'])->name('home.get-inspection-large-load');
Route::get('/home/get-bom-large-load', [HomeController::class, 'fetchBomLargeLoad'])->name('home.get-bom-large-load');
Route::get('/home/get-transformer-large-load', [HomeController::class, 'fetchTransformerLargeLoad'])->name('home.get-transformer-large-load');

// ADD PERMISSIONS TO ROLES
Route::get('/roles/add-permissions/{id}', [RoleController::class, 'addPermissions'])->name('roles.add_permissions');
Route::post('/roles/create-role-permissions', [RoleController::class, 'createRolePermissions']);

// ADD ROLES TO USER
Route::get('/users/add-user-roles/{id}', [UsersController::class, 'addUserRoles'])->name('users.add_user_roles');
Route::post('/users/create-user-roles', [UsersController::class, 'createUserRoles']);
Route::get('/users/add-user-permissions/{id}', [UsersController::class, 'addUserPermissions'])->name('users.add_user_permissions');
Route::post('/users/create-user-permissions', [UsersController::class, 'createUserPermissions']);
Route::get('/users/remove-permission/{id}/{permission}', [UsersController::class, 'removePermission'])->name('users.remove_permission');
Route::get('/users/remove-roles/{id}', [UsersController::class, 'clearRoles'])->name('users.remove_roles');

Route::post('/users/authenticate', [UsersController::class, 'authenticate'])->name('users.authenticate');
Route::get('/users/profile/{id}', [UsersController::class, 'profile'])->name('users.profile');
Route::resource('users', UsersController::class);

Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::resource('permissions', App\Http\Controllers\PermissionController::class);


Route::get('/member_consumers/assess_checklists/{id}', [MemberConsumersController::class, 'assessChecklists'])->name('memberConsumers.assess-checklists');
Route::get('/member_consumers/fetchmemberconsumer', [MemberConsumersController::class, 'fetchmemberconsumer'])->name('memberConsumers.fetch-member-consumers');
Route::get('/member_consumers/capture-image/{id}', [MemberConsumersController::class, 'captureImage'])->name('memberConsumers.capture-image');
Route::get('/member_consumers/print-membership-application/{id}', [MemberConsumersController::class, 'printMembershipApplication'])->name('memberConsumers.print-membership-application');
Route::get('/member_consumers/print-certificate/{id}', [MemberConsumersController::class, 'printCertificate'])->name('memberConsumers.print-certificate');
Route::get('/member_consumers/monthly-reports', [MemberConsumersController::class, 'monthlyReports'])->name('memberConsumers.monthly-reports');
Route::get('/member_consumers/download-monthly-reports/{town}/{month}/{year}/{office}', [MemberConsumersController::class, 'downloadMonthlyReports'])->name('memberConsumers.download-monthly-reports');
Route::get('/member_consumers/quarterly-reports', [MemberConsumersController::class, 'quarterlyReports'])->name('memberConsumers.quarterly-reports');
Route::get('/member_consumers/download-quarterly-reports/{town}/{quarter}/{year}', [MemberConsumersController::class, 'downloadQuarterlyReports'])->name('memberConsumers.download-quarterly-reports');
Route::get('/member_consumers/daily-monitor', [MemberConsumersController::class, 'dailyMonitor'])->name('memberConsumers.daily-monitor');
Route::get('/member_consumers/daily-monitor-data', [MemberConsumersController::class, 'dailyMonitorData'])->name('memberConsumers.daily-monitor-data');
Route::get('/member_consumers/daily-monitor-total', [MemberConsumersController::class, 'dailyMonitorTotal'])->name('memberConsumers.daily-monitor-total');
Route::get('/member_consumers/trash', [MemberConsumersController::class, 'trash'])->name('memberConsumers.trash');
Route::get('/member_consumers/restore/{id}', [MemberConsumersController::class, 'restore'])->name('memberConsumers.restore');
Route::resource('memberConsumers', MemberConsumersController::class);


Route::resource('memberConsumerTypes', App\Http\Controllers\MemberConsumerTypesController::class);


Route::resource('towns', App\Http\Controllers\TownsController::class);


Route::resource('barangays', App\Http\Controllers\BarangaysController::class);
Route::get('/barangays/get-barangays-json/{townId}', [App\Http\Controllers\BarangaysController::class, 'getBarangaysJSON']);

Route::get('/member_consumer_spouses/create/{consumerId}', [App\Http\Controllers\MemberConsumerSpouseController::class, 'create'])->name('memberConsumerSpouses.create');
Route::get('/member_consumer_spouses/index', [App\Http\Controllers\MemberConsumerSpouseController::class, 'index'])->name('memberConsumerSpouses.index');
Route::post('/member_consumer_spouses/store', [App\Http\Controllers\MemberConsumerSpouseController::class, 'store'])->name('memberConsumerSpouses.store');
Route::get('/member_consumer_spouses/edit/{consumerId}', [App\Http\Controllers\MemberConsumerSpouseController::class, 'edit'])->name('memberConsumerSpouses.edit');
Route::patch('/member_consumer_spouses/update/{id}', [App\Http\Controllers\MemberConsumerSpouseController::class, 'update'])->name('memberConsumerSpouses.update');
// Route::resource('memberConsumerSpouses', App\Http\Controllers\MemberConsumerSpouseController::class);

Route::get('/service_connections/fetchserviceconnections', [ServiceConnectionsController::class, 'fetchserviceconnections'])->name('serviceConnections.fetch-service-connections');
Route::get('/service_connections/selectmembership', [ServiceConnectionsController::class, 'selectMembership'])->name('serviceConnections.selectmembership');
Route::get('/service_connections/fetchmemberconsumer', [ServiceConnectionsController::class, 'fetchmemberconsumer'])->name('serviceConnections.fetch-member-consumers');
Route::get('/service_connections/create_new/{consumerId}', [ServiceConnectionsController::class, 'createNew'])->name('serviceConnections.create_new');
Route::get('/service_connections/create_new_step_two/{scId}', [ServiceConnectionsController::class, 'createNewStepTwo'])->name('serviceConnections.create_new_step_two');
Route::get('/service_connections/assess_checklists/{id}', [ServiceConnectionsController::class, 'assessChecklists'])->name('serviceConnections.assess-checklists');
Route::get('/service_connections/update_checklists/{id}', [ServiceConnectionsController::class, 'updateChecklists'])->name('serviceConnections.update-checklists');
Route::get('/service_connections/move_to_trash/{id}', [ServiceConnectionsController::class, 'moveToTrash'])->name('serviceConnections.move-to-trash');
Route::get('/service_connections/trash', [ServiceConnectionsController::class, 'trash'])->name('serviceConnections.trash');
Route::get('/service_connections/restore/{id}', [ServiceConnectionsController::class, 'restore'])->name('serviceConnections.restore');
Route::get('/service_connections/fetchserviceconnectiontrash', [ServiceConnectionsController::class, 'fetchserviceconnectiontrash'])->name('serviceConnections.fetch-service-connection-trash');
Route::get('/service_connections/energization', [ServiceConnectionsController::class, 'energization'])->name('serviceConnections.energization');
Route::get('/service_connections/print_order/{id}', [ServiceConnectionsController::class, 'printOrder'])->name('serviceConnections.print-order');
Route::post('/service_connections/change-station-crew', [ServiceConnectionsController::class, 'changeStationCrew']);
Route::post('/service_connections/update-energization-status', [ServiceConnectionsController::class, 'updateEnergizationStatus']);
Route::get('/service_connections/select_application_type/{consumerId}', [ServiceConnectionsController::class, 'selectApplicationType'])->name('serviceConnections.select-application-type');
Route::post('/service_connections/relay_account_type/{consumerId}', [ServiceConnectionsController::class, 'relayApplicationType'])->name('serviceConnections.relay-account-type');
Route::get('/service_connections/dashboard', [ServiceConnectionsController::class, 'dashboard'])->name('serviceConnections.dashboard');
Route::get('/service_connections/large-load-inspections', [ServiceConnectionsController::class, 'largeLoadInspections'])->name('serviceConnections.large-load-inspections');
Route::post('/service_connections/large-load-inspection-update', [ServiceConnectionsController::class, 'largeLoadInspectionUpdate'])->name('serviceConnections.large-load-inspection-update');
Route::get('/service_connections/bom-index', [ServiceConnectionsController::class, 'bomIndex'])->name('serviceConnections.bom-index');
Route::get('/service_connections/bom-assigning/{scId}', [ServiceConnectionsController::class, 'bomAssigning'])->name('serviceConnections.bom-assigning');
Route::get('/service_connections/forward-to-transformer-assigning/{scId}', [ServiceConnectionsController::class, 'forwardToTransformerAssigning'])->name('serviceConnections.forward-to-transformer-assigning');
Route::get('/service_connections/transformer-assigning/{scId}', [ServiceConnectionsController::class, 'transformerAssigning'])->name('serviceConnections.transformer-assigning');
Route::get('/service_connections/transformer_index', [ServiceConnectionsController::class, 'transformerIndex'])->name('serviceConnections.transformer-index');
Route::get('/service_connections/pole-assigning/{scId}', [ServiceConnectionsController::class, 'poleAssigning'])->name('serviceConnections.pole-assigning');
Route::get('/service_connections/quotation-summary/{scId}', [ServiceConnectionsController::class, 'quotationSummary'])->name('serviceConnections.quotation-summary');
Route::get('/service_connections/spanning-assigning/{scId}', [ServiceConnectionsController::class, 'spanningAssigning'])->name('serviceConnections.spanning-assigning');
Route::get('/service_connections/forward-to-verficaation/{scId}', [ServiceConnectionsController::class, 'forwardToVerification'])->name('serviceConnections.forward-to-verficaation');
Route::get('/service_connections/largeload-predefined-materials/{scId}/{options}', [ServiceConnectionsController::class, 'largeLoadPredefinedMaterials'])->name('serviceConnections.largeload-predefined-materials');
Route::get('/service_connections/fleet-monitor', [ServiceConnectionsController::class, 'fleetMonitor'])->name('serviceConnections.fleet-monitor');
Route::get('/service_connections/metering-equipment-assigning/{scId}', [ServiceConnectionsController::class, 'meteringEquipmentAssigning'])->name('serviceConnections.metering-equipment-assigning');
Route::get('/service_connections/daily-monitor', [ServiceConnectionsController::class, 'dailyMonitor'])->name('serviceConnections.daily-monitor');
Route::get('/service_connections/fetch-daily-monitor-applications-data', [ServiceConnectionsController::class, 'fetchDailyMonitorApplicationsData'])->name('serviceConnections.fetch-daily-monitor-applications-data');
Route::get('/service_connections/fetch-daily-monitor-energized-data', [ServiceConnectionsController::class, 'fetchDailyMonitorEnergizedData'])->name('serviceConnections.fetch-daily-monitor-energized-data');
Route::get('/service_connections/applications-report', [ServiceConnectionsController::class, 'applicationsReport'])->name('serviceConnections.applications-report');
Route::get('/service_connections/fetch-applications-report', [ServiceConnectionsController::class, 'fetchApplicationsReport'])->name('serviceConnections.fetch-applications-report');
Route::post('/service_connections/download-applications-report', [ServiceConnectionsController::class, 'downloadApplicationsReport'])->name('serviceConnections.download-applications-report');
Route::get('/service_connections/energization-report', [ServiceConnectionsController::class, 'energizationReport'])->name('serviceConnections.energization-report');
Route::get('/service_connections/fetch-energization-report', [ServiceConnectionsController::class, 'fetchEnergizationReport'])->name('serviceConnections.fetch-energization-report');
Route::post('/service_connections/download-energization-report', [ServiceConnectionsController::class, 'downloadEnergizationReport'])->name('serviceConnections.download-energization-report');
Route::get('/service_connections/fetch-application-count-via-status', [ServiceConnectionsController::class, 'fetchApplicationCountViaStatus'])->name('serviceConnections.fetch-application-count-via-status');
Route::get('/service_connections/print-service-connection-application/{id}', [ServiceConnectionsController::class, 'printServiceConnectionApplication'])->name('serviceConnections.print-service-connection-application');
Route::get('/service_connections/print-service-connection-contract/{id}', [ServiceConnectionsController::class, 'printServiceConnectionContract'])->name('serviceConnections.print-service-connection-contract');
Route::get('/service_connections/relocation-search', [ServiceConnectionsController::class, 'relocationSearch'])->name('serviceConnections.relocation-search');
Route::get('/service_connections/create-relocation/{id}', [ServiceConnectionsController::class, 'createRelocation'])->name('serviceConnections.create-relocation');
Route::get('/service_connections/change-name-search', [ServiceConnectionsController::class, 'changeNameSearch'])->name('serviceConnections.change-name-search');
Route::get('/service_connections/create-change-name/{id}', [ServiceConnectionsController::class, 'createChangeName'])->name('serviceConnections.create-change-name');
Route::post('/service_connections/store-change-name', [ServiceConnectionsController::class, 'storeChangeName'])->name('serviceConnections.store-change-name');
Route::get('/service_connections/approve-change-name/{id}', [ServiceConnectionsController::class, 'approveForChangeName'])->name('serviceConnections.approve-change-name');
Route::get('/service_connections/bypass-approve-inspection/{inspectionId}', [ServiceConnectionsController::class, 'bypassApproveInspection'])->name('serviceConnections.bypass-approve-inspection');
Route::get('/service_connections/save-electrician-info', [ServiceConnectionsController::class, 'saveElectricianInfo'])->name('serviceConnections.save-electrician-info');
Route::get('/service_connections/print-invoice/{id}', [ServiceConnectionsController::class, 'printInvoice'])->name('serviceConnections.print-invoice');
Route::get('/service_connections/inspection-full-report', [ServiceConnectionsController::class, 'inspectionFullReport'])->name('serviceConnections.inspection-full-report');
Route::get('/service_connections/get-inspection-summary-data', [ServiceConnectionsController::class, 'getInspectionSummaryData'])->name('serviceConnections.get-inspection-summary-data');
Route::get('/service_connections/energization-per-brgy', [App\Http\Controllers\ServiceConnectionsController::class, 'energizationPerBarangay'])->name('serviceConnections.energization-per-brgy');
Route::get('/service_connections/download-energization-per-brgy/{mnth}/{year}', [App\Http\Controllers\ServiceConnectionsController::class, 'downloadEnergizationPerBarangay'])->name('serviceConnections.download-energization-per-brgy');
Route::get('/service_connections/crew-assigning', [App\Http\Controllers\ServiceConnectionsController::class, 'crewAssigning'])->name('serviceConnections.crew-assigning');
Route::get('/service_connections/assign-crew', [App\Http\Controllers\ServiceConnectionsController::class, 'assignCrew'])->name('serviceConnections.assign-crew');
Route::get('/service_connections/energization-per-town', [App\Http\Controllers\ServiceConnectionsController::class, 'energizationPerTown'])->name('serviceConnections.energization-per-town');
Route::get('/service_connections/download-energization-per-town/{mnth}/{year}', [App\Http\Controllers\ServiceConnectionsController::class, 'downloadEnergizationPerTown'])->name('serviceConnections.download-energization-per-town');
Route::get('/service_connections/meter-installation', [App\Http\Controllers\ServiceConnectionsController::class, 'meterInstallation'])->name('serviceConnections.meter-installation');
Route::get('/service_connections/download-meter-installation/{from}/{to}/{office}', [App\Http\Controllers\ServiceConnectionsController::class, 'downloadMeterInstallation'])->name('serviceConnections.download-meter-installation');
Route::get('/service_connections/update-status', [ServiceConnectionsController::class, 'updateStatus'])->name('serviceConnections.update-status');
Route::get('/service_connections/update-or', [ServiceConnectionsController::class, 'updateOR'])->name('serviceConnections.update-or');
Route::get('/service_connections/summary-report', [ServiceConnectionsController::class, 'summaryReport'])->name('serviceConnections.summary-report');
Route::get('/service_connections/download-summary-report/{month}/{year}', [ServiceConnectionsController::class, 'downloadSummaryReport'])->name('serviceConnections.download-summary-report');
Route::get('/service_connections/get-inspection-summary-data-calendar', [ServiceConnectionsController::class, 'getInspectionSummaryDataCalendar'])->name('serviceConnections.get-inspection-summary-data-calendar');
Route::get('/service_connections/convert-to-bapa', [ServiceConnectionsController::class, 'convertToBapa'])->name('serviceConnections.convert-to-bapa');
Route::get('/service_connections/convert-to-eca', [ServiceConnectionsController::class, 'convertToEca'])->name('serviceConnections.convert-to-eca');
Route::get('/service_connections/remove-material-payment', [ServiceConnectionsController::class, 'removeMaterialPayment'])->name('serviceConnections.remove-material-payment');
Route::get('/service_connections/print-quotation-form/{id}', [ServiceConnectionsController::class, 'printQuotationForm'])->name('serviceConnections.print-quotation-form');
Route::get('/service_connections/service-drop', [ServiceConnectionsController::class, 'serviceDrop'])->name('serviceConnections.service-drop');
Route::get('/service_connections/new-energized-rewiring', [ServiceConnectionsController::class, 'newEnergizedRewiring'])->name('serviceConnections.new-energized-rewiring');
Route::get('/service_connections/change-name-payment/{id}', [ServiceConnectionsController::class, 'changeNamePayment'])->name('serviceConnections.change-name-payment');
Route::post('/service_connections/store-change-name-payment', [ServiceConnectionsController::class, 'storeChangeNamePayment'])->name('serviceConnections.store-change-name-payment');
Route::get('/service_connections/change-name-for-approval', [ServiceConnectionsController::class, 'changeNameForApproval'])->name('serviceConnections.change-name-for-approval');
Route::get('/service_connections/approved-change-names', [ServiceConnectionsController::class, 'approvedChangeNames'])->name('serviceConnections.approved-change-names');
Route::get('/service_connections/change-account-name', [ServiceConnectionsController::class, 'changeAccountName'])->name('serviceConnections.change-account-name');
Route::get('/service_connections/print-change-name/{id}', [ServiceConnectionsController::class, 'printChangeName'])->name('serviceConnections.print-change-name');
Route::get('/service_connections/print-quotation-form-separate-installation-fee/{id}', [ServiceConnectionsController::class, 'printQuotationFormSeparateInstallationFee'])->name('serviceConnections.print-quotation-form-separate-installation-fee');
Route::get('/service_connections/save-material-summary-amount', [ServiceConnectionsController::class, 'saveMaterialSummaryAmount'])->name('serviceConnections.save-material-summary-amount');
Route::get('/service_connections/transformer-ammortizations', [ServiceConnectionsController::class, 'transformerAmmortizations'])->name('serviceConnections.transformer-ammortizations');
Route::get('/service_connections/transformer-ammortizations-view/{id}', [ServiceConnectionsController::class, 'transformerAmmortizationsView'])->name('serviceConnections.transformer-ammortizations-view');
Route::get('/service_connections/save-transformer-ammortization', [ServiceConnectionsController::class, 'saveTransformerAmmortization'])->name('serviceConnections.save-transformer-ammortization');
Route::get('/service_connections/print-transformer-ammortization/{id}', [ServiceConnectionsController::class, 'printTransformerAmmortization'])->name('serviceConnections.print-transformer-ammortization');
Route::get('/service_connections/forward-remittance', [ServiceConnectionsController::class, 'forwardRemittance'])->name('serviceConnections.forward-remittance');
Route::get('/service_connections/forward-installation-fees', [ServiceConnectionsController::class, 'forwardInstallationFees'])->name('serviceConnections.forward-installation-fees');
Route::get('/service_connections/forward-transformer-fees', [ServiceConnectionsController::class, 'forwardTransformerFees'])->name('serviceConnections.forward-transformer-fees');
Route::get('/service_connections/forward-all-fees', [ServiceConnectionsController::class, 'forwardAllFees'])->name('serviceConnections.forward-all-fees');
Route::resource('serviceConnections', App\Http\Controllers\ServiceConnectionsController::class);


Route::resource('serviceConnectionAccountTypes', App\Http\Controllers\ServiceConnectionAccountTypesController::class);


Route::get('/service_connection_inspections/create_step_two/{scId}', [App\Http\Controllers\ServiceConnectionInspectionsController::class, 'createStepTwo'])->name('serviceConnectionInspections.create-step-two');
Route::resource('serviceConnectionInspections', App\Http\Controllers\ServiceConnectionInspectionsController::class);


Route::get('/service_connection_mtr_trnsfrmrs/assigning', [App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class, 'assigning'])->name('serviceConnectionMtrTrnsfrmrs.assigning');
Route::get('/service_connection_mtr_trnsfrmrs/create_step_three/{scId}', [App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class, 'createStepThree'])->name('serviceConnectionMtrTrnsfrmrs.create-step-three');
Route::resource('serviceConnectionMtrTrnsfrmrs', App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class);


Route::resource('serviceConnectionMatPayables', App\Http\Controllers\ServiceConnectionMatPayablesController::class);


Route::resource('serviceConnectionPayParticulars', App\Http\Controllers\ServiceConnectionPayParticularsController::class);


Route::resource('serviceConnectionMatPayments', App\Http\Controllers\ServiceConnectionMatPaymentsController::class);

Route::get('/service_connection_pay_tansactions/create_step_four/{scId}', [App\Http\Controllers\ServiceConnectionPayTransactionController::class, 'createStepFour'])->name('serviceConnectionPayTransactions.create-step-four');
Route::get('/service_connection_pay_tansactions/save-wiring-labor', [App\Http\Controllers\ServiceConnectionPayTransactionController::class, 'saveWiringLabor'])->name('serviceConnectionPayTransactions.save-wiring-labor');
Route::get('/service_connection_pay_tansactions/save-bill-deposits', [App\Http\Controllers\ServiceConnectionPayTransactionController::class, 'saveBillDeposits'])->name('serviceConnectionPayTransactions.save-bill-deposits');
Route::get('/service_connection_pay_tansactions/save-service-connection-transaction', [App\Http\Controllers\ServiceConnectionPayTransactionController::class, 'saveServiceConnectionTransaction'])->name('serviceConnectionPayTransactions.save-service-connection-transaction');
Route::get('/service_connection_pay_tansactions/save-installation-fee', [App\Http\Controllers\ServiceConnectionPayTransactionController::class, 'saveInstallationFee'])->name('serviceConnectionPayTransactions.save-installation-fee');
Route::resource('serviceConnectionPayTransactions', App\Http\Controllers\ServiceConnectionPayTransactionController::class);


Route::resource('serviceConnectionTotalPayments', App\Http\Controllers\ServiceConnectionTotalPaymentsController::class);


Route::resource('serviceConnectionTimeframes', App\Http\Controllers\ServiceConnectionTimeframesController::class);


Route::post('/member_consumer_checklists/complyChecklists/{id}', [App\Http\Controllers\MemberConsumerChecklistsController::class, 'complyChecklists'])->name('memberConsumerChecklists.comply-checklists');
Route::resource('memberConsumerChecklists', App\Http\Controllers\MemberConsumerChecklistsController::class);


Route::resource('memberConsumerChecklistsReps', App\Http\Controllers\MemberConsumerChecklistsRepController::class);


Route::resource('serviceConnectionChecklistsReps', App\Http\Controllers\ServiceConnectionChecklistsRepController::class);

Route::post('/service_connection_checklists_reps/complyChecklists/{id}', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'complyChecklists'])->name('serviceConnectionChecklists.comply-checklists');
Route::post('/service_connection_checklists_reps/save-file-and-comply-checklist', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'saveFileAndComplyChecklist']);
Route::get('/service_connection_checklists_reps/assess-checklist-completion/{scId}', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'assessChecklistCompletion'])->name('serviceConnectionChecklists.assess-checklist-completion');
Route::get('/service_connection_checklists_reps/download-file/{scId}/{folder}/{file}', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'downloadFile'])->name('serviceConnectionChecklists.download-file');
Route::resource('serviceConnectionChecklists', App\Http\Controllers\ServiceConnectionChecklistsController::class);


Route::resource('serviceConnectionCrews', App\Http\Controllers\ServiceConnectionCrewController::class);


Route::get('/service_accounts/pending-accounts/', [ServiceAccountsController::class, 'pendingAccounts'])->name('serviceAccounts.pending-accounts');
Route::get('/service_accounts/account-migration/{id}', [ServiceAccountsController::class, 'accountMigration'])->name('serviceAccounts.account-migration');
Route::get('/service_accounts/account-migration-step-two/{id}', [ServiceAccountsController::class, 'accountMigrationStepTwo'])->name('serviceAccounts.account-migration-step-two');
Route::get('/service_accounts/account-migration-step-three/{id}', [ServiceAccountsController::class, 'accountMigrationStepThree'])->name('serviceAccounts.account-migration-step-three');
Route::get('/service_accounts/update_step_one/{id}', [ServiceAccountsController::class, 'updateStepOne'])->name('serviceAccounts.update-step-one');
Route::get('/service_accounts/merge-all-bill-arrears/{id}', [ServiceAccountsController::class,  'mergeAllBillArrears'])->name('serviceAccounts.merge-all-bill-arrears');
Route::get('/service_accounts/unmerge-all-bill-arrears/{id}', [ServiceAccountsController::class,  'unmergeAllBillArrears'])->name('serviceAccounts.unmerge-all-bill-arrears');
Route::get('/service_accounts/unmerge-bill-arrear/{billId}', [ServiceAccountsController::class,  'unmergeBillArrear'])->name('serviceAccounts.unmerge-bill-arrear');
Route::get('/service_accounts/merge-bill-arrear/{billId}', [ServiceAccountsController::class,  'mergeBillArrear'])->name('serviceAccounts.merge-bill-arrear');
Route::get('/service_accounts/accounts-map-view', [ServiceAccountsController::class,  'accountsMapView'])->name('serviceAccounts.accounts-map-view');
Route::get('/service_accounts/get-accounts-by-town', [ServiceAccountsController::class,  'getAccountsByTown'])->name('serviceAccounts.get-accounts-by-town');
Route::get('/service_accounts/bapa', [ServiceAccountsController::class,  'bapa'])->name('serviceAccounts.bapa');
Route::get('/service_accounts/create-bapa', [ServiceAccountsController::class,  'createBapa'])->name('serviceAccounts.create-bapa');
Route::get('/service_accounts/get-routes-from-district', [ServiceAccountsController::class,  'getRoutesFromDistrict'])->name('serviceAccounts.get-routes-from-district');
Route::get('/service_accounts/add-to-bapa', [ServiceAccountsController::class,  'addToBapa'])->name('serviceAccounts.add-to-bapa');
Route::get('/service_accounts/bapa-view/{bapaName}', [ServiceAccountsController::class,  'bapaView'])->name('serviceAccounts.bapa-view');
Route::get('/service_accounts/remove-bapa-by-route', [ServiceAccountsController::class,  'removeBapaByRoute'])->name('serviceAccounts.remove-bapa-by-route');
Route::get('/service_accounts/remove-bapa-by-account', [ServiceAccountsController::class,  'removeBapaByAccount'])->name('serviceAccounts.remove-bapa-by-account');
Route::get('/service_accounts/update-bapa/{bapaName}', [ServiceAccountsController::class,  'updateBapa'])->name('serviceAccounts.update-bapa');
Route::get('/service_accounts/search-accout-bapa', [ServiceAccountsController::class,  'searchAccountBapa'])->name('serviceAccounts.search-accout-bapa');
Route::get('/service_accounts/add-single-account-to-bapa', [ServiceAccountsController::class,  'addSingleAccountToBapa'])->name('serviceAccounts.add-single-account-to-bapa');
Route::get('/service_accounts/reading-account-grouper', [ServiceAccountsController::class,  'readingAccountGrouper'])->name('serviceAccounts.reading-account-grouper');
Route::get('/service_accounts/account-grouper-view/{townCode}', [ServiceAccountsController::class,  'accountGrouperView'])->name('serviceAccounts.account-grouper-view');
Route::get('/service_accounts/account-grouper-organizer/{townCode}/{groupCode}', [ServiceAccountsController::class,  'accountGrouperOrganizer'])->name('serviceAccounts.account-grouper-organizer');
Route::get('/bills/bapa-view-readings/{period}/{bapaName}', [ServiceAccountsController::class,  'bapaViewReadings'])->name('bills.bapa-view-readings');
Route::get('/service_accounts/re-sequence-accounts', [ServiceAccountsController::class,  'reSequenceAccounts'])->name('serviceAccounts.re-sequence-accounts');
Route::get('/service_accounts/update-gps-coordinates', [ServiceAccountsController::class,  'updateGPSCoordinates'])->name('serviceAccounts.update-gps-coordinates');
Route::get('/service_accounts/search-global', [ServiceAccountsController::class,  'searchGlobal'])->name('serviceAccounts.search-global');
Route::get('/service_accounts/termed-payment-accounts', [ServiceAccountsController::class,  'termedPaymentAccounts'])->name('serviceAccounts.termed-payment-accounts');
Route::get('/service_accounts/disconnect-manual', [ServiceAccountsController::class,  'disconnectManual'])->name('serviceAccounts.disconnect-manual');
Route::get('/service_accounts/apprehend-manual', [ServiceAccountsController::class,  'apprehendManual'])->name('serviceAccounts.apprehend-manual');
Route::get('/service_accounts/pullout-manual', [ServiceAccountsController::class,  'pulloutManual'])->name('serviceAccounts.pullout-manual');
Route::get('/service_accounts/change-name', [ServiceAccountsController::class,  'changeName'])->name('serviceAccounts.change-name');
Route::get('/service_accounts/relocation-form/{accountNo}/{scId}', [ServiceAccountsController::class,  'relocationForm'])->name('serviceAccounts.relocation-form');
Route::get('/service_accounts/print-ledger/{id}/{from}/{to}', [ServiceAccountsController::class,  'printLedger'])->name('serviceAccounts.print-ledger');
Route::post('/service_accounts/store-relocation', [ServiceAccountsController::class,  'storeRelocation'])->name('serviceAccounts.store-relocation');
Route::get('/service_accounts/search-for-captured', [ServiceAccountsController::class,  'searchForCaptured'])->name('serviceAccounts.search-for-captured');
Route::get('/service_accounts/print-bapa-bills-list/{bapaName}/{period}', [ServiceAccountsController::class,  'printBapaBillsList'])->name('serviceAccounts.print-bapa-bills-list');
Route::get('/service_accounts/confirm-change-name/{id}', [ServiceAccountsController::class, 'confirmChangeName'])->name('serviceAccounts.confirm-change-name');
Route::post('/service_accounts/update-name', [ServiceAccountsController::class, 'updateName'])->name('serviceAccounts.update-name');
Route::get('/service_accounts/search-bapa-ajax', [ServiceAccountsController::class, 'searchBapaAjax'])->name('serviceAccounts.search-bapa-ajax');
Route::get('/service_accounts/rename-bapa', [ServiceAccountsController::class, 'renameBapa'])->name('serviceAccounts.rename-bapa');
Route::get('/service_accounts/validate-old-account-no', [ServiceAccountsController::class, 'validateOlAccountNo'])->name('serviceAccounts.validate-old-account-no');
Route::get('/service_accounts/manual-account-migration-one', [ServiceAccountsController::class, 'manualAccountMigrationOne'])->name('serviceAccounts.manual-account-migration-one');
Route::post('/service_accounts/store-manual', [ServiceAccountsController::class, 'storeManual'])->name('serviceAccounts.store-manual');
Route::get('/service_accounts/manual-account-migration-two/{id}', [ServiceAccountsController::class, 'manualAccountMigrationTwo'])->name('serviceAccounts.manual-account-migration-two');
Route::post('/service_accounts/store-meters-manual', [ServiceAccountsController::class, 'storeMetersManual'])->name('serviceAccounts.store-meters-manual');
Route::get('/service_accounts/manual-account-migration-three/{id}', [ServiceAccountsController::class, 'manualAccountMigrationThree'])->name('serviceAccounts.manual-account-migration-three');
Route::post('/service_accounts/store-transformer-manual', [ServiceAccountsController::class, 'storeTransformerManual'])->name('serviceAccounts.store-transformer-manual');
Route::get('/service_accounts/change-meter-manual', [ServiceAccountsController::class, 'changeMeterManual'])->name('serviceAccounts.change-meter-manual');
Route::get('/service_accounts/change-meter-manual-console/{id}', [ServiceAccountsController::class, 'changeMeterManualConsole'])->name('serviceAccounts.change-meter-manual-console');
Route::post('/service_accounts/store-change-meter-manual', [ServiceAccountsController::class, 'storeChangeMeterManual'])->name('serviceAccounts.store-change-meter-manual');
Route::get('/service_accounts/relocation-manual', [ServiceAccountsController::class, 'relocationManual'])->name('serviceAccounts.relocation-manual');
Route::get('/service_accounts/relocation-form-manual/{id}', [ServiceAccountsController::class, 'relocationFormManual'])->name('serviceAccounts.relocation-form-manual');
Route::get('/service_accounts/print-group-bills-list/{period}/{groupId}', [ServiceAccountsController::class, 'printGroupBillsList'])->name('serviceAccounts.print-group-bills-list');
Route::get('/service_accounts/check-available-account-numbers', [ServiceAccountsController::class, 'checkAvailableAccountNumbers'])->name('serviceAccounts.check-available-account-numbers');
Route::resource('serviceAccounts', ServiceAccountsController::class);


Route::resource('serviceConnectionLgLoadInsps', App\Http\Controllers\ServiceConnectionLgLoadInspController::class);


Route::get('/structures/get-structures-json', [App\Http\Controllers\StructuresController::class, 'getStructuresJson'])->name('structures.get-structures-json');
Route::get('/structures/get-structures-by-type', [App\Http\Controllers\StructuresController::class, 'getStructuresByType'])->name('structures.get-structures-by-type');
Route::resource('structures', App\Http\Controllers\StructuresController::class);


Route::resource('materialAssets', App\Http\Controllers\MaterialAssetsController::class);


Route::resource('materialsMatrices', App\Http\Controllers\MaterialsMatrixController::class);


Route::resource('billOfMaterialsIndices', App\Http\Controllers\BillOfMaterialsIndexController::class);


Route::resource('billOfMaterialsDetails', App\Http\Controllers\BillOfMaterialsDetailsController::class);

Route::post('/structure_assignments/insert-structure-assignment', [App\Http\Controllers\StructureAssignmentsController::class, 'insertStructureAssignment']);
Route::get('/structure_assignments/delete-brackets', [App\Http\Controllers\StructureAssignmentsController::class, 'deleteBrackets'])->name('structureAssignments.delete-brackets');
Route::get('/structure_assignments/get-bracket-structure', [App\Http\Controllers\StructureAssignmentsController::class, 'getBracketStructure'])->name('structureAssignments.get-bracket-structure');
Route::resource('structureAssignments', App\Http\Controllers\StructureAssignmentsController::class);


Route::get('/bill_of_materials_matrices/view/{scId}', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'view'])->name('billOfMaterialsMatrices.view');
Route::get('/bill_of_materials_matrices/download-bill-of-materials/{scId}',  [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'downloadBillOfMaterials'])->name('billOfMaterialsMatrices.download-bill-of-materials');
Route::get('/bill_of_materials_matrices/get-bill-of-materials-json/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'getBillOfMaterialsJson'])->name('billOfMaterialsMatrices.get-bill-of-materials-json');
Route::post('/bill_of_materials_matrices/insert-transformer-bracket', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertTransformerBracket'])->name('billOfMaterialsMatrices.insert-transformer-bracket');
Route::get('/bill_of_materials_matrices/get-bill-of-materials-brackets/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'getBillOfMaterialsBrackets'])->name('billOfMaterialsMatrices.get-bill-of-materials-brackets');
Route::post('/bill_of_materials_matrices/insert-pole', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertPole'])->name('billOfMaterialsMatrices.insert-pole');
Route::get('/bill_of_materials_matrices/fetch-poles/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'fetchPoles'])->name('billOfMaterialsMatrices.fetch-poles');
Route::get('/bill_of_materials_matrices/delete-pole/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'deletePole'])->name('billOfMaterialsMatrices.delete-pole');
Route::get('/bill_of_materials_matrices/delete-material/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'deleteMaterial'])->name('billOfMaterialsMatrices.delete-material');
Route::post('/bill_of_materials_matrices/add-custom-material', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'addCustomMaterial'])->name('billOfMaterialsMatrices.add-custom-material');
Route::post('/bill_of_materials_matrices/insert-spanning-materials', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertSpanningMaterials'])->name('billOfMaterialsMatrices.insert-spanning-materials');
Route::get('/bill_of_materials_matrices/fetch-span-material/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'fetchSpanMaterials'])->name('billOfMaterialsMatrices.fetch-span-material');
Route::get('/bill_of_materials_matrices/delete-span-material/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'deleteSpanMaterial'])->name('billOfMaterialsMatrices.delete-span-material');
Route::post('/bill_of_materials_matrices/insert-sdw-materials', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertSDWMaterials'])->name('billOfMaterialsMatrices.insert-sdw-materials');
Route::post('/bill_of_materials_matrices/insert-special-equipment', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertSpecialEquipment'])->name('billOfMaterialsMatrices.insert-special-equipment');
Route::get('/bill_of_materials_matrices/fetch-equipments/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'fetchEquipments'])->name('billOfMaterialsMatrices.fetch-equipments');
Route::resource('billOfMaterialsMatrices', App\Http\Controllers\BillOfMaterialsMatrixController::class);


Route::resource('transformerIndices', App\Http\Controllers\TransformerIndexController::class);

Route::post('/transformers_assigned_matrices/create-ajax', [App\Http\Controllers\TransformersAssignedMatrixController::class, 'createAjax'])->name('transformersAssignedMatrices.create-ajax');
Route::get('/transformers_assigned_matrices/fetch-transformers', [App\Http\Controllers\TransformersAssignedMatrixController::class, 'fetchTransformers'])->name('transformersAssignedMatrices.fetch-transformers');
Route::resource('transformersAssignedMatrices', App\Http\Controllers\TransformersAssignedMatrixController::class);


Route::resource('poleIndices', App\Http\Controllers\PoleIndexController::class);


Route::resource('billsOfMaterialsSummaries', App\Http\Controllers\BillsOfMaterialsSummaryController::class);


Route::resource('spanningIndices', App\Http\Controllers\SpanningIndexController::class);


Route::resource('spanningDatas', App\Http\Controllers\SpanningDataController::class);


Route::resource('preDefinedMaterials', App\Http\Controllers\PreDefinedMaterialsController::class);


Route::post('/preDefinedMaterialsMatrices/update-data/', [App\Http\Controllers\PreDefinedMaterialsMatrixController::class, 'updateData']);
Route::get('/preDefinedMaterialsMatrices/re-init/{scId}/{options}', [App\Http\Controllers\PreDefinedMaterialsMatrixController::class, 'reInit'])->name('preDefinedMaterialsMatrices.re-init');
Route::post('/preDefinedMaterialsMatrices/add-material/', [App\Http\Controllers\PreDefinedMaterialsMatrixController::class, 'addMaterial']);
Route::resource('preDefinedMaterialsMatrices', App\Http\Controllers\PreDefinedMaterialsMatrixController::class);


Route::post('/member_consumer_images/create-image/', [App\Http\Controllers\MemberConsumerImagesController::class, 'createImage'])->name('memberConsumerImages.create-image');
Route::get('/member_consumer_images/get-image/{id}', [App\Http\Controllers\MemberConsumerImagesController::class, 'getImage'])->name('memberConsumerImages.get-image');
Route::resource('memberConsumerImages', App\Http\Controllers\MemberConsumerImagesController::class);


Route::get('/tickets/create-select', [TicketsController::class, 'createSelect'])->name('tickets.create-select');
Route::get('/tickets/get-create-ajax', [TicketsController::class, 'getCreateAjax'])->name('tickets.get-create-ajax');
Route::get('/tickets/create-new/{id}', [TicketsController::class, 'createNew'])->name('tickets.create-new');
Route::get('/tickets/fetch-tickets', [TicketsController::class, 'fetchTickets'])->name('tickets.fetch-tickets');
Route::get('/tickets/print-ticket/{id}', [TicketsController::class, 'printTicket'])->name('tickets.print-ticket');
Route::get('/tickets/trash', [TicketsController::class, 'trash'])->name('tickets.trash');
Route::get('/tickets/restore-ticket/{id}', [TicketsController::class, 'restoreTicket'])->name('tickets.restore-ticket');
Route::post('/tickets/update-date-filed', [TicketsController::class, 'updateDateFiled'])->name('tickets.update-date-filed');
Route::post('/tickets/update-date-downloaded', [TicketsController::class, 'updateDateDownloaded'])->name('tickets.update-date-downloaded');
Route::post('/tickets/update-date-arrival', [TicketsController::class, 'updateDateArrival'])->name('tickets.update-date-arrival');
Route::post('/tickets/update-execution', [TicketsController::class, 'updateExecution'])->name('tickets.update-execution');
Route::get('/tickets/dashboard', [TicketsController::class, 'dashboard'])->name('tickets.dashboard');
Route::get('/tickets/fetch-dashboard-tickets-trend', [TicketsController::class, 'fetchDashboardTicketsTrend'])->name('tickets.fetch-dashboard-tickets-trend');
Route::get('/tickets/get-ticket-statistics', [TicketsController::class, 'getTicketStatistics'])->name('tickets.get-ticket-statistics');
Route::get('/tickets/get-ticket-statistics-details', [TicketsController::class, 'getTicketStatisticsDetails'])->name('tickets.get-ticket-statistics-details');
Route::get('/tickets/kps-monitor', [TicketsController::class, 'kpsMonitor'])->name('tickets.kps-monitor');
Route::get('/tickets/get-kps-ticket-crew-graph', [TicketsController::class, 'getKpsTicketCrewGraph'])->name('tickets.get-kps-ticket-crew-graph');
Route::get('/tickets/get-ticket-avg-hours', [TicketsController::class, 'getTicketCrewAverageHours'])->name('tickets.get-ticket-avg-hours');
Route::get('/tickets/get-overall-avg-kps', [TicketsController::class, 'getOverAllAverageKps'])->name('tickets.get-overall-avg-kps');
Route::get('/tickets/change-meter', [TicketsController::class, 'changeMeter'])->name('tickets.change-meter');
Route::get('/tickets/create-change-meter/{accountNumber}', [TicketsController::class, 'createChangeMeter'])->name('tickets.create-change-meter');
Route::get('/tickets/assessments-change-meter', [TicketsController::class, 'changeMeterAssessments'])->name('tickets.assessments-change-meter');
Route::get('/tickets/assessments-ordinary-ticket', [TicketsController::class, 'ordinaryTicketsAssessment'])->name('tickets.assessments-ordinary-ticket');
Route::get('/tickets/assess-change-meter-form/{ticketId}', [TicketsController::class, 'assessChangeMeterForm'])->name('tickets.assess-change-meter-form');
Route::post('/tickets/update-change-meter-assessment', [TicketsController::class, 'updateChangeMeterAssessment'])->name('tickets.update-change-meter-assessment');
Route::post('/tickets/update-ordinary-ticket-assessment', [TicketsController::class, 'updateOrdinaryTicketAssessment'])->name('tickets.update-ordinary-ticket-assessment');
Route::get('/tickets/ticket-summary-report', [TicketsController::class, 'ticketSummaryReport'])->name('tickets.ticket-summary-report');
Route::get('/tickets/get-ticket-summary-report', [TicketsController::class, 'getTicketSummaryResults'])->name('tickets.get-ticket-summary-report');
Route::get('/tickets/ticket-summary-report-download-route', [TicketsController::class, 'ticketSummaryReportDownloadRoute'])->name('tickets.ticket-summary-report-download-route');
Route::get('/tickets/download-tickets-summary-report/{ticketParam}/{from}/{to}/{area}/{status}', [TicketsController::class, 'downloadTicketsSummaryReport'])->name('tickets.download-tickets-summary-report');
Route::get('/tickets/disconnection-assessments', [TicketsController::class, 'disconnectionAssessments'])->name('tickets.disconnection-assessments');
Route::get('/tickets/get-disconnection-results', [TicketsController::class, 'getDisconnectionResults'])->name('tickets.get-disconnection-results');
Route::get('/tickets/disconnection-results-route', [TicketsController::class, 'disconnectionResultsRoute'])->name('tickets.disconnection-results-route');
Route::get('/tickets/create-and-print-disconnection-tickets/{period}/{route}', [TicketsController::class, 'createAndPrintDisconnectionTickets'])->name('tickets.create-and-print-disconnection-tickets');
Route::get('/tickets/ticket-tally', [TicketsController::class, 'ticketTally'])->name('tickets.ticket-tally');
Route::get('/tickets/get-ticket-tally', [TicketsController::class, 'getTicketTally'])->name('tickets.get-ticket-tally');
Route::get('/tickets/get-crew-monitor-data', [TicketsController::class, 'getCrewMonitorData'])->name('tickets.get-crew-monitor-data');
Route::get('/tickets/relocation-search', [TicketsController::class, 'relocationSearch'])->name('tickets.relocation-search');
Route::get('/tickets/create-relocation/{id}', [TicketsController::class, 'createRelocation'])->name('tickets.create-relocation');
Route::post('/tickets/store-relocation', [TicketsController::class, 'storeRelocation'])->name('tickets.store-relocation');
Route::get('/tickets/crew-field-monitor', [TicketsController::class, 'crewFieldMonitor'])->name('tickets.crew-field-monitor');
Route::get('/tickets/get-crew-field-monitor-data', [TicketsController::class, 'getCrewFieldMonitorData'])->name('tickets.get-crew-field-monitor-data');
Route::get('/tickets/save-ticket-log', [TicketsController::class, 'saveTicketLog'])->name('tickets.save-ticket-log');
Route::get('/tickets/nea-kps-summary', [TicketsController::class, 'neaKpsSummary'])->name('tickets.nea-kps-summary');
Route::get('/tickets/download-kps-summary-report/{town}/{from}/{to}', [TicketsController::class, 'downloadKpsSummaryReport'])->name('tickets.download-kps-summary-report');
Route::get('/tickets/kps-customer-service-parameters', [TicketsController::class, 'kpsCustomerServiceParameters'])->name('tickets.kps-customer-service-parameters');
Route::get('/tickets/monthly-per-town', [TicketsController::class, 'monthlyPerTown'])->name('tickets.monthly-per-town');
Route::get('/tickets/download-monthly-per-town/{month}/{year}', [TicketsController::class, 'downoadMonthlyPerTown'])->name('tickets.download-monthly-per-town');
Route::get('/tickets/change-meter-unconfirmed', [TicketsController::class, 'changeMeterUnconfirmed'])->name('tickets.change-meter-unconfirmed');
Route::get('/tickets/change-meter-update/{id}', [TicketsController::class, 'changeMeterUpdate'])->name('tickets.change-meter-update');
Route::get('/tickets/mark-as-change-meter-done', [TicketsController::class, 'markAsChangeMeterDone'])->name('tickets.mark-as-change-meter-done');
Route::get('/tickets/pending-change-meters', [TicketsController::class, 'pendingChangeMeters'])->name('tickets.pending-change-meters');
Route::get('/tickets/update-crew-ajax', [TicketsController::class, 'updateCrewAjax'])->name('tickets.update-crew-ajax');
Route::get('/tickets/crew-assigning-metering', [TicketsController::class, 'crewAssigningMetering'])->name('tickets.crew-assigning-metering');
Route::get('/tickets/meter-inspections', [TicketsController::class, 'meterInspections'])->name('tickets.meter-inspections');
Route::get('/tickets/meter-replacements', [TicketsController::class, 'meterReplacements'])->name('tickets.meter-replacements');
Route::get('/tickets/download-meter-replacements/{from}/{to}/{office}', [TicketsController::class, 'downloadMeterReplacements'])->name('tickets.download-meter-replacements');
Route::get('/tickets/disco-reco-reports', [TicketsController::class, 'disconnectionReconnectionReport'])->name('tickets.disco-reco-reports');
Route::get('/tickets/download-disco-reco/{from}/{to}/{office}', [TicketsController::class, 'downloadDiscoReco'])->name('tickets.download-disco-reco');
Route::get('/tickets/get-crew-from-station', [TicketsController::class, 'getCrewFromStation'])->name('tickets.get-crew-from-station');
Route::get('/tickets/forward-to-esd', [TicketsController::class, 'forwardToESD'])->name('tickets.forward-to-esd');
Route::get('/tickets/undo-forward', [TicketsController::class, 'undoForward'])->name('tickets.undo-forward');
Route::get('/tickets/account-finder', [TicketsController::class, 'accountFinder'])->name('tickets.account-finder');
Route::get('/tickets/load-account-in-map', [TicketsController::class, 'loadAccountInMap'])->name('tickets.load-account-in-map');
Route::get('/tickets/get-tickets-from-station', [TicketsController::class, 'getTicketsFromStation'])->name('tickets.get-tickets-from-station');
Route::get('/tickets/execution', [TicketsController::class, 'execution'])->name('tickets.execution');
Route::get('/tickets/update-execution-data', [TicketsController::class, 'updateExecutionData'])->name('tickets.update-execution-data');
Route::get('/tickets/meter-transfers', [TicketsController::class, 'meterTransfers'])->name('tickets.meter-transfers');
Route::get('/tickets/download-meter-transfers/{from}/{to}/{office}', [TicketsController::class, 'downloadMeterTransfers'])->name('tickets.download-meter-transfers');
Route::get('/tickets/service-conductor-transfers', [TicketsController::class, 'serviceConductorTransfers'])->name('tickets.service-conductor-transfers');
Route::get('/tickets/download-service-conductor-transfers/{from}/{to}/{office}', [TicketsController::class, 'downloadServiceConductorTransfers'])->name('tickets.download-service-conductor-transfers');
Route::get('/tickets/meter-transfer-inspections', [TicketsController::class, 'meterTransferInspections'])->name('tickets.meter-transfer-inspections');
Route::post('/tickets/update-status-and-crew', [TicketsController::class, 'updateStatusAndCrew'])->name('tickets.update-status-and-crew');
Route::get('/tickets/update-transfer-inspection-data', [TicketsController::class, 'updateTransferInspectionData'])->name('tickets.update-transfer-inspection-data');
Route::get('/tickets/print-ticket-go-back/{id}', [TicketsController::class, 'printTicketGoBack'])->name('tickets.print-ticket-go-back');
Route::get('/tickets/executed-reconnections', [TicketsController::class, 'executedReconnections'])->name('tickets.executed-reconnections');
Route::get('/tickets/download-reconnections/{from}/{to}/{office}', [TicketsController::class, 'downloadReconnections'])->name('tickets.download-reconnections');
Route::get('/tickets/quarterly-report', [TicketsController::class, 'quarterlyReport'])->name('tickets.quarterly-report');
Route::get('/tickets/download-quarterly-report/{town}/{q}/{year}', [TicketsController::class, 'downloadQuarterlyReport'])->name('tickets.download-quarterly-report');
Route::get('/tickets/confirm-change-meter', [TicketsController::class, 'confirmChangeMeter'])->name('tickets.confirm-change-meter');
Route::get('/tickets/get-ticket-ajax', [TicketsController::class, 'getTicketAjax'])->name('tickets.get-ticket-ajax');
Route::get('/tickets/get-meter-details', [TicketsController::class, 'getMeterDetails'])->name('tickets.get-meter-details');
Route::get('/tickets/not-executed', [TicketsController::class, 'notExecuted'])->name('tickets.not-executed');
Route::get('/tickets/print-not-executed/{from}/{to}/{area}', [TicketsController::class, 'printNotExecuted'])->name('tickets.print-not-executed');
Route::get('/tickets/print-change-meter-accomplished/{from}/{to}', [TicketsController::class, 'printChangeMeterAccomplished'])->name('tickets.print-change-meter-accomplished');
Route::get('/tickets/print-change-meters/{from}/{to}/{office}', [TicketsController::class, 'printChangeMeters'])->name('tickets.print-change-meters');
Route::resource('tickets', TicketsController::class);


Route::resource('ticketsRepositories', App\Http\Controllers\TicketsRepositoryController::class);


Route::resource('ticketLogs', App\Http\Controllers\TicketLogsController::class);

Route::post('/special_equipment_materials/create-material', [App\Http\Controllers\SpecialEquipmentMaterialsController::class, 'createEquipment']);
Route::resource('specialEquipmentMaterials', App\Http\Controllers\SpecialEquipmentMaterialsController::class);


Route::resource('serviceConnectionImages', App\Http\Controllers\ServiceConnectionImagesController::class);


Route::resource('billingTransformers', App\Http\Controllers\BillingTransformersController::class);


Route::resource('billingMeters', App\Http\Controllers\BillingMetersController::class);


Route::resource('meterReaders', App\Http\Controllers\MeterReadersController::class);


Route::resource('meterReaderTrackNames', App\Http\Controllers\MeterReaderTrackNamesController::class);

Route::get('/meter_reader_tracks/get-tracks-by-tracknameid', [App\Http\Controllers\MeterReaderTracksController::class, 'getTracksByTrackNameId'])->name('meterReaderTracks.get-tracks-by-tracknameid');
Route::resource('meterReaderTracks', App\Http\Controllers\MeterReaderTracksController::class);


Route::get('/damage_assessments/get-objects', [App\Http\Controllers\DamageAssessmentController::class, 'getObjects'])->name('damageAssessments.get-objects');
Route::get('/damage_assessments/search-pole', [App\Http\Controllers\DamageAssessmentController::class, 'searchPole'])->name('damageAssessments.search-pole');
Route::get('/damage_assessments/view-pole', [App\Http\Controllers\DamageAssessmentController::class, 'viewPole'])->name('damageAssessments.view-pole');
Route::post('/damage_assessments/update-ajax', [App\Http\Controllers\DamageAssessmentController::class, 'updateAjax'])->name('damageAssessments.update-ajax');
Route::resource('damageAssessments', App\Http\Controllers\DamageAssessmentController::class);


Route::get('/reading_schedules/update-schedule/{userId}', [App\Http\Controllers\ReadingSchedulesController::class, 'updateSchedule'])->name('readingSchedules.update-schedule');
Route::get('/reading_schedules/view-schedule/{userId}', [App\Http\Controllers\ReadingSchedulesController::class, 'viewSchedule'])->name('readingSchedules.view-schedule');
Route::get('/reading_schedules/get-latest-schedule', [App\Http\Controllers\ReadingSchedulesController::class, 'getLatestSchedule'])->name('readingSchedules.get-latest-schedule');
Route::get('/reading_schedules/reading-schedule-index', [App\Http\Controllers\ReadingSchedulesController::class, 'readingScheduleIndex'])->name('readingSchedules.reading-schedule-index');
Route::get('/reading_schedules/view-meter-reading-scheds-in-period/{period}', [App\Http\Controllers\ReadingSchedulesController::class, 'viewMeterReadingSchedsInPeriod'])->name('readingSchedules.view-meter-reading-scheds-in-period');
Route::get('/reading_schedules/create-reading-schedule', [App\Http\Controllers\ReadingSchedulesController::class, 'createReadingSchedule'])->name('readingSchedules.create-reading-schedule');
Route::post('/reading_schedules/store-reading-schedule', [App\Http\Controllers\ReadingSchedulesController::class, 'storeReadingSchedules'])->name('readingSchedules.store-reading-schedule');
Route::resource('readingSchedules', App\Http\Controllers\ReadingSchedulesController::class);


Route::get('/rates/upload-rate', [App\Http\Controllers\RatesController::class, 'uploadRate'])->name('rates.upload-rate');
Route::post('/rates/validate-rate-upload', [App\Http\Controllers\RatesController::class, 'validateRateUpload'])->name('rates.validate-rate-upload');
Route::get('/rates/view-rates/{servicePeriod}', [App\Http\Controllers\RatesController::class, 'viewRates'])->name('rates.view-rates');
Route::post('/rates/delete-rates/{servicePeriod}', [App\Http\Controllers\RatesController::class, 'deleteRates'])->name('rates.delete-rates');
Route::resource('rates', App\Http\Controllers\RatesController::class);

Route::get('/readings/reading-monitor', [App\Http\Controllers\ReadingsController::class, 'readingMonitor'])->name('readings.reading-monitor');
Route::get('/readings/reading-monitor-view/{servicePeriod}', [App\Http\Controllers\ReadingsController::class, 'readingMonitorView'])->name('readings.reading-monitor-view');
Route::get('/readings/get-readings-from-meter-reader', [App\Http\Controllers\ReadingsController::class, 'getReadingsFromMeterReader'])->name('readings.get-readings-from-meter-reader');
Route::get('/readings/manual-reading', [App\Http\Controllers\ReadingsController::class, 'manualReading'])->name('readings.manual-reading');
Route::get('/readings/manual-reading-console/{id}', [App\Http\Controllers\ReadingsController::class, 'manualReadingConsole'])->name('readings.manual-reading-console');
Route::get('/readings/captured-readings-console/{id}/{readId}/{day}/{bapaName}', [App\Http\Controllers\ReadingsController::class, 'capturedReadingsConsole'])->name('readings.captured-readings-console');
Route::get('/readings/get-computed-bill', [App\Http\Controllers\ReadingsController::class, 'getComputedBill'])->name('readings.get-computed-bill');
Route::post('/readings/create-manual-billing', [App\Http\Controllers\ReadingsController::class, 'createManualBilling'])->name('readings.create-manual-billing');
Route::get('/readings/captured-readings', [App\Http\Controllers\ReadingsController::class, 'capturedReadings'])->name('readings.captured-readings');
Route::get('/readings/mark-as-done', [App\Http\Controllers\ReadingsController::class, 'markAsDone'])->name('readings.mark-as-done');
Route::get('/readings/fetch-account', [App\Http\Controllers\ReadingsController::class, 'fetchAccount'])->name('readings.fetch-account');
Route::get('/readings/view-full-report/{period}/{meterReader}/{day}/{town}', [App\Http\Controllers\ReadingsController::class, 'viewFullReport'])->name('readings.view-full-report');
Route::get('/readings/view-full-report-bapa/{period}/{bapaName}', [App\Http\Controllers\ReadingsController::class, 'viewFullReportBapa'])->name('readings.view-full-report-bapa');
Route::get('/readings/get-previous-readings', [App\Http\Controllers\ReadingsController::class, 'getPreviousReadings'])->name('readings.get-previous-readings');
Route::get('/readings/create-manual-billing-ajax', [App\Http\Controllers\ReadingsController::class, 'createManualBillingAjax'])->name('readings.create-manual-billing-ajax');
Route::get('/readings/check-if-account-has-bill', [App\Http\Controllers\ReadingsController::class, 'checkIfAccountHasBill'])->name('readings.check-if-account-has-bill');
Route::post('/readings/create-bill-for-captured-reading', [App\Http\Controllers\ReadingsController::class, 'createBillForCapturedReading'])->name('readings.create-bill-for-captured-reading');
Route::get('/readings/print-old-format-adjusted/{period}/{day}/{town}/{meterReader}', [App\Http\Controllers\ReadingsController::class, 'printOldFormatAdjusted'])->name('readings.print-old-format-adjusted');
Route::get('/readings/print-new-format-adjusted/{period}/{day}/{town}/{meterReader}', [App\Http\Controllers\ReadingsController::class, 'printNewFormatAdjusted'])->name('readings.print-new-format-adjusted');
Route::get('/readings/print-old-format-adjusted-bapa/{period}/{bapaName}', [App\Http\Controllers\ReadingsController::class, 'printOldFormatAdjustedBapa'])->name('readings.print-old-format-adjusted-bapa');
Route::get('/readings/print-new-format-adjusted-bapa/{period}/{bapaName}', [App\Http\Controllers\ReadingsController::class, 'printNewFormatAdjustedBapa'])->name('readings.print-new-format-adjusted-bapa');
Route::get('/readings/print-unbilled-by-status/{period}/{day}/{town}/{meterReader}/{status}', [App\Http\Controllers\ReadingsController::class, 'printUnbilledList'])->name('readings.print-unbilled-by-status');
Route::get('/readings/print-other-unbilled-list/{period}/{day}/{town}/{meterReader}', [App\Http\Controllers\ReadingsController::class, 'printOtherUnbilledList'])->name('readings.print-other-unbilled-list');
Route::get('/readings/billed-and-unbilled-reports', [App\Http\Controllers\ReadingsController::class, 'billAndUnbilledReport'])->name('readings.billed-and-unbilled-reports');
Route::get('/readings/print-billed-unbilled/{type}/{meterReader}/{day}/{period}/{town}', [App\Http\Controllers\ReadingsController::class, 'printBilledUnbilled'])->name('readings.print-billed-unbilled');
Route::get('/readings/print-disco-active/{meterReader}/{day}/{period}/{town}', [App\Http\Controllers\ReadingsController::class, 'printDiscoActive'])->name('readings.print-disco-active');
Route::get('/readings/billed-and-unbilled-reports-bapa', [App\Http\Controllers\ReadingsController::class, 'billAndUnbilledReportBapa'])->name('readings.billed-and-unbilled-reports-bapa');
Route::get('/readings/print-billed-unbilled-bapa/{type}/{bapaName}/{period}/{town}', [App\Http\Controllers\ReadingsController::class, 'printBilledUnbilledBapa'])->name('readings.print-billed-unbilled-bapa');
Route::get('/readings/efficiency-report', [App\Http\Controllers\ReadingsController::class, 'efficiencyReport'])->name('readings.efficiency-report');
Route::get('/readings/print-bapa-reading-list', [App\Http\Controllers\ReadingsController::class, 'printBapaReadingList'])->name('readings.print-bapa-reading-list');
Route::get('/readings/search-print-bapa-reading-list', [App\Http\Controllers\ReadingsController::class, 'searchPrintBapaReadingList'])->name('readings.search-print-bapa-reading-list');
Route::get('/readings/print-bapa-reading-list-to-paper/{bapaName}/{period}', [App\Http\Controllers\ReadingsController::class, 'printBapaReadingListToPaper'])->name('readings.print-bapa-reading-list-to-paper');
Route::get('/readings/print-bulk-new-format-mreader/{period}/{day}/{town}/{mreader}', [App\Http\Controllers\ReadingsController::class, 'printBulkBillNewFormatMreader'])->name('readings.print-bulk-new-format-mreader');
Route::get('/readings/print-bulk-old-format-mreader/{period}/{day}/{town}/{mreader}', [App\Http\Controllers\ReadingsController::class, 'printBulkBillOldFormatMreader'])->name('readings.print-bulk-old-format-mreader');
Route::resource('readings', App\Http\Controllers\ReadingsController::class);

Route::resource('readingImages', App\Http\Controllers\ReadingImagesController::class);

Route::get('/collectibles/ledgerize', [App\Http\Controllers\CollectiblesController::class, 'ledgerize'])->name('collectibles.ledgerize');
Route::get('/collectibles/add-to-month', [App\Http\Controllers\CollectiblesController::class, 'addToMonth'])->name('collectibles.add-to-month');
Route::post('/collectibles/clear-ledger/{id}', [App\Http\Controllers\CollectiblesController::class, 'clearLedger'])->name('collectibles.clear-ledger');
Route::resource('collectibles', App\Http\Controllers\CollectiblesController::class);


Route::resource('arrearsLedgerDistributions', App\Http\Controllers\ArrearsLedgerDistributionController::class);

Route::get('/transaction_indices/service-connection-collection', [App\Http\Controllers\TransactionIndexController::class, 'serviceConnectionCollection'])->name('transactionIndices.service-connection-collection');
Route::get('/transaction_indices/get-payable-details', [App\Http\Controllers\TransactionIndexController::class, 'getPayableDetails'])->name('transactionIndices.get-payable-details');
Route::get('/transaction_indices/get-payable-total', [App\Http\Controllers\TransactionIndexController::class, 'getPayableTotal'])->name('transactionIndices.get-payable-total');
Route::get('/transaction_indices/get-power-load-payables', [App\Http\Controllers\TransactionIndexController::class, 'getPowerLoadPayables'])->name('transactionIndices.get-power-load-payables');
Route::get('/transaction_indices/save-and-print-or-service-connections', [App\Http\Controllers\TransactionIndexController::class, 'saveAndPrintORServiceConnections'])->name('transactionIndices.save-and-print-or-service-connections');
Route::get('/transaction_indices/print-or-service-connections/{transactionIndexId}', [App\Http\Controllers\TransactionIndexController::class, 'printORServiceConnections'])->name('transactionIndices.print-or-service-connections');
Route::get('/transaction_indices/uncollected-arrears', [App\Http\Controllers\TransactionIndexController::class, 'uncollectedArrears'])->name('transactionIndices.uncollected-arrears');
Route::get('/transaction_indices/search-arrear-collectibles', [App\Http\Controllers\TransactionIndexController::class, 'searchArrearCollectibles'])->name('transactionIndices.search-arrear-collectibles');
Route::get('/transaction_indices/fetch-arrear-details', [App\Http\Controllers\TransactionIndexController::class, 'fetchArrearDetails'])->name('transactionIndices.fetch-arrear-details');
Route::get('/transaction_indices/save-arrear-transaction', [App\Http\Controllers\TransactionIndexController::class, 'saveArrearTransaction'])->name('transactionIndices.save-arrear-transaction');
Route::get('/transaction_indices/ledger-arrears-collection/{accountNo}', [App\Http\Controllers\TransactionIndexController::class, 'ledgerArrearsCollection'])->name('transactionIndices.ledger-arrears-collection');
Route::get('/transaction_indices/save-ledger-arrear-transaction', [App\Http\Controllers\TransactionIndexController::class, 'saveLedgerArrearTransaction'])->name('transactionIndices.save-ledger-arrear-transaction');
Route::get('/transaction_indices/print-or-termed-ledger-arrears/{transactionIndexId}', [App\Http\Controllers\TransactionIndexController::class, 'printORTermedLedgerArrears'])->name('transactionIndices.print-or-termed-ledger-arrears');
Route::get('/transaction_indices/other-payments', [App\Http\Controllers\TransactionIndexController::class, 'otherPayments'])->name('transactionIndices.other-payments');
Route::get('/transaction_indices/search-consumer', [App\Http\Controllers\TransactionIndexController::class, 'searchConsumer'])->name('transactionIndices.search-consumer');
Route::get('/transaction_indices/fetch-account-details', [App\Http\Controllers\TransactionIndexController::class, 'fetchAccountDetails'])->name('transactionIndices.fetch-account-details');
Route::get('/transaction_indices/fetch-payable-details', [App\Http\Controllers\TransactionIndexController::class, 'fetchPayableDetails'])->name('transactionIndices.fetch-payable-details');
Route::get('/transaction_indices/print-other-payments/{transactionIndexId}', [App\Http\Controllers\TransactionIndexController::class, 'printOtherPayments'])->name('transactionIndices.print-other-payments');
Route::get('/transaction_indices/reconnection-collection', [App\Http\Controllers\TransactionIndexController::class, 'reconnectionCollection'])->name('transactionIndices.reconnection-collection');
Route::get('/transaction_indices/search-disconnected-consumers', [App\Http\Controllers\TransactionIndexController::class, 'searchDisconnectedConsumers'])->name('transactionIndices.search-disconnected-consumers');
Route::get('/transaction_indices/get-arrears-data', [App\Http\Controllers\TransactionIndexController::class, 'getArrearsData'])->name('transactionIndices.get-arrears-data');
Route::get('/transaction_indices/save-reconnection-transaction', [App\Http\Controllers\TransactionIndexController::class, 'saveReconnectionTransaction'])->name('transactionIndices.save-reconnection-transaction');
Route::get('/transaction_indices/add-check-payment', [App\Http\Controllers\TransactionIndexController::class, 'addCheckPayment'])->name('transactionIndices.add-check-payment');
Route::get('/transaction_indices/delete-check-payment', [App\Http\Controllers\TransactionIndexController::class, 'deleteCheckPayment'])->name('transactionIndices.delete-check-payment');
Route::get('/transaction_indices/browse-ors', [App\Http\Controllers\TransactionIndexController::class, 'browseORs'])->name('transactionIndices.browse-ors');
Route::get('/transaction_indices/browse-ors-view/{id}/{paymentType}', [App\Http\Controllers\TransactionIndexController::class, 'browseORView'])->name('transactionIndices.browse-ors-view');
Route::get('/transaction_indices/print-or-transactions/{transactionIndexId}', [App\Http\Controllers\TransactionIndexController::class, 'printOrTransactions'])->name('transactionIndices.print-or-transactions');
Route::get('/transaction_indices/print-reconnection-collection/{transactionIndexId}', [App\Http\Controllers\TransactionIndexController::class, 'printOrReconnection'])->name('transactionIndices.print-reconnection-collection');
Route::get('/transaction_indices/or-maintenance', [App\Http\Controllers\TransactionIndexController::class, 'orMaintenance'])->name('transactionIndices.or-maintenance');
Route::get('/transaction_indices/update-or-number', [App\Http\Controllers\TransactionIndexController::class, 'updateORNumber'])->name('transactionIndices.update-or-number');
Route::resource('transactionIndices', App\Http\Controllers\TransactionIndexController::class);


Route::resource('transactionDetails', App\Http\Controllers\TransactionDetailsController::class);

Route::get('/disconnection_histories/generate-turn-off-list', [App\Http\Controllers\DisconnectionHistoryController::class, 'generateTurnOffList'])->name('disconnectionHistories.generate-turn-off-list');
Route::get('/disconnection_histories/get-turn-off-list-preview', [App\Http\Controllers\DisconnectionHistoryController::class, 'getTurnOffListPreview'])->name('disconnectionHistories.get-turn-off-list-preview');
Route::get('/disconnection_histories/get-turn-off-list-preview-route', [App\Http\Controllers\DisconnectionHistoryController::class, 'getTurnOffListPreviewRoute'])->name('disconnectionHistories.get-turn-off-list-preview-route');
Route::get('/disconnection_histories/print-turn-off-list/{period}/{area}/{meterReader}/{day}', [App\Http\Controllers\DisconnectionHistoryController::class, 'printTurnOffList'])->name('disconnectionHistories.print-turn-off-list');
Route::get('/disconnection_histories/print-turn-off-list-route/{period}/{area}/{route}', [App\Http\Controllers\DisconnectionHistoryController::class, 'printTurnOffListRoute'])->name('disconnectionHistories.print-turn-off-list-route');
Route::resource('disconnectionHistories', App\Http\Controllers\DisconnectionHistoryController::class);

Route::get('/disco_notice_histories/generate-nod', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'generateNod'])->name('discoNoticeHistories.generate-nod');
Route::get('/disco_notice_histories/get-disco-list-preview', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'getDiscoListPreview'])->name('discoNoticeHistories.get-disco-list-preview');
Route::get('/disco_notice_histories/print-reroute', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'printReroute'])->name('discoNoticeHistories.print-reroute');
Route::get('/disco_notice_histories/get-disco-list-preview-route', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'getDiscoListPreviewRoute'])->name('discoNoticeHistories.get-disco-list-preview-route');
Route::get('/disco_notice_histories/print-disconnection-list/{period}/{area}/{meterReader}/{day}', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'printDisconnectionList'])->name('discoNoticeHistories.print-disconnection-list');
Route::get('/disco_notice_histories/print-disconnection-list-route/{period}/{area}/{route}', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'printDisconnectionListRoute'])->name('discoNoticeHistories.print-disconnection-list-route');
Route::resource('discoNoticeHistories', App\Http\Controllers\DiscoNoticeHistoryController::class);


Route::resource('accountPayables', App\Http\Controllers\AccountPayablesController::class);


Route::get('/cache_other_payments/fetch-cached', [App\Http\Controllers\CacheOtherPaymentsController::class, 'fetchCached'])->name('cacheOtherPayments.fetch-cached');
Route::get('/cache_other_payments/save-other-payments', [App\Http\Controllers\CacheOtherPaymentsController::class, 'saveOtherPayments'])->name('cacheOtherPayments.save-other-payments');
Route::resource('cacheOtherPayments', App\Http\Controllers\CacheOtherPaymentsController::class);


Route::get('/pending_bill_adjustments/open-reading-adjustments/{servicePeriod}', [App\Http\Controllers\PendingBillAdjustmentsController::class, 'openReadingAdjustments'])->name('pendingBillAdjustments.open-reading-adjustments');
Route::get('/pending_bill_adjustments/confirm-all-adjustments/{servicePeriod}', [App\Http\Controllers\PendingBillAdjustmentsController::class, 'confirmAllAdjustments'])->name('pendingBillAdjustments.confirm-all-adjustments');
Route::get('/pending_bill_adjustments/confirm-adjustment/{pendingAdjustmentId}', [App\Http\Controllers\PendingBillAdjustmentsController::class, 'confirmAdjustment'])->name('pendingBillAdjustments.confirm-adjustment');
Route::resource('pendingBillAdjustments', App\Http\Controllers\PendingBillAdjustmentsController::class);


Route::get('/o_r_assignings/get-last-or', [App\Http\Controllers\ORAssigningController::class, 'getLastOR'])->name('oRAssignings.get-last-or');
Route::get('/o_r_assignings/get-next-or', [App\Http\Controllers\ORAssigningController::class, 'getNextOR'])->name('oRAssignings.get-next-or');
Route::resource('oRAssignings', App\Http\Controllers\ORAssigningController::class);


Route::post('/kwh_sales/generate-new/', [App\Http\Controllers\KwhSalesController::class, 'generateNew'])->name('kwhSales.generate-new');
Route::post('/kwh_sales/save-sales-report', [App\Http\Controllers\KwhSalesController::class, 'saveSalesReport'])->name('kwhSales.save-sales-report');
Route::get('/kwh_sales/view-sales/{id}', [App\Http\Controllers\KwhSalesController::class, 'viewSales'])->name('kwhSales.view-sales');
Route::get('/kwh_sales/print-report/{id}', [App\Http\Controllers\KwhSalesController::class, 'printReport'])->name('kwhSales.print-report');
Route::get('/kwh_sales/sales-distribution', [App\Http\Controllers\KwhSalesController::class, 'salesDistribution'])->name('kwhSales.sales-distribution');
Route::get('/kwh_sales/sales-distribution-view/{period}', [App\Http\Controllers\KwhSalesController::class, 'salesDistributionView'])->name('kwhSales.sales-distribution-view');
Route::get('/kwh_sales/consolidated-per-town/{period}', [App\Http\Controllers\KwhSalesController::class, 'consolidatedPerTown'])->name('kwhSales.consolidated-per-town');
Route::resource('kwhSales', App\Http\Controllers\KwhSalesController::class);


Route::get('/pre_payment_balances/search', [App\Http\Controllers\PrePaymentBalanceController::class, 'search'])->name('prePaymentBalances.search');
Route::get('/pre_payment_balances/get-balance-details', [App\Http\Controllers\PrePaymentBalanceController::class, 'getBalanceDetails'])->name('prePaymentBalances.get-balance-details');
Route::resource('prePaymentBalances', App\Http\Controllers\PrePaymentBalanceController::class);


Route::resource('prePaymentTransHistories', App\Http\Controllers\PrePaymentTransHistoryController::class);


Route::get('/notifiers/get-notifications', [App\Http\Controllers\NotifiersController::class, 'getNotifications'])->name('notifiers.get-notifications');
Route::resource('notifiers', App\Http\Controllers\NotifiersController::class);


Route::get('/o_r_cancellations/approve-bills-or-cancellation/{orCancellationId}', [App\Http\Controllers\ORCancellationsController::class, 'approveBillsORCancellation'])->name('oRCancellations.approve-bills-or-cancellation');
Route::get('/o_r_cancellations/other-payments', [App\Http\Controllers\ORCancellationsController::class, 'otherPaymentsORCancellation'])->name('oRCancellations.other-payments');
Route::get('/o_r_cancellations/fetch-transaction-indices', [App\Http\Controllers\ORCancellationsController::class, 'fetchTransactionIndices'])->name('oRCancellations.fetch-transaction-indices');
Route::get('/o_r_cancellations/fetch-transaction-details', [App\Http\Controllers\ORCancellationsController::class, 'fetchTransactionDetails'])->name('oRCancellations.fetch-transaction-details');
Route::get('/o_r_cancellations/fetch-transaction-particulars', [App\Http\Controllers\ORCancellationsController::class, 'fetchParticulars'])->name('oRCancellations.fetch-transaction-particulars');
Route::get('/o_r_cancellations/attempt-cancel-transaction-or', [App\Http\Controllers\ORCancellationsController::class, 'attemptCancelTransactionOR'])->name('oRCancellations.attempt-cancel-transaction-or');
Route::get('/o_r_cancellations/show-other-payments/{id}', [App\Http\Controllers\ORCancellationsController::class, 'showOtherPayments'])->name('oRCancellations.show-other-payments');
Route::get('/o_r_cancellations/approve-transaction-cancellation/{id}', [App\Http\Controllers\ORCancellationsController::class, 'approveTransactionCancellation'])->name('oRCancellations.approve-transaction-cancellation');
Route::resource('oRCancellations', App\Http\Controllers\ORCancellationsController::class);


Route::get('/b_a_p_a_reading_schedules/show-schedules/{period}', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'showSchedules'])->name('bAPAReadingSchedules.show-schedules');
Route::get('/b_a_p_a_reading_schedules/add-schedule', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'addSchedule'])->name('bAPAReadingSchedules.add-schedule');
Route::get('/b_a_p_a_reading_schedules/get-bapas', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'getBapas'])->name('bAPAReadingSchedules.get-bapas');
Route::get('/b_a_p_a_reading_schedules/remove-bapa-from-sched', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'removeBapaFromSched'])->name('bAPAReadingSchedules.remove-bapa-from-sched');
Route::get('/b_a_p_a_reading_schedules/remove-downloaded-status-from-bapa', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'removeDownloadedStatusFromBapa'])->name('bAPAReadingSchedules.remove-downloaded-status-from-bapa');
Route::resource('bAPAReadingSchedules', App\Http\Controllers\BAPAReadingSchedulesController::class);


Route::resource('bAPAPayments', App\Http\Controllers\BAPAPaymentsController::class);


Route::resource('distributionSystemLosses', App\Http\Controllers\DistributionSystemLossController::class);


Route::resource('rateItems', App\Http\Controllers\RateItemsController::class);


Route::resource('changeMeterLogs', App\Http\Controllers\ChangeMeterLogsController::class);


Route::resource('accountGLCodes', App\Http\Controllers\AccountGLCodesController::class);


Route::resource('dCRSummaryTransactions', App\Http\Controllers\DCRSummaryTransactionsController::class);
Route::get('/d_c_r_summary_transactions/sales-dcr-monitor', [App\Http\Controllers\DCRSummaryTransactionsController::class, 'salesDcrMonitor'])->name('dCRSummaryTransactions.sales-dcr-monitor');
Route::get('/d_c_r_summary_transactions/print-dcr/{teller}/{day}', [App\Http\Controllers\DCRSummaryTransactionsController::class, 'printDcr'])->name('dCRSummaryTransactions.print-dcr');
Route::get('/d_c_r_summary_transactions/dashboard', [App\Http\Controllers\DCRSummaryTransactionsController::class, 'collectionDashboard'])->name('dCRSummaryTransactions.dashboard');
Route::get('/d_c_r_summary_transactions/get-collection-per-area', [App\Http\Controllers\DCRSummaryTransactionsController::class, 'dashboardGetCollectionPerArea'])->name('dCRSummaryTransactions.get-collection-per-area');
Route::get('/d_c_r_summary_transactions/collection-office-expand/{office}', [App\Http\Controllers\DCRSummaryTransactionsController::class, 'collectionOfficeEpand'])->name('dCRSummaryTransactions.collection-office-expand');


Route::resource('banks', App\Http\Controllers\BanksController::class);


Route::get('/b_a_p_a_adjustments/adjust-bapa/{bapaName}', [App\Http\Controllers\BAPAAdjustmentsController::class, 'adjustBapaPayments'])->name('bAPAAdjustments.adjust-bapa');
Route::get('/b_a_p_a_adjustments/search-bapa', [App\Http\Controllers\BAPAAdjustmentsController::class, 'searchBapa'])->name('bAPAAdjustments.search-bapa');
Route::get('/b_a_p_a_adjustments/save-bapa-adjustments', [App\Http\Controllers\BAPAAdjustmentsController::class, 'saveBapaAdjustments'])->name('bAPAAdjustments.save-bapa-adjustments');
Route::get('/b_a_p_a_adjustments/search-bapa-monitor', [App\Http\Controllers\BAPAAdjustmentsController::class, 'searchBapaMonitor'])->name('bAPAAdjustments.search-bapa-monitor');
Route::get('/b_a_p_a_adjustments/get-bapa-monitor-search-results', [App\Http\Controllers\BAPAAdjustmentsController::class, 'getBapaMonitorSearchResults'])->name('bAPAAdjustments.get-bapa-monitor-search-results');
Route::get('/b_a_p_a_adjustments/bapa-collection-monitor-console/{bapaName}', [App\Http\Controllers\BAPAAdjustmentsController::class, 'bapaCollectionMonitorConsole'])->name('bAPAAdjustments.bapa-collection-monitor-console');
Route::get('/b_a_p_a_adjustments/print-voucher/{representative}/{bapaName}/{period}/{discount}/{dateAdjusted}', [App\Http\Controllers\BAPAAdjustmentsController::class, 'printVoucher'])->name('bAPAAdjustments.print-voucher');
Route::resource('bAPAAdjustments', App\Http\Controllers\BAPAAdjustmentsController::class);


Route::resource('bAPAAdjustmentDetails', App\Http\Controllers\BAPAAdjustmentDetailsController::class);


Route::resource('paidBillsDetails', App\Http\Controllers\PaidBillsDetailsController::class);


Route::resource('transacionPaymentDetails', App\Http\Controllers\TransacionPaymentDetailsController::class);


Route::resource('billsOriginals', App\Http\Controllers\BillsOriginalController::class);


Route::resource('accountNameHistories', App\Http\Controllers\AccountNameHistoryController::class);


Route::resource('mastPoles', App\Http\Controllers\MastPolesController::class);


Route::resource('dCRIndices', App\Http\Controllers\DCRIndexController::class);


Route::resource('accountLocationHistories', App\Http\Controllers\AccountLocationHistoryController::class);


Route::resource('denominations', App\Http\Controllers\DenominationsController::class);


Route::get('/excemptions/new-excemptions', [App\Http\Controllers\ExcemptionsController::class, 'newExcemption'])->name('excemptions.new-excemptions');
Route::get('/excemptions/search-account-excemption', [App\Http\Controllers\ExcemptionsController::class, 'searchAccountExcemption'])->name('excemptions.search-account-excemption');
Route::get('/excemptions/add-excemption', [App\Http\Controllers\ExcemptionsController::class, 'addExcemption'])->name('excemptions.add-excemption');
Route::get('/excemptions/get-excemptions-ajax', [App\Http\Controllers\ExcemptionsController::class, 'getExcemptionsAjax'])->name('excemptions.get-excemptions-ajax');
Route::get('/excemptions/remove-excemption', [App\Http\Controllers\ExcemptionsController::class, 'removeExcemption'])->name('excemptions.remove-excemption');
Route::get('/excemptions/print-excemptions/{period}', [App\Http\Controllers\ExcemptionsController::class, 'printExcemptions'])->name('excemptions.print-excemptions');
Route::resource('excemptions', App\Http\Controllers\ExcemptionsController::class);

Route::get('/electricians/get-electricians-ajax', [App\Http\Controllers\ElectriciansController::class, 'getElectricianAjax'])->name('electricians.get-electricians-ajax');
Route::get('/electricians/housewiring-labor', [App\Http\Controllers\ElectriciansController::class, 'housewiringLabor'])->name('electricians.housewiring-labor');
Route::get('/electricians/download-housewiring-labor/{month}/{term}/{year}/{office}', [App\Http\Controllers\ElectriciansController::class, 'downloadHousewiringLabor'])->name('electricians.download-housewiring-labor');
Route::get('/electricians/labor-summary', [App\Http\Controllers\ElectriciansController::class, 'laborSummary'])->name('electricians.labor-summary');
Route::get('/electricians/download-labor-share/{month}/{term}/{year}/{office}', [App\Http\Controllers\ElectriciansController::class, 'downloadLaborShare'])->name('electricians.download-labor-share');
Route::get('/electricians/print-labor-share/{month}/{term}/{year}/{office}', [App\Http\Controllers\ElectriciansController::class, 'printLaborShare'])->name('electricians.print-labor-share');
Route::resource('electricians', App\Http\Controllers\ElectriciansController::class);


Route::resource('unbundledRates', App\Http\Controllers\UnbundledRatesController::class);


Route::resource('unbundledRatesExtensions', App\Http\Controllers\UnbundledRatesExtensionController::class);


Route::resource('billDeposits', App\Http\Controllers\BillDepositsController::class);

Route::get('/account_masters/account-migration-step-one/{id}', [App\Http\Controllers\AccountMasterController::class, 'accountMigrationStepOne'])->name('accountMasters.account-migration-step-one');
Route::get('/account_masters/get-available-account-numbers', [App\Http\Controllers\AccountMasterController::class, 'getAvailableAccountNumbers'])->name('accountMasters.get-available-account-numbers');
Route::get('/account_masters/account-migration-step-two/{id}/{scId}', [App\Http\Controllers\AccountMasterController::class, 'accountMigrationStepTwo'])->name('accountMasters.account-migration-step-two');
Route::get('/account_masters/account-migration-step-three/{id}/{scId}', [App\Http\Controllers\AccountMasterController::class, 'accountMigrationStepThree'])->name('accountMasters.account-migration-step-three');
Route::get('/account_masters/get-neighboring-by-barangay', [App\Http\Controllers\AccountMasterController::class, 'getNeighboringByBarangay'])->name('accountMasters.get-neighboring-by-barangay');
Route::get('/account_masters/get-neighboring-by-account', [App\Http\Controllers\AccountMasterController::class, 'getNeighboringByAccount'])->name('accountMasters.get-neighboring-by-account');
Route::get('/account_masters/get-available-sequence-numbers', [App\Http\Controllers\AccountMasterController::class, 'getAvailableSequenceNumbers'])->name('accountMasters.get-available-sequence-numbers');
Route::get('/account_masters/reports-new-accounts', [App\Http\Controllers\AccountMasterController::class, 'reportsNewAccounts'])->name('accountMasters.reports-new-accounts');
Route::get('/account_masters/print-new-accounts/{from}/{to}', [App\Http\Controllers\AccountMasterController::class, 'printNewAccounts'])->name('accountMasters.print-new-accounts');
Route::get('/account_masters/new-bapa-energized', [App\Http\Controllers\AccountMasterController::class, 'newEnergizedBapa'])->name('accountMasters.new-bapa-energized');
Route::get('/account_masters/abrupt-increase-decrease', [App\Http\Controllers\AccountMasterController::class, 'abruptIncreaseDecrease'])->name('accountMasters.abrupt-increase-decrease');
Route::get('/account_masters/get-abrupt-increase-decrease', [App\Http\Controllers\AccountMasterController::class, 'getAbruptIncreaseDecrease'])->name('accountMasters.get-abrupt-increase-decrease');
Route::get('/account_masters/check-left-available-account-numbers', [App\Http\Controllers\AccountMasterController::class, 'getLeftAvailableAccountNumbers'])->name('accountMasters.check-left-available-account-numbers');
Route::get('/account_masters/print-sdir/{office}', [App\Http\Controllers\AccountMasterController::class, 'printSDIR'])->name('accountMasters.print-sdir');
Route::resource('accountMasters', App\Http\Controllers\AccountMasterController::class);


Route::resource('barangayProxies', App\Http\Controllers\BarangayProxiesController::class);


Route::resource('preRegEntries', App\Http\Controllers\PreRegEntriesController::class);


Route::resource('signatories', App\Http\Controllers\SignatoriesController::class);


Route::resource('accountMasterExtensions', App\Http\Controllers\AccountMasterExtensionController::class);


Route::resource('meters', App\Http\Controllers\MetersController::class);


Route::get('/bills/show-bill/{acctno}/{period}', [App\Http\Controllers\BillsController::class, 'showBill'])->name('bills.show-bill');
Route::resource('bills', App\Http\Controllers\BillsController::class);


Route::resource('billsExtensions', App\Http\Controllers\BillsExtensionController::class);


Route::resource('paidBills', App\Http\Controllers\PaidBillsController::class);


Route::resource('sMSNotifications', App\Http\Controllers\SMSNotificationsController::class);


Route::resource('cRMDetails', App\Http\Controllers\CRMDetailsController::class);


Route::resource('cRMQueues', App\Http\Controllers\CRMQueueController::class);


Route::get("/disconnection_schedules/set-schedule", [DisconnectionSchedulesController::class, 'setSchedule'])->name("disconnectionSchedules.set-schedule");
Route::get("/disconnection_schedules/get-routes", [DisconnectionSchedulesController::class, 'getRoutes'])->name("disconnectionSchedules.get-routes");
Route::get("/disconnection_schedules/get-stats", [DisconnectionSchedulesController::class, 'getStats'])->name("disconnectionSchedules.get-stats");
Route::get("/disconnection_schedules/view-disconnection-consumers/{id}/{day}/{period}", [DisconnectionSchedulesController::class, 'viewDisconnectionConsumers'])->name("disconnectionSchedules.view-disconnection-consumers");
Route::get("/disconnection_schedules/get-schedules-data", [DisconnectionSchedulesController::class, 'getSchedulesData'])->name("disconnectionSchedules.get-schedules-data");
Route::get("/disconnection_schedules/get-accounts-from-schedule", [DisconnectionSchedulesController::class, 'getAccountsFromSchedule'])->name("disconnectionSchedules.get-accounts-from-schedule");
Route::get("/disconnection_schedules/monitor", [DisconnectionSchedulesController::class, 'monitor'])->name("disconnectionSchedules.monitor");
Route::get("/disconnection_schedules/monitor-view/{id}", [DisconnectionSchedulesController::class, 'monitorView'])->name("disconnectionSchedules.monitor-view");
Route::get("/disconnection_schedules/disconnection-map-data", [DisconnectionSchedulesController::class, 'disconnectionMapData'])->name("disconnectionSchedules.disconnection-map-data");
Route::get("/disconnection_schedules/get-schedules-collection-calendar-data", [DisconnectionSchedulesController::class, 'getSchedulesCollectionCalendarData'])->name("disconnectionSchedules.get-schedules-collection-calendar-data");
Route::get("/disconnection_schedules/add-payment-notes", [DisconnectionSchedulesController::class, 'addPaymentNotes'])->name("disconnectionSchedules.add-payment-notes");
Route::resource('disconnectionSchedules', DisconnectionSchedulesController::class);


Route::get("/disconnection_datas/disco-teller-module", [DisconnectionDataController::class, 'discoTellerModule'])->name("disconnectionDatas.disco-teller-module");
Route::get("/disconnection_datas/disco-teller-module-view/{name}/{date}", [DisconnectionDataController::class, 'discoTellerModuleView'])->name("disconnectionDatas.disco-teller-module-view");
Route::get("/disconnection_datas/post-payments", [DisconnectionDataController::class, 'postPayments'])->name("disconnectionDatas.post-payments");
Route::get("/disconnection_datas/print-double-payments/{name}/{date}", [DisconnectionDataController::class, 'printDoublePayments'])->name("disconnectionDatas.print-double-payments");
Route::get("/disconnection_datas/get-monthly-collection-graph", [DisconnectionDataController::class, 'getMonthlyCollectionGraph'])->name("disconnectionDatas.get-monthly-collection-graph");
Route::resource('disconnectionDatas', DisconnectionDataController::class);


Route::resource('disconnectionRoutes', App\Http\Controllers\DisconnectionRoutesController::class);


Route::resource('changeMeters', App\Http\Controllers\ChangeMeterController::class);

Route::get("/administrative/server-monitor", [AdministrativeController::class, 'serverMonitor'])->name("administrative.server-monitor");


Route::resource('servers', App\Http\Controllers\ServersController::class);


Route::resource('serverStats', App\Http\Controllers\ServerStatsController::class);


Route::resource('serverLogs', App\Http\Controllers\ServerLogsController::class);


Route::resource('tempReadings', App\Http\Controllers\TempReadingsController::class);


Route::resource('sites', App\Http\Controllers\SitesController::class)->middleware('cors');


Route::resource('additionalConsumptions', App\Http\Controllers\AdditionalConsumptionsController::class);
