@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                 <h4>
                    <span class="text-muted">Disco Schedule: </span><strong>{{ $disconnectionSchedules->DisconnectorName }}</strong> | 
                    <span class="text-muted">Day: </span> <span class="text-danger">{{ date('M d, Y', strtotime($disconnectionSchedules->Day)) }}</span> | 
                    <span class="text-muted">Billing Month: </span> {{ date('F Y', strtotime($disconnectionSchedules->ServicePeriodEnd)) }}
                 </h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- Summary --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-list ico-tab"></i>Summary
                    <div id="loader" class="spinner-border text-danger float-right" role="status" style="width: 22px; height: 22px; margin-left: 10px;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </span>

                <button onclick="setSchedule(`{{ $disconnectionSchedules->DisconnectorId }}`)" class="btn btn-sm btn-warning float-right">Update Schedule</button>
                <input type="text" maxlength="4" class="form-control form-control-sm float-right" id="route-to" style="width: 120px; margin-right: 10px;" placeholder="Route To">
                <input type="text" maxlength="4" class="form-control form-control-sm float-right" id="route-from" style="width: 120px; margin-right: 5px;" placeholder="Route From">
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- ROUTES --}}
                    <div class="col-lg-7">
                        <table class="table table-sm table-hover" id="route-table">
                            <thead>
                                <th>Route</th>
                                <th>Seq. From</th>
                                <th>Seq. To</th>
                                <th>No. Of Disco.</th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    {{-- PREVIEW --}}
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Quick Stats
                                    <div id="loaderStats" class="spinner-border text-primary float-right gone" role="status" style="width: 22px; height: 22px; margin-left: 10px;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="text-muted text-center" style="padding: 0 !important; margin: 0 !important;">Total Bills</p>
                                        <h4 class="text-primary text-center" id="totalConsumerStats"></h4>
                                    </div>
                                    <div class="col-lg-6">
                                        <p class="text-muted text-center" style="padding: 0 !important; margin: 0 !important;">Total Amount</p>
                                        <h4 class="text-danger text-center" id="totalAmountStats"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed View --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Consumers in this Schedule
                    <div id="loaderAccounts" class="spinner-border text-danger float-right" role="status" style="width: 22px; height: 22px; margin-left: 10px;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered" id="accounts-table">                    
                    <thead>
                        <th>#</th>
                        <th>Account Number</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                        <th>Meter Number</th>
                        <th>Account Type</th>
                        <th>Account Status</th>
                        <th>Billing Month</th>
                        <th>Amount Due</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            getRoutes("{{ $disconnectionSchedules->DisconnectorId }}", "{{ $disconnectionSchedules->Day }}{{ $disconnectionSchedules->ServicePeriodEnd }}")
            getStats("{{ $disconnectionSchedules->DisconnectorId }}", "{{ $disconnectionSchedules->Day }}", "{{ $disconnectionSchedules->ServicePeriodEnd }}")
        })

        function setSchedule(id) {
            var from = $('#route-from').val()
            var to = $('#route-to').val()
            var day = "{{ $disconnectionSchedules->Day }}"
            var period = "{{ $disconnectionSchedules->ServicePeriodEnd }}"

            $('#loader').removeClass('gone')

            if (jQuery.isEmptyObject(day)) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Please select day!'
                })
            } else {
                if (jQuery.isEmptyObject(from)) {
                    Toast.fire({
                        icon : 'warning',
                        text : 'Please input Route From!'
                    })
                } else {
                    if (jQuery.isEmptyObject(to)) {
                        to = from
                    }

                    $.ajax({
                        url : "{{ route('disconnectionSchedules.set-schedule') }}",
                        type : "GET",
                        data : {
                            From : from,
                            To : to,
                            Day : day,
                            UserId : id,
                            Period : period,
                        },
                        success : function(res) {
                            getRoutes(id, day + "" + period)
                            getStats(id, day, period)
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error getting routes!'
                            })
                        }
                    })
                }  
            }      
        }

        function getRoutes(id, day) {
            var schedId = id + "-" + day
            $.ajax({
                url : "{{ route('disconnectionSchedules.get-routes') }}",
                type : "GET",
                data : {
                    ScheduleId : schedId
                },
                success : function(res) {
                    $('#route-table tbody tr').remove()
                    $('#route-table tbody').append(res)

                    $('#loader').addClass('gone')
                    getAccounts(schedId)
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting routes!'
                    })
                    $('#loader').addClass('gone')
                }
            })
        }

        function removeRoute(id) {
            $.ajax({
                url : "{{ url('/disconnectionRoutes') }}/" + id,
                type : "DELETE",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                },
                success : function(res) {
                    $('#' + id).remove()
                    getStats(res["DisconnectorId"], res["Day"], res["ServicePeriodEnd"])
                    getAccounts(res["DisconnectorId"], res["Day"]+""+res["ServicePeriodEnd"])
                    Toast.fire({
                        icon : 'success',
                        text : 'Route removed from schedule!'
                    })
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error removing route!'
                    })
                }
            })            
        }

        function saveRoute(id) {
            $.ajax({
                url : "{{ url('/disconnectionRoutes') }}/" + id,
                type : "PATCH",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                    SequenceFrom : $('#from-' + id).val(),
                    SequenceTo : $('#to-' + id).val(),
                },
                success : function(res) {
                    $('#loader').removeClass('gone')
                    getRoutes(res["DisconnectorId"], res["Day"] + "" + res["ServicePeriodEnd"])
                    getStats(res["DisconnectorId"], res["Day"], res["ServicePeriodEnd"])

                    Toast.fire({
                        icon : 'success',
                        text : 'Route updated!'
                    })                    
                }, 
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error removing route!'
                    })
                }
            })
        }

        function getStats(id, day, period) {
            $('#loaderStats').removeClass('gone')
            $('#viewConsumers').addClass('gone')
            $.ajax({
                url : "{{ route('disconnectionSchedules.get-stats') }}",
                type : "GET",
                data : {
                    Day : day,
                    UserId : id,
                    Period : period,
                },
                success : function(res) {
                    $('#totalConsumerStats').text(res['TotalCount'])
                    $('#totalAmountStats').text(Number.parseFloat(res['TotalAmount']).toLocaleString(2))
                    $('#loaderStats').addClass('gone')
                    
                    $('#viewConsumers').removeClass('gone')
                }, 
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting stats!'
                    })
                    $('#loaderStats').addClass('gone')
                }
            })
        }

        function getAccounts(id) {
            $('#loaderAccounts').removeClass('gone')
            $.ajax({
                url : "{{ route('disconnectionSchedules.get-accounts-from-schedule') }}",
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    $('#accounts-table tbody tr').remove()
                    $('#accounts-table tbody').append(res)
                    $('#loaderAccounts').addClass('gone')
                },
                error : function(err) {
                    $('#loaderAccounts').addClass('gone')
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting accounts'
                    })
                }
            })
        }
    </script>
@endpush
