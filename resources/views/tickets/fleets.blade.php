@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
<div id="map" style="width: 100%; height: 99vh; position: fixed; top: 0;"></div>

<div class="row">
    <div class="col-lg-2 col-md-4" style="margin-top: 10px; margin-left: 10px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-cogs ico-tab"></i>Options</span>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <span class="text-muted">Department</span>
                        <select id="Department" class="form-control">
                            <option value="All">ALL</option>
                            <option value="ESD">ESD</option>
                            <option value="SEEAD">SEEAD</option>
                            <option value="OGM">OGM</option>
                            <option value="ISD">ISD</option>
                            <option value="OSD">OSD</option>
                            <option value="PGD">PGD</option>
                        </select>

                        <span class="text-muted">Crew</span>
                        <select id="Crew" class="form-control">
                            <option value="All">ALL</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        $('body').addClass('sidebar-collapse')

        var fleets = []
        var department = 'All'
        var crew = 'All'
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [123.977243, 9.949143], // starting position [lng, lat], , 
            zoom: 12 // starting zoom
        });

        var markers = []

        $(document).ready(function() {
            
        })

        function getFletData(crewId, background, foreground) {
            $.ajax({
                url : "{{ route('tickets.get-fleet-data') }}",
                type : "GET",
                data : {
                    CrewId : crewId,
                },
                success : function(res) {
                    var coordinates = []

                    if (!jQuery.isEmptyObject(res)) {
                        var size = res.length
                        $.each(res, function(index, element) {
                            var coordata = res[index]['Coordinates'].split(',')
                            var lat = parseFloat(coordata[0])
                            var longi = parseFloat(coordata[1])

                            coordinates.push([longi, lat])

                            if (index == 0) {
                                if (markers.length > 0) {
                                    markers[crewId].remove()
                                }
                                // ADD MARKER ON LAST COORDINATE
                                const el = document.createElement('div');
                                el.className = 'marker';
                                el.id = res[index]['CrewId'];
                                el.title = res[index]['StationName']
                                el.innerHTML += '<i class="fas fa-car" style="font-size: 2.5em; color: ' + foreground + '; margin-left: 8px; margin-top: 8px;"></i>'
                                el.style.backgroundColor = background;    
                                el.style.margin = `auto`
                                el.style.cssText = `width: 53px; height: 53px; background-color: ` + background + `; border-radius: 50%; border: 4px solid ` + foreground + `;`

                                marker = new mapboxgl.Marker(el)
                                        .setLngLat([longi, lat])
                                        .addTo(map);

                                markers[crewId] = marker
                            }
                        })

                        // remove layer and source
                        if (!jQuery.isEmptyObject(map.getLayer('route' + crewId))) {
                            map.removeLayer('route' + crewId)
                        }

                        if (!jQuery.isEmptyObject(map.getSource('route' + crewId))) {
                            map.removeSource('route' + crewId)
                        }

                        // add to map
                        map.addSource('route' + crewId, {
                            'type': 'geojson',
                            'data': {
                                'type': 'Feature',
                                'properties': {},
                                'geometry': {
                                    'type': 'LineString',
                                    'coordinates': coordinates,
                                }
                            }
                        })

                        map.addLayer({
                            'id': 'route' + crewId,
                            'type': 'line',
                            'source': 'route' + crewId,
                            'layout': {
                                'line-join': 'round',
                                'line-cap': 'round'
                            },
                            'paint': {
                                'line-color': background,
                                'line-width': 8
                            }
                        })
                    }
                    
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting data'
                    })
                }
            })
        }

        function getFleets(department, crewId, reloadCrews) {
            $.ajax({
                url : "{{ route('tickets.get-fleets') }}",
                type : "GET",
                data : {
                    Department : department,
                    CrewId : crewId,
                },
                success : function(res) {
                    clearDisplays()

                    fleets = []

                    if (reloadCrews) {                            
                        $('#Crew option').remove()
                        $('#Crew').append("<option value='All'>ALL</option>")
                    }
                    
                    if (!jQuery.isEmptyObject(res)) {

                        $.each(res, function(index, element) {
                            fleets.push(res[index]['CrewId'])   
                            
                            if (reloadCrews) {
                                $('#Crew').append("<option value='" + res[index]['CrewId'] + "'>" + res[index]['StationName'] + "</option>")
                            }
                        })
                    }
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting fleets'
                    })
                }
            })
        }

        map.on('load', () => {
            getFleets(department, crew, true)
            
            var cols = [
                '#00edc6', 
                '#ed0096',
                '#5115ab',
                '#bab211',
                '#1680a1',
                '#a11632',
                '#de5e02',
                '#07b81f',
                '#9c1f59',
                '#1f789c',
                '#578720',
                '#872c20',
                '#00edc6', 
                '#ed0096',
                '#5115ab',
                '#bab211',
                '#1680a1',
                '#a11632',
                '#de5e02',
                '#07b81f',
                '#9c1f59',
                '#1f789c',
                '#578720',
                '#872c20',
                '#00edc6', 
                '#ed0096',
                '#5115ab',
                '#bab211',
                '#1680a1',
                '#a11632',
                '#de5e02',
                '#07b81f',
                '#9c1f59',
                '#1f789c',
                '#578720',
                '#872c20',
                '#00edc6', 
                '#ed0096',
                '#5115ab',
                '#bab211',
                '#1680a1',
                '#a11632',
                '#de5e02',
                '#07b81f',
                '#9c1f59',
                '#1f789c',
                '#578720',
                '#872c20',
                '#00edc6', 
                '#ed0096',
                '#5115ab',
                '#bab211',
                '#1680a1',
                '#a11632',
                '#de5e02',
                '#07b81f',
                '#9c1f59',
                '#1f789c',
                '#578720',
                '#872c20',
            ]

            setInterval(() => {
                for(let i=0; i<fleets.length; i++) {
                    getFletData(fleets[i], cols[i], '#fff')
                }
            }, 5000);  
            
            $('#Department').on('change', function() {
                getFleets(this.value, 'All', true)
            })

            $('#Crew').on('change', function() {
                getFleets($('#Department').val(), this.value, false)
            })
        })

        function clearDisplays() {
            for(let i=0; i<fleets.length; i++) {
                markers[fleets[i]].remove()
                // remove layer and source
                if (!jQuery.isEmptyObject(map.getLayer('route' + fleets[i]))) {
                    map.removeLayer('route' + fleets[i])
                }

                if (!jQuery.isEmptyObject(map.getSource('route' + fleets[i]))) {
                    map.removeSource('route' + fleets[i])
                }
            }

            $('.marker').remove()
        }

    </script>
@endpush