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
                    <a href="{{ route('memberConsumers.daily-monitor') }}"
                    class="nav-link {{ Request::is('memberConsumers.daily-monitor*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-success"></i><p>Daily Monitor</p>
                    </a>
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
            @canany(['Super Admin', 'update membership'])
                <li class="nav-header">                
                    Others
                </li>

                <li class="nav-item">
                    <a href="{{ route('memberConsumers.trash') }}"
                    class="nav-link {{ Request::is('memberConsumers.trash*') ? 'active' : '' }}">
                    <i class="fas fa-trash nav-icon text-success"></i><p>Trash</p>
                    </a>
                </li>
            @endcanany
        </ul>
    </li>    
@endcanany


<!-- SERVICE CONNECTION MENU -->
@canany(['Super Admin', 'sc view'])
    <li class="nav-item has-treeview {{ Auth::user()->hasAnyRole(['Service Connection Assessor']) ? 'menu-open' : '' }}">
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
                </li>  --}}

                <li class="nav-item">
                    <a href="{{ route('serviceConnections.change-name-search') }}"
                    class="nav-link {{ Request::is('serviceConnections.change-name-search') ? 'active' : '' }}">
                        <i class="fas fa-plus nav-icon text-warning"></i>
                        <p>Change Name</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'update metering data'])
            <li class="nav-item">
                <a href="{{ route('serviceConnectionMtrTrnsfrmrs.assigning') }}"
                class="nav-link {{ Request::is('serviceConnectionMtrTrnsfrmrs.assigning') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon text-warning"></i>
                    <p>Assign Meters
                        <span id="assign-badge-count" class="right badge badge-danger">0</span>
                    </p>
                </a>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc powerload update'])
            <li class="nav-item">
                <a href="{{ route('serviceConnections.bom-index') }}"
                class="nav-link {{ Request::is('serviceConnections.bom-index') ? 'active' : '' }}">
                    <i class="fas fa-charging-station nav-icon text-warning"></i>
                    <p>Power Load Applications
                    </p>
                </a>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc powerload update', 'sc transformer ammortization'])
            <li class="nav-item">
                <a href="{{ route('serviceConnections.transformer-ammortizations') }}"
                class="nav-link {{ Request::is('serviceConnections.transformer-ammortizations') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-warning"></i>
                    <p>Transformer Ammortizations
                    </p>
                </a>
            </li>
            @endcanany

            @canany(['Super Admin', 'update metering data', 'sc update energization'])
            <li class="nav-item">
                <a href="{{ route('serviceConnections.energization') }}"
                class="nav-link {{ Request::is('serviceConnections.energization') ? 'active' : '' }}">
                    <i class="fas fa-tools nav-icon text-warning"></i>
                    <p>Energization
                    </p>
                </a>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc view'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Monitoring & Approvals
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
                    @canany(['Super Admin', 'sc update energization'])
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.crew-assigning') }}"
                        class="nav-link {{ Request::is('serviceConnections.crew-assigning*') ? 'active' : '' }}">
                        <i class="fas fa-hard-hat nav-icon text-warning"></i><p>Crew Assigning</p>
                        </a>
                    </li>
                    @endcanany
                    @canany(['Super Admin', 'sc update energization'])
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.change-name-for-approval') }}"
                        class="nav-link {{ Request::is('serviceConnections.change-name-for-approval*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Change Name Approvals</p>
                        </a>
                    </li>
                    @endcanany
                </ul>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc create'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Electricians
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('electricians.index') }}"
                        class="nav-link {{ Request::is('electricians.index*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Accrdtd. Electricians</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('electricians.housewiring-labor') }}"
                        class="nav-link {{ Request::is('electricians.housewiring-labor*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Housewiring Labor</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('electricians.labor-summary') }}"
                        class="nav-link {{ Request::is('electricians.labor-summary*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Labor Summary</p>
                        </a>
                    </li>
                </ul>
            </li>
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
                        <a href="{{ route('serviceConnections.meter-installation') }}"
                        class="nav-link {{ Request::is('serviceConnections.meter-installation*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon text-warning"></i><p>Meter Installation</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.kps-customer-service-parameters') }}"
                        class="nav-link {{ Request::is('tickets.kps-customer-service-parameters*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-warning"></i><p>KPS Cust. Svc. Params.</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.energization-per-brgy') }}"
                        class="nav-link {{ Request::is('serviceConnections.energization-per-brgy*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-warning"></i><p>Energization per Barangay</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.energization-per-town') }}"
                        class="nav-link {{ Request::is('serviceConnections.energization-per-town*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-warning"></i><p>Energization per Town</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.summary-report') }}"
                        class="nav-link {{ Request::is('serviceConnections.summary-report*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-warning"></i><p>Summary</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.service-drop') }}"
                        class="nav-link {{ Request::is('serviceConnections.service-drop*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-warning"></i><p>Energization Service Drop</p>
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
    <li class="nav-item has-treeview {{ Auth::user()->hasAnyRole(['Energization Clerk', 'CWD', 'Metering Personnel']) ? 'menu-open' : '' }}">
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
            
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="fas fa-plus-circle nav-icon text-danger"></i>
                    <p>
                        Create Ticket
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['Super Admin', 'tickets create'])
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
                    @endcanany
                    @canany(['Super Admin', 'create metering data'])
                    <li class="nav-item">
                        <a href="{{ route('tickets.change-meter') }}"
                        class="nav-link {{ Request::is('tickets.change-meter*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Change Meter</p>
                        </a>
                    </li>
                    @endcanany
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.account-finder') }}"
                class="nav-link {{ Request::is('tickets.account-finder*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt nav-icon text-danger"></i><p>Account Finder</p>
                </a>
            </li>

            @canany(['Super Admin', 'tickets edit'])
            <li class="nav-header">                
                ESD/Operations 
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.crew-field-monitor') }}"
                class="nav-link {{ Request::is('tickets.crew-field-monitor*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Crew Monitor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.assessments-ordinary-ticket') }}"
                class="nav-link {{ Request::is('tickets.assessments-ordinary-ticket*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Crew Assigning</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.execution') }}"
                class="nav-link {{ Request::is('tickets.execution*') ? 'active' : '' }}">
                    <i class="fas fa-hard-hat nav-icon text-danger"></i><p>Execution</p>
                </a>
            </li>
            @endcanany
            @canany(['Super Admin', 'create metering data'])
            <li class="nav-header">                
                Metering 
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.pending-change-meters') }}"
                class="nav-link {{ Request::is('tickets.pending-change-meters*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Pending Change Meters</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.crew-assigning-metering') }}"
                class="nav-link {{ Request::is('tickets.crew-assigning-metering*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Crew Assigning</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.meter-inspections') }}"
                class="nav-link {{ Request::is('tickets.meter-inspections*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Change Meter Inspections</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.meter-transfer-inspections') }}"
                class="nav-link {{ Request::is('tickets.meter-transfer-inspections*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Meter Transfer Inspections</p>
                </a>
            </li>
            @endcanany
            @canany(['Super Admin', 'tickets create', 'tickets edit', 'billing re-bill'])
            <li class="nav-header">                
                Reports
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.quarterly-report') }}"
                class="nav-link {{ Request::is('tickets.quarterly-report*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Quarterly Report</p>
                </a>
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

            <li class="nav-item">
                <a href="{{ route('tickets.nea-kps-summary') }}"
                class="nav-link {{ Request::is('tickets.nea-kps-summary*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>NEA KPS Summary</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.kps-customer-service-parameters') }}"
                class="nav-link {{ Request::is('tickets.kps-customer-service-parameters*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>KPS Cust. Svc. Params.</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.monthly-per-town') }}"
                class="nav-link {{ Request::is('tickets.monthly-per-town*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Monthly Per Town</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.meter-replacements') }}"
                class="nav-link {{ Request::is('tickets.meter-replacements*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Meter Replacements</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.disco-reco-reports') }}"
                class="nav-link {{ Request::is('tickets.disco-reco-reports*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Disco/Reco Report</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.executed-reconnections') }}"
                class="nav-link {{ Request::is('tickets.executed-reconnections*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Executed Reconnections</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.meter-transfers') }}"
                class="nav-link {{ Request::is('tickets.meter-transfers*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Meter Transfers</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.service-conductor-transfers') }}"
                class="nav-link {{ Request::is('tickets.service-conductor-transfers*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Svc. Condctr. Transfers</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.not-executed') }}"
                class="nav-link {{ Request::is('tickets.not-executed*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-circle nav-icon text-danger"></i><p>Unexecuted Tickets</p>
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

{{-- MISCELLANEOUS APPLICATIONS --}}
@canany(['Super Admin', 'sc view', 'ticket view'])
<li class="nav-item">
    <a href="{{ route('tickets.fleets') }}"
       class="nav-link {{ Request::is('tickets.fleets*') ? 'active' : '' }}">
       <i class="fas fa-car nav-icon"></i>
        <p>Fleet Monitor</p>
    </a>
</li>
@endcanany

{{-- MISCELLANEOUS APPLICATIONS --}}
@canany(['Super Admin', 'sc view', 'ticket view'])
    <li class="nav-item has-treeview {{ Auth::user()->hasAnyRole(['Service Connection Assessor']) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link">
            <i class="fas fa-circle nav-icon"></i>
            <p>
                Miscellaneous Applications
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @canany(['Super Admin', 'sc view'])
                <li class="nav-item">
                    <a href="{{ route('miscellaneousApplications.service-drop-purchasing') }}"
                       class="nav-link {{ Request::is('miscellaneousApplications.service-drop-purchasing*') ? 'active' : '' }}">
                       <i class="fas fa-circle nav-icon"></i>
                        <p>Purchase of Service Drop</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('miscellaneousApplications.transformer-testing') }}"
                       class="nav-link {{ Request::is('miscellaneousApplications.transformer-testing*') ? 'active' : '' }}">
                       <i class="fas fa-circle nav-icon"></i>
                        <p>Transformer Testing</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('miscellaneousApplications.disco-application') }}"
                       class="nav-link {{ Request::is('miscellaneousApplications.disco-application*') ? 'active' : '' }}">
                       <i class="fas fa-circle nav-icon"></i>
                        <p>Disco Application</p>
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
    {{-- <li class="nav-item">
        <a href="{{ route('bills.dashboard') }}"
           class="nav-link {{ Request::is('bills.dashboard*') ? 'active' : '' }}">                   
           <i class="fas fa-chart-line nav-icon text-primary"></i><p>Dashboard
           </p>
        </a>
    </li> --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file-invoice-dollar nav-icon text-primary"></i>
            <p>
                Billing
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('accountMasters.index') }}"
                   class="nav-link {{ Request::is('accountMasters.index*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-circle nav-icon text-primary"></i><p>Consumer Accounts</p>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('serviceAccounts.termed-payment-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.termed-payment-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-list nav-icon text-primary"></i><p>Accounts w/ OCL</p>
                </a>
            </li> --}}
            <li class="nav-header">                
                Others 
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.pending-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.pending-accounts*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-alt-slash nav-icon text-primary"></i><p>New Energized</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceConnections.new-energized-rewiring') }}"
                   class="nav-link {{ Request::is('serviceConnections.new-energized-rewiring*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-alt-slash nav-icon text-primary"></i><p>New Rewiring</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.change-meter-unconfirmed') }}"
                   class="nav-link {{ Request::is('tickets.change-meter-unconfirmed*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>New Change Meters</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('accountMasters.new-bapa-energized') }}"
                   class="nav-link {{ Request::is('accountMasters.new-bapa-energized*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>New Energized BAPA</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceConnections.approved-change-names') }}"
                   class="nav-link {{ Request::is('serviceConnections.approved-change-names*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>New Change of Names</p>
                </a>
            </li>

            <li class="nav-header">                
                Monitoring 
            </li>
            <li class="nav-item">
                <a href="{{ route('accountMasters.abrupt-increase-decrease') }}"
                   class="nav-link {{ Request::is('accountMasters.abrupt-increase-decrease*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Abrupt Inc/Dec Monitor</p>
                </a>
            </li>

            <li class="nav-header">                
                Reports 
            </li>
            <li class="nav-item">
                <a href="{{ route('accountMasters.reports-new-accounts') }}"
                   class="nav-link {{ Request::is('accountMasters.reports-new-accounts*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>New Accounts</p>
                </a>
            </li>
            {{-- <li class="nav-item">
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
            </li> --}}
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
    {{-- <li class="nav-item has-treeview">
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
    </li> --}}

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
@canany(['Super Admin', 'disco monitor'])
    <li class="nav-header">DISCONNECTION</li>
    
    <li class="nav-item">
        <a href="{{ route('disconnectionDatas.index') }}"
           class="nav-link {{ Request::is('disconnectionDatas.index*') ? 'active' : '' }}">
            <i class="fas fa-chart-line nav-icon text-primary"></i><p>Dashboard</p>
        </a>
    </li>
@endcanany

@canany(['Super Admin', 'disco create schedule'])
    <li class="nav-item">
        <a href="{{ route('disconnectionSchedules.index') }}"
           class="nav-link {{ Request::is('disconnectionSchedules.index*') ? 'active' : '' }}">
            <i class="fas fa-calendar nav-icon text-primary"></i><p>Schedules</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('disconnectionSchedules.monitor') }}"
           class="nav-link {{ Request::is('disconnectionSchedules.monitor*') ? 'active' : '' }}">
            <i class="fas fa-check nav-icon text-primary"></i><p>Monitor</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('disconnectionSchedules.weekly-report') }}"
           class="nav-link {{ Request::is('disconnectionSchedules.weekly-report*') ? 'active' : '' }}">
            <i class="fas fa-list nav-icon text-primary"></i><p>Weekly Report</p>
        </a>
    </li>
@endcanany

<!-- TELLERING MENU -->
@canany(['Super Admin', 'teller create'])
    <li class="nav-header">COLLECTION</li>
    {{-- <li class="nav-item">
        <a href="{{ route('dCRSummaryTransactions.dashboard') }}"
        class="nav-link {{ Request::is('dCRSummaryTransactions.dashboard*') ? 'active' : '' }}">
        <i class="fas fa-chart-line nav-icon text-info"></i><p>Dashboard</p>
        </a>
    </li> --}}
    <li class="nav-item">
        <a href="{{ route('disconnectionDatas.disco-teller-module') }}"
        class="nav-link {{ Request::is('disconnectionDatas.disco-teller-module*') ? 'active' : '' }}">
        <i class="fas fa-exclamation-circle nav-icon text-info"></i><p>Disconnection</p>
        </a>
    </li>
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
        <i class="fas fa-check-circle     nav-icon"></i><p>AMGA 2023 Pre-Reg</p>
        </a>
    </li>
@endcanany

<!-- ADMIN MENU -->
@can('Super Admin')
    <li class="nav-header">ADMINISTRATIVE</li>
    <li class="nav-item">
        <a href="{{ route('administrative.server-monitor') }}"
           class="nav-link {{ Request::is('administrative.server-monitor*') ? 'active' : '' }}">
            <i class="fas fa-server nav-icon"></i><p>Server Monitor</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('sites.index') }}"
           class="nav-link {{ Request::is('sites*') ? 'active' : '' }}">
           <i class="fas fa-globe nav-icon"></i><p>Site Monitor</p>
        </a>
    </li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-shield-alt nav-icon"></i>
            <p>
                Authentication
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

<!-- SHORTCUTS -->
@can('Super Admin')
    <li class="nav-header">SHORTCUTS</li>
    <li class="nav-item">
        <a href="http://192.168.10.200:8000" target="_blank"
           class="nav-link">
            <i class="fas fa-globe nav-icon"></i><p>Assist</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="http://192.168.10.16/bill-mailer/" target="_blank"
           class="nav-link">
            <i class="fas fa-envelope nav-icon"></i><p>Bill Mailer</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="http://192.168.10.16/blaster-brain/index.php" target="_blank"
           class="nav-link">
            <i class="fas fa-sms nav-icon"></i><p>Text Blaster</p>
        </a>
    </li>
@endcan





