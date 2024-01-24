@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="map" style="width: 100%; height: 95vh;"></div>
    </div>
</div>
    
@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        $('body').addClass('sidebar-collapse')

        var fleets = []
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

        function getFleets() {
            $.ajax({
                url : "{{ route('tickets.get-fleets') }}",
                type : "GET",
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        $.each(res, function(index, element) {
                            fleets.push(res[index]['CrewId'])
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
            getFleets()
            
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
        })
    </script>
@endpush