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

        function getFletData() {
            $.ajax({
                url : "{{ route('tickets.get-fleet-data') }}",
                type : "GET",
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
                                // remove markers
                                if (markers.length > 0) {
                                    for (x=0; x<markers.length; x++) {
                                        markers[x].remove()
                                    }
                                    console.log('markers removed')
                                }

                                // ADD MARKER ON LAST COORDINATE
                                const el = document.createElement('div');
                                el.className = 'marker';
                                el.id = res[index]['CrewId'];
                                el.title = res[index]['StationName']
                                el.innerHTML += '<i class="fas fa-car" style="font-size: 2.5em; color: #ffffff; margin-left: 8px; margin-top: 8px;"></i>'
                                el.style.backgroundColor = `#0ac1d1`;    
                                el.style.margin = `auto`
                                el.style.cssText = `width: 53px; height: 53px; background-color: #0ac1d1; border-radius: 50%; border: 4px solid #ffffff;`

                                marker = new mapboxgl.Marker(el)
                                        .setLngLat([longi, lat])
                                        .addTo(map);

                                markers.push(marker)
                            }
                        })

                        // remove layer and source
                        if (!jQuery.isEmptyObject(map.getLayer('route'))) {
                            map.removeLayer('route')
                            console.log('layer removed')
                        }

                        if (!jQuery.isEmptyObject(map.getSource('route'))) {
                            map.removeSource('route')
                            console.log('source removed')
                        }
                        console.log('data removed')

                        // add to map
                        map.addSource('route', {
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
                            'id': 'route',
                            'type': 'line',
                            'source': 'route',
                            'layout': {
                                'line-join': 'round',
                                'line-cap': 'round'
                            },
                            'paint': {
                                'line-color': '#eb7d34',
                                'line-width': 10
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

        map.on('load', () => {
            setInterval(() => {
                getFletData()
            }, 5000);
            
        })
    </script>
@endpush