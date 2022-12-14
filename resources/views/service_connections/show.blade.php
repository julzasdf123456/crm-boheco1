<?php

use App\Models\ServiceConnections;
use Illuminate\Support\Facades\Auth;

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">  
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar progress-bar-striped {{ ServiceConnections::getBgStatus($serviceConnections->Status) }}" role="progressbar" style="width: {{ ServiceConnections::getProgressStatus($serviceConnections->Status) }}%" aria-valuenow="{{ ServiceConnections::getProgressStatus($serviceConnections->Status) }}" aria-valuemin="0" aria-valuemax="10"></div>
                    </div>                  
                    <span class="badge {{ ServiceConnections::getBgStatus($serviceConnections->Status) }}"><strong>{{ $serviceConnections->Status }}</strong></span>
                </div> 
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                {{-- APPLICATON DETAILS --}}
                <div class="card {{ is_numeric($serviceConnections->LoadCategory) && floatval($serviceConnections->LoadCategory) >= 15 ? 'card-danger' : 'card-primary' }} card-outline shadow-none">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img id="prof-img" class="profile-user-img img-fluid img-circle" src="" alt="User profile picture">
                        </div>

                        <h3 title="Go to Membership Profile" class="profile-username text-center"><a href="{{ $serviceConnections->MemberConsumerId != null ? route('memberConsumers.show', [$serviceConnections->MemberConsumerId]) : '' }}">{{ $serviceConnections->ServiceAccountName }}</a></h3>
                        <p class="text-muted text-center">
                            {{ $serviceConnections->id }} ({{ $serviceConnections->AccountApplicationType }}) 
                            @if ($serviceConnections->ORNumber != null)
                                <span class="badge badge-success">Paid</span>
                            @endif
                        </p>

                        <hr>

                        <strong><i class="far fa-calendar mr-1"></i> Date of Application</strong>
                        <p class="text-muted">{{ date('F d, Y', strtotime($serviceConnections->DateOfApplication)) }}</p>

                        <hr>                        

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                        <p class="text-muted">{{ ServiceConnections::getAddress($serviceConnections) }}</p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Contact Info</strong>
                        <p class="text-muted">{{ ServiceConnections::getContactInfo($serviceConnections) }}</p>

                        <hr>

                        <strong><i class="fas fa-search-plus mr-1"></i> Account Count</strong>
                        <p class="text-muted">{{ $serviceConnections->AccountCount }}</p>

                        <hr>

                        <strong><i class="fas fa-code-branch mr-1"></i> Account Type</strong>
                        <p class="text-muted">{{ $serviceConnections->AccountType }}</p>

                        <hr>

                        <strong><i class="fas fa-code-branch mr-1"></i> Application Type</strong>
                        <p class="text-muted">{{ $serviceConnections->ConnectionApplicationType }}</p>

                        <hr>

                        <strong><i class="fas fa-file-alt mr-1"></i> Notes</strong>
                        <p class="text-muted">{{ $serviceConnections->Notes}}</p>

                        <hr>

                        <strong><i class="fas fa-warehouse mr-1"></i> Office Registered</strong>
                        <p class="text-muted">{{ $serviceConnections->Office}}</p>

                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor'])) 
                            <a href="{{ route('serviceConnections.edit', [$serviceConnections->id]) }}" class="text-warning" title="Edit service connection details">
                                <i class="fas fa-pen"></i>
                            </a>

                            @if ($serviceConnections->ConnectionApplicationType == 'Change Name' && $serviceConnections->ORNumber != null)
                                @if ($serviceConnections->Status == 'Approved For Change Name')
                                    
                                @else
                                    <a href="{{ route('serviceConnections.approve-change-name', [$serviceConnections->id]) }}" class="text-success" title="Approve Change Name and Forward to Billing Analyst" style="margin-left: 20px;">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                @endif                                
                            @endif

                            <a href="{{ route('serviceConnections.move-to-trash', [$serviceConnections->id]) }}" class="text-danger float-right" title="Move to trash">
                                <i class="fas fa-trash"></i>
                            </a>                            
                        @endif
                        
                    </div>
                    <div class="card-footer">
                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor']))
                            @if ($serviceConnections->MemberConsumerId != null)
                                <a class="btn btn-success btn-xs" href="{{ route('memberConsumers.print-membership-application', [$serviceConnections->MemberConsumerId]) }}" title="Print Application Form">
                                    <i class="fas fa-print"> </i> Membership Form
                                </a>
                                <a href="{{ route('memberConsumers.print-certificate', [$serviceConnections->MemberConsumerId]) }}" class="btn btn-xs btn-warning" title="Print Certificate"><i class="fas fa-print"></i>
                                    Certificate
                                </a>
                            @endif 
                            
                            <a class="btn btn-primary btn-xs" href="{{ route('serviceConnections.print-service-connection-application', [$serviceConnections->id]) }}" title="Print Service Connection Application">
                                <i class="fas fa-print"> </i> Application Form
                            </a>

                            {{-- <a class="btn btn-danger btn-xs" href="{{ route('serviceConnections.print-service-connection-contract', [$serviceConnections->id]) }}" class="text-danger" title="Print Service Connection Contract">
                                <i class="fas fa-print"> </i> Contract
                            </a>                         --}}
                        @endif
                    </div>
                </div> 
            </div>

            <div class="col-md-8 col-lg-8">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#verification" data-toggle="tab">
                                <i class="fas fa-clipboard-check"></i>
                                Verification</a></li>
                            <li class="nav-item"><a class="nav-link" href="#metering" data-toggle="tab">
                                <i class="fas fa-tachometer-alt"></i>
                                Metering and Transformer</a></li>
                            <li class="nav-item"><a class="nav-link" href="#invoice" data-toggle="tab">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Payment Invoice</a></li>
                            @if (is_numeric($serviceConnections->LoadCategory) && floatval($serviceConnections->LoadCategory) >= 15)
                            <li class="nav-item"><a class="nav-link" href="#bom" data-toggle="tab">
                                <i class="fas fa-toolbox"></i>
                                Bill of Materials</a></li>
                            @endif
                            <li class="nav-item"><a class="nav-link" href="#requirements" data-toggle="tab">
                                <i class="fas fa-info-circle"></i>
                                Requirements & Crew</a></li>
                            <li class="nav-item"><a class="nav-link" href="#logs" data-toggle="tab">
                                <i class="fas fa-list"></i>
                                Logs</a></li>
                            <li class="nav-item"><a class="nav-link" href="#photos" data-toggle="tab">
                                <i class="fas fa-file-image"></i>
                                Photos</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="verification">
                                @include('service_connections.verification')
                            </div>

                            <div class="tab-pane" id="metering">
                                @include('service_connections.metering')
                            </div>

                            <div class="tab-pane" id="invoice">
                                @include('service_connections.invoice')
                            </div>
                            
                            <div class="tab-pane" id="bom">
                                @include('service_connections.bom_details')
                            </div>

                            <div class="tab-pane" id="requirements">
                                @include('service_connections.details')
                            </div>

                            <div class="tab-pane" id="logs">
                                @include("service_connections.logs")
                            </div>

                            <div class="tab-pane" id="photos">
                                @include("service_connections.photos")
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // LOAD IMAGE
            $.ajax({
                url : '/member_consumer_images/get-image/' + "{{ $serviceConnections->MemberConsumerId }}",
                type : 'GET',
                success : function(result) {
                    var data = JSON.parse(result)
                    $('#prof-img').attr('src', data['img'])
                },
                error : function(error) {
                    console.log(error);
                }
            })
        });
    </script>
@endpush
