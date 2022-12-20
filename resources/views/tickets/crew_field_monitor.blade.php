@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
    <div class="row">
        {{-- PARAMS --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="margin-top: 3px;">
                <div class="card-body p-1">
                    <div class="row">
                        {{-- TICKET --}}
                        <div class="form-group col-sm-3">
                            <label for="Ticket">Ticket Type</label>
                            <select class="custom-select select2"  name="Ticket" id="Ticket">
                                <option value="All">All</option>
                                @foreach ($parentTickets as $items)
                                    <optgroup label="{{ $items->Name }}">
                                        @php
                                            $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->whereNotIn('Id', Tickets::getMeterRelatedComplainsId())->orderBy('Name')->get();
                                        @endphp
                                        @foreach ($ticketsRep as $item)
                                            <option value="{{ $item->id }}">{{ $item->Name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="form-group col-sm-2">
                            <label for="Status">Ticket Status</label>
                            <select name="Status" id="Status" class="form-control">
                                @foreach ($status as $item)
                                    <option value="{{ $item->Status }}">{{ $item->Status }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CREW --}}
                        <div class="form-group col-sm-2">
                            {!! Form::label('CrewAssigned', 'Crew Assigned:') !!}
                            <select name="CrewAssigned" id="CrewAssigned" class="form-control">
                                <option value="All">All</option>
                                @foreach ($crew as $item)
                                    <option value="{{ $item->id }}">{{ $item->StationName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Actions</label><br>
                            <button class="btn btn-primary" id="filter">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-4">
            <div class="card shadow-none" style="height: 75vh;">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm" id="res-table">
                        <thead>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MAP --}}
        <div class="col-lg-8">
            <div id="map" style="width: 100%; height: 75vh;"></div>
        </div>
    </div>
    
@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [124.048419, 9.776509], // starting position [lng, lat], , 
            zoom: 9 // starting zoom
        });

        var markers = [];

        $(document).ready(function() {
            $('#filter').on('click', function() {
                getTickets()
            })
        })

        function getTickets() {
            $('#res-table tbody tr').remove()
            $.ajax({
                url : "{{ route('tickets.get-crew-field-monitor-data') }}",
                type : 'GET',
                data : {
                    Ticket : $('#Ticket').val(),
                    Status : $('#Status').val(),
                    Crew : $('#CrewAssigned').val()
                },
                success : function(res) {
                    if (markers.length > 0) {
                        for (x=0; x<markers.length; x++) {
                            markers[x].remove()
                        }
                    }
                    
                    $.each(res, function(index, element) {
                        // APPEND TO TABLE
                        $('#res-table tbody').append(addRow(res[index]['id'], res[index]['ConsumerName'], res[index]['Name'], res[index]['StationName'], res[index]['created_at'], res[index]['GeoLocation']))

                        if (!jQuery.isEmptyObject(res[index]['GeoLocation'])) {
                            // CREATE MARKER
                            const el = document.createElement('div');
                            el.className = 'marker';
                            el.id = res[index]['id'];
                            el.title = res[index]['ConsumerName']
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 1.7em;"></i></span> </button>'
                            el.style.backgroundColor = `transparent`;                       
                            el.style.width = `15px`;
                            el.style.height = `15px`;
                            el.style.borderRadius = '50%';
                            el.style.backgroundSize = '100%';

                            el.addEventListener('click', () => {
                                Swal.fire({
                                    title : res[index]['ConsumerName'],
                                    html : '<span>TICKET ID: ' + res[index]['id'] + '</span><br><span>TICKET: ' + res[index]['Name'] + '</span><br><span>' + 'GPS: ' + res[index]['GeoLocation'] + '</span>',
                                })
                            });

                            // GET LAT LONG
                            var coordinates = res[index]['GeoLocation'].split(",")
                            var lat = parseFloat(coordinates[0]) ? parseFloat(coordinates[0]) : 0
                            var long = parseFloat(coordinates[1]) ? parseFloat(coordinates[1]) : 0

                            // PLOT
                            if (!jQuery.isEmptyObject(lat) | parseFloat(lat)) {
                                marker = new mapboxgl.Marker(el)
                                        .setLngLat([long, lat])
                                        .addTo(map);

                                markers.push(marker)
                            }
                        }                        
                    })

                    map.flyTo({
                        center: [124.048419, 9.776509],
                        zoom: 10,
                        bearing: 0,                        
                        speed: 1.8, // make the flying slow
                        curve: 1, // change the speed at which it zooms out                        
                        // easing: (t) => t,                        
                        essential: true
                    })
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting tickets',
                        icon : 'error'
                    })
                }
            })
        }

        function addRow(id, name, ticket, crew, created_at, geo) {
            return "<tr onclick=flyToAccount('" + geo + "')>" +
                        "<td>" +
                            "(<span>"+ id + "</span>) <strong>" + ticket + "</strong><br>" +
                            "<span>" + name + "</span><br>" +
                            "<span>Crew: <strong>" + crew + "</strong></span><br>" +
                        "</td>" +
                    "</tr>";
        }

        function flyToAccount(geo) {
            if (!jQuery.isEmptyObject(geo)) {
                var coordinates = geo.split(",")
                var lat = parseFloat(coordinates[0]) ? parseFloat(coordinates[0]) : 0
                var long = parseFloat(coordinates[1]) ? parseFloat(coordinates[1]) : 0

                if (!jQuery.isEmptyObject(lat) | parseFloat(lat)) {
                    map.flyTo({
                        center: [long, lat],
                        zoom: 12,
                        bearing: 0,                        
                        speed: 1.8, // make the flying slow
                        curve: 1, // change the speed at which it zooms out                        
                        // easing: (t) => t,                        
                        essential: true
                    })
                }
            } else {
                Swal.fire({
                    text : 'No coordinates recorded for this account',
                    icon : 'info'
                })
            }
        }
    </script>
@endpush