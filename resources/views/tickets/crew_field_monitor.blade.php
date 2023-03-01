@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Ticket Crew Monitoring <span><i class="text-muted" style="font-size: .7em; margin-left: 10px;">For the last 45 days</i></span></h4>
            </div>
        </div>
    </div>
</section>
<div class="content">
    <div class="row">
        {{-- RESULTS --}}
        <div class="col-lg-3 col-md-4">
            {{-- FORM --}}
            <div class="card shadow-none">
                <div class="card-body">
                    {{-- CREW --}}
                    <div class="form-group">
                        {!! Form::label('Station', 'Station:') !!}
                        <select name="Station" id="Station" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($stations as $item)
                                <option value="{{ $item->CrewLeader }}">{{ $item->CrewLeader }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Actions</label><br>
                        <button class="btn btn-primary btn-sm" id="filter"><i class="fas fa-filter ico-tab-mini"></i>Filter</button>
                    </div>
                </div>
            </div>

            {{-- CREW --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Crew Group in this Station</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover" id="crew-table">
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MAP --}}
        <div class="col-lg-9 col-md-8">
            <div class="card shadow-none" style="height: 80vh;">
                <div class="card-body">

                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div id="map" style="width: 100%; height: 75vh;"></div>
        </div>
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
                getCrewFromStation($('#Station').val())
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

        function getCrewFromStation(station) {
            $('#crew-table tbody tr').remove()
            $.ajax({
                url : "{{ route('tickets.get-crew-from-station') }}",
                type : 'GET',
                data : {
                    Station : station,
                },
                success : function(res) {
                    $('#crew-table tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        text : 'Error getting crew',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush