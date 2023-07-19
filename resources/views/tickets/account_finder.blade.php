@php
    use App\Models\ServiceAccounts;
    use App\Models\AccountMaster;
use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Account Finder</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- MAP --}}
    <div class="col-lg-12 col-md-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span>
                    Input Account Number:
                    <input type="text" class="form-control form-control-sm" style="width: 180px; display: inline; margin-left: 5px;" id="account" >
                    <button class="btn btn-primary btn-sm" id="filter">Find in Map</button>
                    <button class="btn btn-default btn-sm" id="clear">Clear Map</button>
                </span>
            </div>

            <div class="card-body">
                <div id="map" style="width: 100%; height: 70vh;"></div>
            </div>
        </div>        
    </div>
</div>

@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        /**
         *  MAPPING
         **/
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [124.016388, 9.764970], // starting position [lng, lat], , 
            zoom: 10 // starting zoom
        });

        $(document).ready(function() {    
            $('#filter').on('click', function() {
                loadAccountGPS($('#account').val())
            })
            
            $('#clear').on('click', function() {
                $('div[id^="neighbors"]').remove()
            })
        })

        /**
         *  MAP
         **/
        map.on('load', () => {
            // loadServiceConnectionGPS()
            // loadNeighbors($('#brgy').val(), $('#town').val())
        })

        function loadAccountGPS(acctNo) {
            $('div[id^="neighbors"]').remove()
            $.ajax({
                url : "{{ route('tickets.load-account-in-map') }}",
                type : 'GET',
                data :{
                    AccountNumber : acctNo,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        Toast.fire({
                            icon : 'warning',
                            text : 'Account not foudn!'
                        })
                    } else {
                        var loc = res['Item1']
                        if (jQuery.isEmptyObject(loc)) {
                            Swal.fire({
                                icon : 'info',
                                text : "This account has no GPS, but the nearby GPS coordinates are loaded (based on the account's barangay)"
                            })
                        } else {
                            var coordinates = loc.split(',')
                            if (coordinates.length == 2) {
                                var lati = coordinates[0].trim()
                                var longi = coordinates[1].trim()

                                map.flyTo({
                                    center: [parseFloat(longi), parseFloat(lati)],
                                    zoom: 15,
                                    bearing: 0,
                                    speed: 1, // make the flying slow
                                    curve: 1, // change the speed at which it zooms out
                                    easing: (t) => t,
                                    essential: true
                                });

                                const el = document.createElement('div');
                                el.className = 'marker';
                                el.id = "neighbors";
                                el.title = res['ConsumerName']
                                el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 3.4em;"></i></span> </button>'
                                el.style.backgroundColor = `transparent`;                       
                                el.style.width = `15px`;
                                el.style.height = `15px`;
                                el.style.borderRadius = '50%';
                                el.style.backgroundSize = '100%';

                                el.addEventListener('click', () => {
                                    Swal.fire({
                                        title : res['ConsumerName'],
                                        text : 'Account No: ' + res['AccountNumber'] + ' Pole No: ' + res['Pole'],
                                    })
                                });

                                new mapboxgl.Marker(el)
                                    .setLngLat([parseFloat(longi), parseFloat(lati)])
                                    .addTo(map);

                                loadNeighbors(acctNo)
                            } else {
                                Swal.fire({
                                    icon : 'info',
                                    text : "This account has no GPS, but the nearby GPS coordinates are loaded (based on the account's barangay)"
                                })
                            }                            
                        }
                    }
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error getting account!'
                    })
                }
            })
        }

        function loadNeighbors(acctNo) {
            $.ajax({
                url : "{{ route('accountMasters.get-neighboring-by-account') }}",
                type : 'GET',
                data : {
                    AccountNumber : acctNo,
                },
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        $.each(res, function(index, element) {
                            if (!jQuery.isEmptyObject(res[index]['Item1'])) {
                                var loc = res[index]['Item1'].split(',')
                                if (loc.length == 2) {
                                    var lat = loc[0]
                                    var long = loc[1]

                                    const el = document.createElement('div');
                                    el.className = 'marker';
                                    el.id = "neighbors";
                                    el.title = res[index]['AccountNumber'] + " | " + res[index]['ConsumerName']
                                    el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-primary" style="font-size: 2.2em;"></i></span> </button>'
                                    el.style.backgroundColor = `transparent`;                       
                                    el.style.width = `15px`;
                                    el.style.height = `15px`;
                                    el.style.borderRadius = '50%';
                                    el.style.backgroundSize = '100%';

                                    el.addEventListener('click', () => {
                                        Swal.fire({
                                            title : res[index]['ConsumerName'],
                                            text : res[index]['AccountNumber'] + " | Sequence: " + res[index]['SequenceNumber'] + " | Pole No: " + res[index]['Pole'],
                                        })
                                    });

                                    new mapboxgl.Marker(el)
                                            .setLngLat([parseFloat(long), parseFloat(lat)])
                                            .addTo(map);
                                }
                            }
                        })
                    }
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error getting neighbors'
                    })
                }
            })
        }
        
    </script>
@endpush