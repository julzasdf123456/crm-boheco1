<?php

use App\Models\ServiceConnections;
use Illuminate\Support\Facades\Auth;

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div>                            
                @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor'])) 
                    <a href="{{ route('serviceConnections.edit', [$serviceConnections->id]) }}" class="btn btn-tool text-warning" title="Edit service connection details">
                        <i class="fas fa-pen"></i>
                    </a>
                    @if ($totalTransactions != null)
                        @if ($totalTransactions->Notes == null && $serviceConnections->ORNumber == null) 
                            <a href="{{ route('serviceConnectionPayTransactions.create-step-four', [$serviceConnections->id]) }}" class="btn btn-tool text-success" title="Update service connection payment">
                            <i class="fas fa-dollar-sign"></i></a>
                        @else
                            @if (Auth::user()->hasAnyRole(['Administrator'])) 
                                <a href="{{ route('serviceConnectionPayTransactions.create-step-four', [$serviceConnections->id]) }}" class="btn btn-tool text-success" title="Update service connection payment">
                                <i class="fas fa-dollar-sign"></i></a>
                            @endif
                        @endif

                        <a href="{{ route('serviceConnections.print-invoice', [$serviceConnections->id]) }}" class="btn btn-tool text-success" title="Print Payment Slip">
                            <i class="fas fa-comments-dollar"></i>
                        </a>  

                        {{-- INSTALLATION FEE - PLANNING --}}
                        <button onclick="showBomModal()" class="btn btn-tool" style="color: #ff7b00;" title="Update Installation Fee (BoM Figure)"><i class="fas fa-coins"></i></button>
                        @if ($serviceConnections->LoadCategory >= .25)
                            <a href="{{ route('serviceConnections.print-quotation-form', [$serviceConnections->id]) }}" class="btn btn-tool" style="color: #ff7b00;" title="Print Quotation with Embedded Installation Fee">
                                <i class="fas fa-file-contract"></i>
                            </a> 

                            <a href="{{ route('serviceConnections.print-quotation-form-separate-installation-fee', [$serviceConnections->id]) }}" class="btn btn-tool" style="color: #ff1e00;" title="Print Quotation with Separate Installation Fee">
                                <i class="fas fa-file-contract"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('serviceConnectionPayTransactions.create-step-four', [$serviceConnections->id]) }}" class="btn btn-tool text-success" title="Update service connection payment">
                            <i class="fas fa-dollar-sign"></i></a>
                    @endif

                    @if ($serviceConnectionInspections != null)
                    <a href="{{ route('serviceConnectionInspections.edit', [$serviceConnectionInspections->id]) }}" class="btn btn-tool text-primary" title="Update Verification/Inspection Details">
                        <i class="fas fa-clipboard-check"></i>
                    </a>
                    @endif

                    {{-- CHANGE NAME --}}
                    @if ($serviceConnections->ConnectionApplicationType == 'Change Name')
                        <a href="{{ route('serviceConnections.print-change-name', [$serviceConnections->id]) }}" class="btn btn-tool text-info" title="Print change name certificate">
                            <i class="fas fa-file-invoice"></i>
                        </a>  
                    @endif
                    <a class="btn btn-tool text-info" href="{{ route('serviceConnections.assess-checklists', [$serviceConnections->id]) }}" title="Update requirements"><i class="fas fa-check-circle"></i></a>
                    <a href="{{ route('serviceConnections.move-to-trash', [$serviceConnections->id]) }}" class="btn btn-tool text-danger" title="Move to trash">
                        <i class="fas fa-trash"></i>
                    </a>  
                @endif
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                {{-- APPLICATON DETAILS --}}
                <div class="card shadow-none">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-sm-12">  
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar progress-bar-striped {{ ServiceConnections::getBgStatus($serviceConnections->Status) }}" role="progressbar" style="width: {{ ServiceConnections::getProgressStatus($serviceConnections->Status) }}%" aria-valuenow="{{ ServiceConnections::getProgressStatus($serviceConnections->Status) }}" aria-valuemin="0" aria-valuemax="10"></div>
                                </div>                  
                                <span style="margin-top: 5px;" class="badge {{ ServiceConnections::getBgStatus($serviceConnections->Status) }}"><strong>{{ $serviceConnections->Status }}</strong></span>
                            </div> 
                        </div>
                        
                    </div>
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

                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td><strong><i class="far fa-calendar mr-1"></i> Date of Application</strong></td>
                                    <td>{{ date('F d, Y', strtotime($serviceConnections->DateOfApplication)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong></td>
                                    <td>{{ ServiceConnections::getAddress($serviceConnections) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-phone mr-1"></i> Contact Info</strong></td>
                                    <td>{{ ServiceConnections::getContactInfo($serviceConnections) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-search-plus mr-1"></i> Account Count</strong></td>
                                    <td>{{ $serviceConnections->AccountCount }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-code-branch mr-1"></i> Account Type</strong></td>
                                    <td>{{ $serviceConnections->AccountType }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-info-circle mr-1"></i> Load (kVA)</strong></td>
                                    <td>{{ $serviceConnections->LoadCategory }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-code-branch mr-1"></i> Application Type</strong></td>
                                    <td>{{ $serviceConnections->ConnectionApplicationType }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-warehouse mr-1"></i> Office Registered</strong></td>
                                    <td>{{ $serviceConnections->Office}}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-file-alt mr-1"></i> Notes/Remarks</strong></td>
                                    <td>{{ $serviceConnections->Notes}}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers'])) 
                            <hr>
                            <button id="override" class="btn btn-danger btn-sm float-right" style="margin-left: 10px;">Override Status</button>
                            <select name="Status" id="Status" class="form-control form-control-sm float-right" style="width: 200px;">
                                <option {{ $serviceConnections->Status=="Approved" ? 'selected' : '' }} value="Approved">Approved</option>
                                <option {{ $serviceConnections->Status=="Approved For Change Name" ? 'selected' : '' }} value="Approved For Change Name">Approved For Change Name</option>
                                <option {{ $serviceConnections->Status=="Closed" ? 'selected' : '' }} value="Closed">Closed</option>
                                <option {{ $serviceConnections->Status=="Downloaded by Crew" ? 'selected' : '' }} value="Downloaded by Crew">Downloaded by Crew</option>
                                <option {{ $serviceConnections->Status=="Energized" ? 'selected' : '' }} value="Energized">Energized</option>
                                <option {{ $serviceConnections->Status=="For Inspection" ? 'selected' : '' }} value="For Inspection">For Inspection</option>
                                <option {{ $serviceConnections->Status=="Forwarded To Planning" ? 'selected' : '' }} value="Forwarded to Planning">Forwarded To Planning</option>
                                <option {{ $serviceConnections->Status=="Forwarded to Accounting" ? 'selected' : '' }} value="Forwarded to Accounting">Forwarded to Accounting</option>
                            </select>
                        @endif
                        
                    </div>
                </div> 

                @if ($totalTransactions != null)
                    {{-- PAYMENT FORWARDING --}}
                    @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Power Load Personnel'])) 
                        <div class="card shadow-none">
                            <div class="card-header">
                                <span class="card-title"><i class="fas fa-shield-alt ico-tab"></i>Cashier Queue Payment Forwarder</span>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>Remittance Fees</td>
                                            <td class="text-right">{{ $totalTransactions != null ? (is_numeric($totalTransactions->Total) ? number_format($totalTransactions->Total, 2) : '0.00') : '0.00' }}</td>
                                            <td class="text-right">
                                                @if ($totalTransactions != null && $totalTransactions->RemittanceForwarded=='Yes')
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Forwarded</span>
                                                @else
                                                    <button id="forward-remittance" class="btn btn-xs btn-primary">Forward <i class="fas fa-arrow-right"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $materialsTotal = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost + $totalTransactions->MaterialsVAT) : 0;
                                        @endphp
                                        @if ($materialsTotal > 0)
                                            <tr>
                                                <td>Installation Fees</td>
                                                @php
                                                    $materialsTotal = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost + $totalTransactions->MaterialsVAT) : 0;
                                                    $pn = $totalTransactions != null ? ($totalTransactions->InstallationFeeBalance != null && $totalTransactions->InstallationFeeBalance > 0 ? $totalTransactions->InstallationFeeBalance : 0) : 0;
                                                @endphp
                                                <td class="text-right">{{ number_format($materialsTotal - $pn, 2) }}</td>
                                                <td class="text-right">
                                                    @if ($totalTransactions != null && $totalTransactions->InstallationForwarded=='Yes')
                                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Forwarded</span>
                                                    @else
                                                        <button id="forward-installation-fees" class="btn btn-xs btn-primary">Forward <i class="fas fa-arrow-right"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        
                                        @if ($totalTransactions != null && $totalTransactions->TransformerCost > 0)
                                            <tr>
                                                <td>Transformer Fees</td>
                                                <td class="text-right">{{ $totalTransactions != null ? (is_numeric($totalTransactions->TransformerCost) ? number_format($totalTransactions->TransformerCost, 2) : '0.00') : '0.00' }}</td>
                                                <td class="text-right">
                                                    @if ($totalTransactions != null && $totalTransactions->TransformerForwarded=='Yes')
                                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Forwarded</span>
                                                    @else
                                                        <button id="forward-transformer-fees" class="btn btn-xs btn-primary">Forward <i class="fas fa-arrow-right"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        
                                    </tbody>
                                </table>

                                @if ($totalTransactions != null && ($totalTransactions->TransformerForwarded!='Yes' | $totalTransactions->InstallationForwarded!='Yes' | $totalTransactions->RemittanceForwarded!='Yes'))
                                    <div class="divider"></div>
                                    <button id="forward-all" class="btn btn-sm btn-primary float-right">Forward All to Cashier <i class="fas fa-arrow-right"></i></button>
                                @endif
                                
                            </div>
                        </div>
                    @endif
                @endif
                
            </div>

            {{-- TABS --}}
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
                            {{-- @if (is_numeric($serviceConnections->LoadCategory) && floatval($serviceConnections->LoadCategory) >= 15)
                            
                            @endif --}}
                            <li class="nav-item"><a class="nav-link" href="#bom" data-toggle="tab">
                                <i class="fas fa-toolbox"></i>
                                Bill of Materials</a></li>
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

@include('service_connections.modal_update_installation_fee')

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

            $('#override').on('click', function(e) {
                e.preventDefault()
                var status = $('#Status').val()

                $.ajax({
                    url : "{{ route('serviceConnections.update-status') }}",
                    type : 'GET',
                    data : {
                        id : "{{ $serviceConnections->id }}",
                        Status : status
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'Error updating status'
                        })
                    }
                })
            })

            $('#forward-remittance').on('click', function() {
                Swal.fire({
                    title: "Forward Remittance to Cashier?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.forward-remittance') }}",
                            type : "GET",
                            data : {
                                ServiceConnectionId : "{{ $serviceConnections->id }}",
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Remittance fees forwarded to cashier!'
                                })
                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    text : 'Error forwarding remittance!'
                                })
                                console.log(err)
                            }
                        })
                    }
                });
            })

            $('#forward-installation-fees').on('click', function() {
                Swal.fire({
                    title: "Forward Installation Fees to Cashier?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.forward-installation-fees') }}",
                            type : "GET",
                            data : {
                                ServiceConnectionId : "{{ $serviceConnections->id }}",
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Installation fees forwarded to cashier!'
                                })
                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    text : 'Error forwarding installation fees!'
                                })
                                console.log(err)
                            }
                        })
                    }
                });
            })

            $('#forward-transformer-fees').on('click', function() {
                Swal.fire({
                    title: "Forward Transformer Fees to Cashier?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.forward-transformer-fees') }}",
                            type : "GET",
                            data : {
                                ServiceConnectionId : "{{ $serviceConnections->id }}",
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Transformer fees forwarded to cashier!'
                                })
                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    text : 'Error forwarding transformer fees!'
                                })
                                console.log(err)
                            }
                        })
                    }
                });
            })

            $('#forward-all').on('click', function() {
                Swal.fire({
                    title: "Forward All Unforwarded Fees to Cashier for Payment?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.forward-all-fees') }}",
                            type : "GET",
                            data : {
                                ServiceConnectionId : "{{ $serviceConnections->id }}",
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'All fees forwarded to cashier!'
                                })
                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    text : 'Error forwarding all fees!'
                                })
                                console.log(err)
                            }
                        })
                    }
                });
            })
        });

        function showBomModal() {
            $('#modal-installation-fee').modal({backdrop: 'static', keyboard: false}, 'show')
        }
    </script>
@endpush
