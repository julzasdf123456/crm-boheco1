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
            <div class="col-sm-8">
                <p><strong>Inspectors and Verifiers Monitoring</strong></p>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-5">
                        <label for="" class="float-right">Select Month</label>
                    </div>

                    <div class="col-sm-7">
                        <div class="form-group">
                            <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- INDEX --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <p>Inspection Summary</p>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover table-bordered" id="table-summary">
                    <thead>
                        <th class="text-center">Inspector</th>
                        <th class="text-center">Inspections<br>Filed Today</th>
                        <th class="text-center">For Inspection</th>
                        <th class="text-center">Approved</th>
                        <th class="text-center">Total Inspections</th>
                        <th class="text-center">No. of Days</th>
                        <th class="text-center">Average Daily</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>        
    </div>

    {{-- CALENDAR --}}

    <div class="col-lg-8">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Calendar View</span>
                <div class="card-tools">
                    <select name="Inspector" id="Inspector" class="form-control form-control-sm">
                        @foreach ($inspectors as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var scheds = [];
        
        var Calendar
        var calendarEl
        var calendar

        $(document).ready(function() {
            Calendar = FullCalendar.Calendar
            calendarEl = document.getElementById('calendar')
            getSummary()
            fetchCalendarData()

            $('#ServicePeriod').on('change', function() {
                getSummary()
            })

            $('#Inspector').on('change', function() {
                fetchCalendarData()
            })
        })

        function getSummary() {
            $.ajax({
                url : "{{ route('serviceConnections.get-inspection-summary-data') }}",
                type : 'GET',
                data : {
                    ServicePeriod : $('#ServicePeriod').val()
                },
                success : function(res) {
                    $('#table-summary tbody tr').remove()
                    $('#table-summary tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error fetching inspection summary'
                    })
                }
            })
        }

        function fetchCalendarData() {
            scheds = []
            // QUERY SCHEDS
            $.ajax({
                url : '{{ route("serviceConnections.get-inspection-summary-data-calendar") }}',
                type : 'GET',
                data : {
                    ServicePeriod : $('#ServicePeriod').val(),
                    Inspector : $('#Inspector').val()
                },
                success : function(res) {
                    
                    var hrFormat = "YYYY-MM-DD HH:mm:ss"
                    

                    $.each(res, function(index, element) {
                        var obj = {}
                        var timestamp = moment(res[index]['DateOfVerification'], 'YYYY-MM-DD')

                        obj['title'] = res[index]['Count']
                        obj['backgroundColor'] = '#66bb6a';
                        obj['borderColor'] = '#66bb6a';
                        
                        obj['start'] = moment(timestamp).format('YYYY-MM-DD');
                        
                        // urlShow = urlShow.replace("rsId", res[index]['id'])
                        // obj['url'] = urlShow

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    //         /* initialize the calendar
                    // -----------------------------------------------------------------*/
                    //Date for the calendar events (dummy data)
                    var date = new Date()
                    var d    = date.getDate(),
                        m    = date.getMonth(),
                        y    = date.getFullYear()

                    if (calendar != null) {
                        calendar.removeAllEvents()
                    }
                
                    calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left  : 'prev,next today',
                            center: 'title',
                            right : 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        themeSystem: 'bootstrap',
                        events : scheds,
                        eventOrderStrict : true,
                        editable  : true,
                    });

                    calendar.render();
                },
                error : function(err) {
                    alert('An error occurred while trying to query the schedules')
                }
            })
        }
    </script>
@endpush