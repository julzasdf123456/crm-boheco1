@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Disconnection Crew Accomplishment Monitor</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Schedule Calendar</span>
                </div>
                <div class="card-body">
                    <div id="calendar" style="height: 78vh;"></div>
                </div>
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

            fetchCalendarData(moment().format('MMMM YYYY'))
        })

        function fetchCalendarData(month) {
            scheds = []
            // QUERY SCHEDS
            $.ajax({
                url : '{{ route("disconnectionSchedules.get-schedules-data") }}',
                type : 'GET',
                data : {
                    Month : month
                },
                success : function(res) {
                    console.log(res)
                    $.each(res, function(index, element) {
                        var obj = {}
                        var timestamp = moment(res[index]['Day'], 'YYYY-MM-DD')

                        obj['title'] = res[index]['DisconnectorName']

                        if (res[index]['Status'] == 'Downloaded') {
                           obj['backgroundColor'] = '#fc6203';
                           obj['borderColor'] = '#fc6203';
                        } else {
                           obj['backgroundColor'] = '#66bb6a';
                           obj['borderColor'] = '#66bb6a';
                        }                        

                        obj['extendedProps'] = {
                           ScheduleId : res[index]['id']
                        }
                        
                        obj['start'] = moment(timestamp).format('YYYY-MM-DD');
                        
                        // urlShow = urlShow.replace("rsId", res[index]['id'])
                        // obj['url'] = urlShow

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

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
                        height : 620,
                        // initialDate : moment(month).format("YYYY-MM-DD")
                        eventClick : function(info) {
                            window.location.href = "{{ url('/disconnection_schedules/monitor-view') }}/" + info.event.extendedProps['ScheduleId']
                        }
                    });

                    calendar.render()

                    // $('.fc-prev-button').on('click', function() {
                    //     fetchCalendarData($('#fc-dom-1').text())
                    // })

                    // $('.fc-next-button').on('click', function() {
                    //     fetchCalendarData($('#fc-dom-1').text())
                    // })
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting schedules'
                    })
                }
            })
        }
    </script>
@endpush

