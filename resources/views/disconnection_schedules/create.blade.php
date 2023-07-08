@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Create Disconnection Schedule</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('adminlte-templates::common.errors')
        <div class="row">
            {{-- PARAMETERS --}}
            <div class="col-lg-3">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Schedule Parameters</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="Period">Billing Month</label>
                            <select name="Period" id="Period" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ $currentMonth != null && $currentMonth->ServicePeriodEnd != null && $currentMonth->ServicePeriodEnd==$months[$i] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Day">Day</label>
                            <input type="text" name="Day" id="Day" class="form-control form-control-sm">

                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#Day').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>
                    </div>
                </div>
            </div>

            {{-- SCHEDULER --}}   
            <div class="col-lg-9">         
                @foreach ($meterReaders as $item)
                    <div class="card shadow-none">
                        <div class="card-header">
                            <span class="card-title"><i class="fas fa-calendar ico-tab"></i>
                                <strong>{{ $item->name }}</strong>
                                <div id="loader-{{ $item->id }}" class="spinner-border text-danger float-right gone" role="status" style="width: 22px; height: 22px; margin-left: 10px;">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>

                            <button onclick="setSchedule(`{{ $item->id }}`)" class="btn btn-sm btn-primary float-right">Set Schedule</button>
                            <input type="text" maxlength="4" class="form-control form-control-sm float-right" id="route-to-{{ $item->id }}" style="width: 120px; margin-right: 10px;" placeholder="Route To">
                            <input type="text" maxlength="4" class="form-control form-control-sm float-right" id="route-from-{{ $item->id }}" style="width: 120px; margin-right: 5px;" placeholder="Route From">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- ROUTES --}}
                                <div class="col-lg-7">
                                    <table class="table table-sm table-hover" id="route-table-{{ $item->id }}">
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
                                                <div id="loaderStats-{{ $item->id }}" class="spinner-border text-primary float-right gone" role="status" style="width: 22px; height: 22px; margin-left: 10px;">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </span>
                                            
                                            <div class="card-tools">
                                                <button id="viewConsumers-{{ $item->id }}" onclick="viewConsumers(`{{ $item->id }}`)" class="btn btn-danger btn-xs gone"><i class="fas fa-share ico-tab-mini"></i>View Consumers</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <p class="text-muted text-center" style="padding: 0 !important; margin: 0 !important;">Total Consumers</p>
                                                    <h4 class="text-primary text-center" id="totalConsumerStats-{{ $item->id }}"></h4>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p class="text-muted text-center" style="padding: 0 !important; margin: 0 !important;">Total Amount</p>
                                                    <h4 class="text-danger text-center" id="totalAmountStats-{{ $item->id }}"></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function setSchedule(id) {
            var from = $('#route-from-' + id).val()
            var to = $('#route-to-' + id).val()
            var day = $('#Day').val()
            var period = $('#Period').val()

            $('#loader-' + id).removeClass('gone')

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
                    $('#route-table-' + id + ' tbody tr').remove()
                    $('#route-table-' + id + ' tbody').append(res)

                    $('#loader-' + id).addClass('gone')
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting routes!'
                    })
                    $('#loader-' + id).addClass('gone')
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
                    $('#loader-' + res["DisconnectorId"]).removeClass('gone')
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
            $('#loaderStats-' + id).removeClass('gone')
            $('#viewConsumers-' + id).addClass('gone')
            $.ajax({
                url : "{{ route('disconnectionSchedules.get-stats') }}",
                type : "GET",
                data : {
                    Day : day,
                    UserId : id,
                    Period : period,
                },
                success : function(res) {
                    $('#totalConsumerStats-' + id).text(res['TotalCount'])
                    $('#totalAmountStats-' + id).text(Number.parseFloat(res['TotalAmount']).toLocaleString(2))
                    $('#loaderStats-' + id).addClass('gone')
                    
                    $('#viewConsumers-' + id).removeClass('gone')
                }, 
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting stats!'
                    })
                    $('#loaderStats-' + id).addClass('gone')
                }
            })
        }

        function viewConsumers(id) {            
            var day = $('#Day').val()
            var period = $('#Period').val()

            window.open(
                "{{ url('/disconnection_schedules/view-disconnection-consumers') }}/" + id + "/" + day + "/" + period,
                "_blank"
            )
        }
    </script>
@endpush
