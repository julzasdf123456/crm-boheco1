@php
    
use Illuminate\Support\Facades\Auth;

@endphp

<!-- MEMBERSHIP MENU -->
@canany(['Super Admin', 'view membership'])
    <li class="nav-header">CRM</li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-users nav-icon text-success"></i>
            <p>
                Membership
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @canany(['Super Admin', 'view membership'])
                <li class="nav-item">
                    <a href="{{ route('memberConsumers.index') }}"
                    class="nav-link {{ Request::is('memberConsumers.index*') ? 'active' : '' }}">
                    <i class="fas fa-street-view nav-icon text-success"></i><p>Member Consumers</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'create membership'])
                <li class="nav-item">
                    <a href="{{ route('memberConsumers.create') }}"
                    class="nav-link {{ Request::is('memberConsumers.create*') ? 'active' : '' }}">
                    <i class="fas fa-user-plus nav-icon text-success"></i><p>Register New MCO</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'update membership'])
                <li class="nav-header">                
                    Reports
                </li>

                <li class="nav-item">
                    <a href="{{ route('memberConsumers.monthly-reports') }}"
                    class="nav-link {{ Request::is('memberConsumers.monthly-reports*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-success"></i><p>Monthly</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('memberConsumers.quarterly-reports') }}"
                    class="nav-link {{ Request::is('memberConsumers.quarterly-reports*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-success"></i><p>Quarterly</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'update membership'])
                <li class="nav-header">                
                    Settings
                </li>

                <li class="nav-item">
                    <a href="{{ route('memberConsumerTypes.index') }}"
                    class="nav-link {{ Request::is('memberConsumerTypes*') ? 'active' : '' }}">
                    <i class="fas fa-code-branch nav-icon text-success"></i><p>Consumer Types</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('memberConsumerChecklistsReps.index') }}"
                    class="nav-link {{ Request::is('memberConsumerChecklistsReps*') ? 'active' : '' }}">
                    <i class="fas fa-check nav-icon text-success"></i><p>Checklists</p>
                    </a>
                </li>
            @endcanany
        </ul>
    </li>    
@endcanany


<!-- SERVICE CONNECTION MENU -->
@canany(['Super Admin', 'sc view'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-plug nav-icon text-warning"></i>
            <p>
                Service Connections
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @canany(['Super Admin', 'sc view'])
                <li class="nav-item">
                    <a href="{{ route('serviceConnections.dashboard') }}"
                    class="nav-link {{ Request::is('serviceConnections.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon text-warning"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'sc view'])
                <li class="nav-item">
                    <a href="{{ route('serviceConnections.index') }}"
                    class="nav-link {{ Request::is('serviceConnections*') ? 'active' : '' }}">
                        <i class="fas fa-bolt nav-icon text-warning"></i>
                        <p>All Applications</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'sc create'])
                <li class="nav-item">
                    <a href="{{ route('serviceConnections.selectmembership') }}"
                    class="nav-link {{ Request::is('serviceConnections.selectmembership') ? 'active' : '' }}">
                        <i class="fas fa-plus nav-icon text-warning"></i>
                        <p>New Application</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('serviceConnections.relocation-search') }}"
                    class="nav-link {{ Request::is('serviceConnections.relocation-search') ? 'active' : '' }}">
                        <i class="fas fa-plus nav-icon text-warning"></i>
                        <p>New Relocation</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('serviceConnections.change-name-search') }}"
                    class="nav-link {{ Request::is('serviceConnections.change-name-search') ? 'active' : '' }}">
                        <i class="fas fa-plus nav-icon text-warning"></i>
                        <p>Change Name</p>
                    </a>
                </li> --}}
            @endcanany

            @if (Auth::user()->hasRole('Metering Personnel'))
            <li class="nav-item">
                <a href="{{ route('serviceConnectionMtrTrnsfrmrs.assigning') }}"
                class="nav-link {{ Request::is('serviceConnectionMtrTrnsfrmrs.assigning') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon text-warning"></i>
                    <p>Assign Meters
                        <span id="assign-badge-count" class="right badge badge-danger">0</span>
                    </p>
                </a>
            </li>
            @endif

            @canany(['Super Admin', 'sc view'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Monitoring
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.daily-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.daily-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check nav-icon text-warning"></i><p>Daily Monitor</p>
                        </a>
                    </li>

                </ul>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc create'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Reports
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.applications-report') }}"
                        class="nav-link {{ Request::is('serviceConnections.applications-report*') ? 'active' : '' }}">
                        <i class="fas fa-file-import nav-icon text-warning"></i><p>Applications</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.energization-report') }}"
                        class="nav-link {{ Request::is('serviceConnections.energization-report*') ? 'active' : '' }}">
                        <i class="fas fa-charging-station nav-icon text-warning"></i><p>Energization</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.daily-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.daily-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon text-warning"></i><p>Meter Installation</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.daily-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.daily-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list nav-icon text-warning"></i><p>Summary</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc settings'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    {{-- <i class="fas fa-cogs"></i> --}}
                    <p>
                        Settings
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionAccountTypes.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionAccountTypes*') ? 'active' : '' }}">
                        <i class="fas fa-code-branch nav-icon text-warning"></i><p>Account Types</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionMatPayables.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionMatPayables*') ? 'active' : '' }}">
                        <i class="fas fa-hammer nav-icon text-warning"></i><p>Material Payables</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionPayParticulars.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionPayParticulars*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart nav-icon text-warning"></i><p>Payment Particulars</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionChecklistsReps.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionChecklistsReps*') ? 'active' : '' }}">
                        <i class="fas fa-check nav-icon text-warning"></i><p>Checklists</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            {{-- MATERIALS AND STRUCTURES --}}
            @canany(['Super Admin', 'sc create'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    {{-- <i class="fas fa-toolbox nav-icon"></i> --}}
                    <p>
                        Mat. & Structures
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('structures.index') }}"
                        class="nav-link {{ Request::is('structures*') ? 'active' : '' }}">
                        <i class="fas fa-draw-polygon nav-icon text-warning"></i><p>Structures</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('materialAssets.index') }}"
                        class="nav-link {{ Request::is('materialAssets*') ? 'active' : '' }}">
                        <i class="fas fa-plug nav-icon text-warning"></i><p>Material Assets</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('transformerIndices.index') }}"
                        class="nav-link {{ Request::is('transformerIndices*') ? 'active' : '' }}">
                        <i class="fas fa-car-battery nav-icon text-warning"></i><p>Transformer Index</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('poleIndices.index') }}"
                        class="nav-link {{ Request::is('poleIndices*') ? 'active' : '' }}">
                        <i class="fas fa-cross nav-icon text-warning"></i><p>Pole Index</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('spanningIndices.index') }}"
                        class="nav-link {{ Request::is('spanningIndices*') ? 'active' : '' }}">
                        <i class="fas fa-network-wired nav-icon text-warning"></i>
                        <p>Spanning Index</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('specialEquipmentMaterials.index') }}"
                        class="nav-link {{ Request::is('specialEquipmentMaterials*') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt nav-icon text-warning"></i><p>Special Eq. Materials</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('preDefinedMaterials.index') }}"
                        class="nav-link {{ Request::is('preDefinedMaterials*') ? 'active' : '' }}">
                            <i class="fas fa-plug nav-icon text-warning"></i><p>Pre-Defined Materials</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            {{-- @canany(['Super Admin', 'sc view'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Others
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.fleet-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.fleet-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-street-view nav-icon text-warning"></i><p>Fleet Monitoring</p>
                        </a>
                    </li>

                </ul>
            </li>
            @endcanany --}}
            
            @canany(['Super Admin', 'sc delete'])
            <li class="nav-item">
                <a href="{{ route('serviceConnections.trash') }}"
                class="nav-link {{ Request::is('serviceConnections.trash*') ? 'active' : '' }}">
                <i class="fas fa-trash nav-icon text-warning"></i><p>Trash</p>
                </a>
            </li>
            @endcanany
        </ul>
    </li>
@endcanany

{{-- TICKETS --}}
@canany(['Super Admin', 'tickets view'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-ambulance nav-icon text-danger"></i>
            <p>
                Tickets
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('tickets.dashboard') }}"
                class="nav-link {{ Request::is('tickets.dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon text-danger"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.index') }}"
                class="nav-link {{ Request::is('tickets.index*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list nav-icon text-danger"></i><p>All Tickets</p>
                </a>
            </li>
            @canany(['Super Admin', 'tickets create'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="fas fa-plus-circle nav-icon text-danger"></i>
                    <p>
                        Create Ticket
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('tickets.create-select') }}"
                        class="nav-link {{ Request::is('tickets.create-select*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Ordinary Ticket</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.relocation-search') }}"
                        class="nav-link {{ Request::is('tickets.relocation-search*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Relocation</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.change-meter') }}"
                        class="nav-link {{ Request::is('tickets.change-meter*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Change Meter</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany
            @canany(['Super Admin', 'tickets edit'])
            <li class="nav-header">                
                Assessments 
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.crew-field-monitor') }}"
                class="nav-link {{ Request::is('tickets.crew-field-monitor*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Crew Field Monitor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.assessments-change-meter') }}"
                class="nav-link {{ Request::is('tickets.assessments-change-meter*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Change Meter Requests</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.assessments-ordinary-ticket') }}"
                class="nav-link {{ Request::is('tickets.assessments-ordinary-ticket*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Crew Assigning</p>
                </a>
            </li>
            @endcanany
            @canany(['Super Admin', 'tickets create', 'tickets edit'])
            <li class="nav-header">                
                Reports
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.ticket-summary-report') }}"
                class="nav-link {{ Request::is('tickets.ticket-summary-report*') ? 'active' : '' }}">
                    <i class="fas fa-file nav-icon text-danger"></i><p>Ticket Summary</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.ticket-tally') }}"
                class="nav-link {{ Request::is('tickets.ticket-tally*') ? 'active' : '' }}">
                    <i class="fas fa-list nav-icon text-danger"></i><p>Ticket Tally</p>
                </a>
            </li>
            <li class="nav-header">                
                Settings and Others 
            </li>
            <li class="nav-item">
                <a href="{{ route('ticketsRepositories.index') }}"
                   class="nav-link {{ Request::is('ticketsRepositories*') ? 'active' : '' }}">
                   <i class="fas fa-check-circle nav-icon text-danger"></i><p>Ticket Types</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.trash') }}"
                   class="nav-link {{ Request::is('tickets.trash*') ? 'active' : '' }}">
                   <i class="fas fa-trash nav-icon text-danger"></i><p>Trash</p>
                </a>
            </li>
            @endcanany
        </ul>
    </li>
    
@endcanany

{{-- DAMAGE ASSESSMENT --}}
{{-- @canany(['Super Admin', 'view damage monitor'])
    <li class="nav-item">
        <a href="{{ route('damageAssessments.index') }}"
        class="nav-link {{ Request::is('damageAssessments.index*') ? 'active' : '' }}">
            <i class="fas fa-house-damage nav-icon text-default"></i><p>Damage Assessment</p>
        </a>
    </li>    
@endcanany --}}

{{-- SERVICE ACCOUNTS --}}
@canany(['Super Admin', 'billing re-bill'])
    <li class="nav-header">BILLING</li>
    <li class="nav-item">
        <a href="{{ route('bills.dashboard') }}"
           class="nav-link {{ Request::is('bills.dashboard*') ? 'active' : '' }}">                   
           <i class="fas fa-chart-line nav-icon text-primary"></i><p>Dashboard
           </p>
        </a>
    </li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file-invoice-dollar nav-icon text-primary"></i>
            <p>
                Billing Inquiry
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.index') }}"
                   class="nav-link {{ Request::is('serviceAccounts*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-circle nav-icon text-primary"></i><p>All Accounts</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.termed-payment-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.termed-payment-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-list nav-icon text-primary"></i><p>Accounts w/ OCL</p>
                </a>
            </li>
            <li class="nav-header">                
                Others 
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.pending-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.pending-accounts*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-alt-slash nav-icon text-primary"></i><p>Pending Accounts</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.manual-account-migration-one') }}"
                   class="nav-link {{ Request::is('serviceAccounts.manual-account-migration-one*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-plus nav-icon text-primary"></i><p>Add New Account
                   </p>                   
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.change-meter-manual') }}"
                   class="nav-link {{ Request::is('serviceAccounts.change-meter-manual*') ? 'active' : '' }}">                   
                   <i class="fas fa-random nav-icon text-primary"></i><p>Change Meter
                   </p>                   
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.relocation-manual') }}"
                   class="nav-link {{ Request::is('serviceAccounts.relocation-manual*') ? 'active' : '' }}">                   
                   <i class="fas fa-map-marked-alt nav-icon text-primary"></i><p>Relocation
                   </p>                   
                </a>
            </li>
        </ul>
    </li>

    {{-- <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-layer-group nav-icon text-primary"></i>
            <p>
                BAPA
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.bapa') }}"
                   class="nav-link {{ Request::is('serviceAccounts.bapa*') ? 'active' : '' }}">                   
                   <i class="fas fa-list nav-icon text-primary"></i><p>All BAPA</p>
                </a>    
            </li>
            <li class="nav-item">
                <a href="{{ route('bAPAReadingSchedules.index') }}"
                   class="nav-link {{ Request::is('bAPAReadingSchedules.index*') ? 'active' : '' }}">                   
                   <i class="fas fa-calendar nav-icon text-primary"></i><p>BAPA Reading Sched</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.bapa-manual-billing') }}"
                   class="nav-link {{ Request::is('bills.bapa-manual-billing*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Manual Billing</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.print-bapa-reading-list') }}"
                   class="nav-link {{ Request::is('readings.print-bapa-reading-list*') ? 'active' : '' }}">                   
                   <i class="fas fa-print nav-icon text-primary"></i><p>Print Reading List
                   </p>
                </a>
            </li>
        </ul>
    </li> --}}
@endcanany

{{-- BILLS --}}
{{-- @canany(['Super Admin', 'billing re-bill'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-wallet nav-icon text-primary"></i>
            <p>
                Bills
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('bills.all-bills') }}"
                   class="nav-link {{ Request::is('bills.all-bills*') ? 'active' : '' }}">
                    <i class="fas fa-list nav-icon text-primary"></i><p>All Bills</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('rates.index') }}"
                   class="nav-link {{ Request::is('rates*') ? 'active' : '' }}">
                    <i class="fas fa-percentage nav-icon text-primary"></i><p>Rate Management</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.unbilled-readings') }}"
                   class="nav-link {{ Request::is('bills.unbilled-readings*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle nav-icon text-primary"></i><p>Unbilled Readings</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.grouped-billing') }}"
                   class="nav-link {{ Request::is('bills.grouped-billing*') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon text-primary"></i><p>Grouped Billing</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.bulk-print-bill') }}"
                   class="nav-link {{ Request::is('bills.bulk-print-bill*') ? 'active' : '' }}">
                    <i class="fas fa-print nav-icon text-primary"></i><p>Bulk Printing</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('excemptions.index') }}"
                   class="nav-link {{ Request::is('excemptions.index*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Excemptions
                        <span class="right badge badge-danger">New</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.kwh-monitoring') }}"
                   class="nav-link {{ Request::is('bills.kwh-monitoring*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Kwh Monitoring
                        <span class="right badge badge-danger">New</span>
                    </p>
                </a>
            </li>

            <li class="nav-header">                
                Others
            </li>
            <li class="nav-item">
                <a href="{{ route('pendingBillAdjustments.index') }}"
                   class="nav-link {{ Request::is('pendingBillAdjustments*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Zero Reading Adj.</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany --}}
@canany(['Super Admin', 'billing re-bill'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-tachometer-alt nav-icon text-primary"></i>
            <p>
                Meter Reading
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('readingSchedules.reading-schedule-index') }}"
                   class="nav-link {{ Request::is('readingSchedules.reading-schedule-index*') ? 'active' : '' }}">                   
                   <i class="fas fa-calendar-week nav-icon text-primary"></i><p>M. Reader Scheduler</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.reading-account-grouper') }}"
                   class="nav-link {{ Request::is('serviceAccounts.reading-account-grouper*') ? 'active' : '' }}">                   
                   <i class="fas fa-calendar-alt nav-icon text-primary"></i><p>Reading Schedules</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.captured-readings') }}"
                   class="nav-link {{ Request::is('readings.captured-readings*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Captured Readings</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.manual-reading') }}"
                   class="nav-link {{ Request::is('readings.manual-reading*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Manual Reading</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.reading-monitor') }}"
                   class="nav-link {{ Request::is('readings.reading-monitor*') ? 'active' : '' }}">                   
                   <i class="fas fa-street-view nav-icon text-primary"></i><p>Reading Monitoring</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('meterReaderTrackNames.index') }}"
                   class="nav-link {{ Request::is('meterReaderTrackNames.index*') ? 'active' : '' }}">                   
                   <i class="fas fa-map-marked-alt nav-icon text-primary"></i><p>M. Reader Tracks</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-check nav-icon text-primary"></i>
            <p>
                Approvals
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('bills.bill-arrears-unlocking') }}"
                   class="nav-link {{ Request::is('bills.bill-arrears-unlocking*') ? 'active' : '' }}">                   
                   <i class="fas fa-unlock nav-icon text-primary"></i><p>Bill Arrears Unlocking</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.bills-cancellation-approval') }}"
                   class="nav-link {{ Request::is('bills.bills-cancellation-approval*') ? 'active' : '' }}">                   
                   <i class="fas fa-check-circle nav-icon text-primary"></i><p>Bills Cancellation</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file nav-icon text-primary"></i>
            <p>
                Reports
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-header">                
                Bills
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.adjustment-reports') }}"
                   class="nav-link {{ Request::is('bills.adjustment-reports*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Adjustments</p>
                </a>
            </li>
            <li class="nav-header">                
                Meter Reading 
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.billed-and-unbilled-reports') }}"
                   class="nav-link {{ Request::is('readings.billed-and-unbilled-reports*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Billed & Unbilled</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.billed-and-unbilled-reports-bapa') }}"
                   class="nav-link {{ Request::is('readings.billed-and-unbilled-reports-bapa*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Billed & Unbilled BAPA</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.efficiency-report') }}"
                   class="nav-link {{ Request::is('readings.efficiency-report*') ? 'active' : '' }}">                   
                   <i class="fas fa-chart-pie nav-icon text-primary"></i><p>Efficiency Report</p>
                </a>
            </li>
            <li class="nav-header">                
                CorPlan 
            </li>
            <li class="nav-item">
                <a href="{{ route('kwhSales.index') }}"
                   class="nav-link {{ Request::is('kwhSales.index*') ? 'active' : '' }}">                   
                   <i class="fas fa-plug nav-icon text-primary"></i><p>KWH Sales</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kwhSales.sales-distribution') }}"
                   class="nav-link {{ Request::is('kwhSales.sales-distribution*') ? 'active' : '' }}">                   
                   <i class="fas fa-file nav-icon text-primary"></i><p>Sales Distribution</p>
                </a>
            </li>
        </ul>
    </li> --}}
@endcanany

{{-- DISCONNECTION --}}
{{-- @canany(['Super Admin', 'billing re-bill', 'teller approve'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-users-slash nav-icon text-primary"></i>
            <p>
                Disconnection
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('discoNoticeHistories.index') }}"
                   class="nav-link {{ Request::is('discoNoticeHistories.index*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon text-primary"></i><p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('discoNoticeHistories.generate-nod') }}"
                   class="nav-link {{ Request::is('discoNoticeHistories.generate-nod*') ? 'active' : '' }}">
                    <i class="fas fa-file nav-icon text-primary"></i><p>Generate NoD</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('disconnectionHistories.generate-turn-off-list') }}"
                   class="nav-link {{ Request::is('disconnectionHistories.generate-turn-off-list*') ? 'active' : '' }}">
                    <i class="fas fa-ban nav-icon text-primary"></i><p>Turn-Off List 
                    </p>
                </a>
            </li>
        </ul>
    </li>
@endcanany --}}

<!-- TELLERING MENU -->
@canany(['Super Admin', 'teller create'])
    {{-- <li class="nav-header">COLLECTION</li>
    <li class="nav-item">
        <a href="{{ route('dCRSummaryTransactions.dashboard') }}"
        class="nav-link {{ Request::is('dCRSummaryTransactions.dashboard*') ? 'active' : '' }}">
        <i class="fas fa-chart-line nav-icon text-info"></i><p>Dashboard</p>
        </a>
    </li> --}}
    {{-- <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-credit-card nav-icon text-info"></i>
            <p>
                Collection
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('paidBills.index') }}"
                class="nav-link {{ Request::is('paidBills.index*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar nav-icon text-info"></i><p>Bills Payment</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paidBills.bapa-payments') }}"
                class="nav-link {{ Request::is('paidBills.bapa-payments*') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon text-info"></i><p>BAPA Payments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.service-connection-collection') }}"
                class="nav-link {{ Request::is('transactionIndices.service-connection-collection*') ? 'active' : '' }}">
                <i class="fas fa-plug nav-icon text-info"></i><p>Service Connection</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.uncollected-arrears') }}"
                class="nav-link {{ Request::is('transactionIndices.uncollected-arrears*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle nav-icon text-info"></i><p>Uncollected Arrears</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.reconnection-collection') }}"
                class="nav-link {{ Request::is('transactionIndices.reconnection-collection*') ? 'active' : '' }}">
                <i class="fas fa-link nav-icon text-info"></i><p>Reconnection</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.other-payments') }}"
                class="nav-link {{ Request::is('transactionIndices.other-payments*') ? 'active' : '' }}">
                <i class="fas fa-coins nav-icon text-info"></i><p>Other Payments</p>
                </a>
            </li>
        </ul>
    </li> --}}
@endcanany
{{-- @canany(['Super Admin', 'bapa adjust', 'billing re-bill'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-receipt nav-icon text-info"></i>
            <p>
                BAPA
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('bAPAAdjustments.search-bapa-monitor') }}"
                class="nav-link {{ Request::is('bAPAAdjustments.search-bapa-monitor*') ? 'active' : '' }}">
                <i class="fas fa-chart-line nav-icon text-info"></i><p>Collection Monitor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bAPAAdjustments.index') }}"
                class="nav-link {{ Request::is('bAPAAdjustments.index*') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon text-info"></i><p>New BAPA Adjustments</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany
@canany(['Super Admin', 'teller view'])
    <li class="nav-item">
        <a href="{{ route('transactionIndices.browse-ors') }}"
        class="nav-link {{ Request::is('transactionIndices.browse-ors*') ? 'active' : '' }}">
        <i class="fas fa-search nav-icon text-info"></i><p>Browse ORs</p>
        </a>
    </li>
@endcanany --}}
{{-- @canany(['Super Admin', 'teller create'])
    <li class="nav-item">
        <a href="{{ route('transactionIndices.or-maintenance') }}"
        class="nav-link {{ Request::is('transactionIndices.or-maintenance*') ? 'active' : '' }}">
        <i class="fas fa-circle nav-icon text-info"></i><p>OR Maintenance</p>
        </a>
    </li>
@endcanany --}}
{{-- @canany(['Super Admin', 'teller create', 'teller approve'])
    <li class="nav-item">
        <a href="{{ route('paidBills.third-party-collection') }}"
        class="nav-link {{ Request::is('paidBills.third-party-collection*') ? 'active' : '' }}">
        <i class="fas fa-sign-out-alt nav-icon text-info"></i><p>Third-party Collection
        </p>
        </a>
    </li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-ban nav-icon text-info"></i>
            <p>
                OR Cancellations
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('paidBills.or-cancellation') }}"
                class="nav-link {{ Request::is('paidBills.or-cancellation*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar nav-icon text-info"></i><p>Bills Payment</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('oRCancellations.other-payments') }}"
                class="nav-link {{ Request::is('oRCancellations.other-payments*') ? 'active' : '' }}">
                <i class="fas fa-plug nav-icon text-info"></i><p>Other Payments</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany
@canany(['Super Admin', 'teller approve'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-check nav-icon text-info"></i>
            <p>
                Approvals
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('oRCancellations.index') }}"
                class="nav-link {{ Request::is('oRCancellations.index*') ? 'active' : '' }}">
                <i class="fas fa-ban nav-icon text-info"></i><p>OR Cancellations</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany
@canany(['Super Admin', 'teller create', 'teller approve'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file nav-icon text-info"></i>
            <p>
                DCR and Sales
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('dCRSummaryTransactions.index') }}"
                class="nav-link {{ Request::is('dCRSummaryTransactions.index*') ? 'active' : '' }}">
                <i class="fas fa-file-alt nav-icon text-info"></i><p>DCR Summary</p>
                </a>
            </li>
            @canany(['Super Admin', 'teller approve'])
            <li class="nav-item">
                <a href="{{ route('dCRSummaryTransactions.sales-dcr-monitor') }}"
                class="nav-link {{ Request::is('dCRSummaryTransactions.sales-dcr-monitor*') ? 'active' : '' }}">
                <i class="fas fa-chart-area nav-icon text-info"></i><p>Sales Monitor</p>
                </a>
            </li>
            @endcanany
        </ul>
    </li>
@endcanany --}}
{{-- @canany(['Super Admin', 'teller create'])
    <li class="nav-header">                
        Others
    </li>
    <li class="nav-item">
        <a href="{{ route('prePaymentBalances.index') }}"
           class="nav-link {{ Request::is('prePaymentBalances.index*') ? 'active' : '' }}">
           <i class="fas fa-piggy-bank nav-icon text-info"></i><p>Pre-Payments/Deposits</p>
        </a>
    </li>
@endcanany --}}
{{-- @canany(['Super Admin', 'teller create', 'teller approve'])
    <li class="nav-header">                
        Settings
    </li>
    <li class="nav-item">
        <a href="{{ route('accountPayables.index') }}"
           class="nav-link {{ Request::is('accountPayables*') ? 'active' : '' }}">
           <i class="fas fa-circle nav-icon text-info"></i><p>Account Payables</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('accountGLCodes.index') }}"
           class="nav-link {{ Request::is('accountGLCodes*') ? 'active' : '' }}">
           <i class="fas fa-circle nav-icon text-info"></i><p>GL Codes</p>
        </a>
    </li>  
@endcanany --}}
<!-- EXTRAS MENU -->
@canany(['Super Admin', 'create membership', 'sc create', 'teller create', 'teller approve'])
    <li class="nav-header">MISCELLANEOUS</li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-ellipsis-v nav-icon"></i>
            <p>
                Extras
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('towns.index') }}"
                class="nav-link {{ Request::is('towns*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt nav-icon"></i><p>Towns</p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('barangays.index') }}"
                class="nav-link {{ Request::is('barangays*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt nav-icon"></i><p>Barangays</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('serviceConnectionCrews.index') }}"
                   class="nav-link {{ Request::is('serviceConnectionCrews*') ? 'active' : '' }}">
                   <i class="fas fa-map-marked-alt nav-icon"></i><p>Station Crews</p>
                </a>
            </li>
            
            {{-- <li class="nav-item">
                <a href="{{ route('rateItems.index') }}"
                class="nav-link {{ Request::is('rateItems*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon"></i><p>Rate Items</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('banks.index') }}"
                   class="nav-link {{ Request::is('banks*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon"></i><p>Banks</p>
                </a>
            </li> --}}
        </ul>
    </li>
@endcanany

@canany(['Super Admin', 'agma'])
    <li class="nav-item">
        <a href="{{ route('preRegEntries.index') }}"
        class="nav-link {{ Request::is('preRegEntries*') ? 'active' : '' }}">
        <i class="fas fa-check-circle     nav-icon"></i><p>AMGA 2022 Pre-Reg</p>
        </a>
    </li>
@endcanany

<!-- ADMIN MENU -->
@can('Super Admin')
    <li class="nav-header">ADMINISTRATIVE</li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-shield-alt nav-icon"></i>
            <p>
                Administrator
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('users.index') }}"
                class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                    <p><i class="fas fa-user-lock nav-icon"></i> Users</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('roles.index') }}"
                class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
                <i class="fas fa-unlock-alt nav-icon"></i><p>Roles</p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('permissions.index') }}"
                class="nav-link {{ Request::is('permissions*') ? 'active' : '' }}">
                <i class="fas fa-key nav-icon"></i><p>Permissions</p>
                </a>
            </li>
        </ul>
    </li>
@endcan



