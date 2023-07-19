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
   {{-- SUMMARY --}}
   <div class="col-lg-12">
      <div class="card shadow-none">
         <div class="card-body">
            <div class="row">
               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Amount Collected</p>
                  <h2 class="text-primary text-center">{{ $totalCollection != null && is_numeric($totalCollection->PaidAmount) ? number_format($totalCollection->PaidAmount, 2) : '0' }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Disconnected</p>
                  <h2 class="text-danger text-center">{{ $poll != null && is_numeric($poll->Disconnected) ? number_format($poll->Disconnected) : '0' }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Paid</p>
                  <h2 class="text-success text-center">{{ $poll != null && is_numeric($poll->Paid) ? number_format($poll->Paid) : '0' }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Promised</p>
                  <h2 class="text-warning text-center">{{ $poll != null && is_numeric($poll->Promised) ? number_format($poll->Promised) : '0' }}</h2>
               </div>
            </div>
         </div>
      </div>
   </div>

   {{-- ALL DATA --}}
   <div class="col-lg-12">
      <div class="card shadow-none" style="height: 70vh;">
         <div class="card-header">
            <span class="card-title"><i class="fas fa-list ico-tab"></i>Accomplishment Data</span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-sm">
               <thead>
                  <th>#</th>
                  <th>Account Number</th>
                  <th>Consumer Name</th>
                  <th>Consumer Address</th>
                  <th>Billing Month</th>
                  <th>Account<br>Type</th>
                  <th>Account Status</th>
                  <th>Amount Due</th>
                  <th>Disconnection<br>Assessment</th>
                  <th>Amount Paid</th>
                  <th>Date<br>Acted</th>
            </thead>
            <tbody>
                  @php
                     $i = 1;
                     $icon = "";
                     $bg = "";
                  @endphp
                  @foreach ($data as $item)
                     @if ($item->Status == null)
                        @php
                           $icon = 'fa-exclamation-circle text-danger';
                        @endphp
                     @else
                        @php
                           $icon = 'fa-check-circle text-success';
                        @endphp
                     @endif
                     <tr>
                        <td>{{ $i }}</td>
                        <td><i class="fas {{ $icon }} ico-tab-mini"></i>{{ $item->AccountNumber }}</td>
                        <td>{{ $item->ConsumerName }}</td>
                        <td>{{ $item->ConsumerAddress }}</td>
                        <td>{{ date('M Y', strtotime($item->ServicePeriodEnd)) }}</td>
                        <td>{{ $item->ConsumerType }}</td>
                        <td>{{ $item->AccountStatus }}</td>
                        <td class="text-right text-danger"><strong>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0 }}</strong></td>
                        <td class="text-center"><span class="badge {{ $item->Status=='Promised' ? 'bg-warning' : ($item->Status=='Paid' ? 'bg-success' : 'bg-danger') }}">{{ $item->Status }}</span></td>
                        <td class="text-right text-primary"><strong>{{ is_numeric($item->AmountPaid) ? number_format($item->AmountPaid, 2) : 0 }}</strong></td>
                        <td>{{ $item->DisconnectionDate != null ? date('M d, Y h:i A', strtotime($item->DisconnectionDate)) : '' }}</td>
                     </tr>
                     @php
                        $i++;
                     @endphp
                  @endforeach
            </tbody>
            </table>
         </div>
      </div>
   </div>  
   
   {{-- MAP --}}
   <div class="col-lg-12">
      <div class="card shadow-none">
         <div class="card-header">
            <span class="card-title"><i class="fas fa-map-marker-alt ico-tab"></i>Map View</span>
         </div>
         <div class="card-body p-0">
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
                $('div[id^="disco"]').remove()
            })
        })

        /**
         *  MAP
         **/
        map.on('load', () => {
            loadAccountGPS()
            // loadNeighbors($('#brgy').val(), $('#town').val())
        })

        function loadAccountGPS() {
            $('div[id^="disco"]').remove()
            $.ajax({
                url : "{{ route('disconnectionSchedules.disconnection-map-data') }}",
                type : 'GET',
                data :{
                    ScheduleId : "{{ $disconnectionSchedules->id }}",
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        Toast.fire({
                            icon : 'warning',
                            text : 'Account not found!'
                        })
                    } else {
                        $.each(res, function(index, item) {
                           var loc = res[index]['AccountCoordinates']
                        
                           var coordinates = loc.split(',')

                           var lati = jQuery.isEmptyObject(res[index]['Latitude']) ? 0 : parseFloat(res[index]['Latitude'])
                           var longi = jQuery.isEmptyObject(res[index]['Longitude']) ? 0 : parseFloat(res[index]['Longitude'])

                           if (index == 0) {
                              map.flyTo({
                                 center: [longi, lati],
                                 zoom: 15,
                                 bearing: 0,
                                 speed: 1, // make the flying slow
                                 curve: 1, // change the speed at which it zooms out
                                 easing: (t) => t,
                                 essential: true
                              })
                           }

                           const el = document.createElement('div');
                           el.className = 'marker';
                           el.id = "disco";
                           el.title = res[index]['ConsumerName']
                           el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 2.8em;"></i></span> </button>'
                           el.style.backgroundColor = `transparent`;                       
                           el.style.width = `15px`;
                           el.style.height = `15px`;
                           el.style.borderRadius = '50%';
                           el.style.backgroundSize = '100%';

                           el.addEventListener('click', () => {
                              Swal.fire({
                                    title : res[index]['ConsumerName'],
                                    text : 'Account No: ' + res[index]['AccountNumber'] + ' | Address: ' + res[index]['ConsumerAddress'] + ' | Pole No: ' + res[index]['Pole'],
                              })
                           });

                           new mapboxgl.Marker(el)
                              .setLngLat([parseFloat(longi), parseFloat(lati)])
                              .addTo(map);

                           if (coordinates.length == 2) {
                              var lati = jQuery.isEmptyObject(coordinates[0]) ? 0 : parseFloat(coordinates[0])
                              var longi = jQuery.isEmptyObject(coordinates[1]) ? 0 : parseFloat(coordinates[1])

                              const el = document.createElement('div');
                              el.className = 'marker';
                              el.id = "account";
                              el.title = res[index]['ConsumerName']
                              el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-info" style="font-size: 2.8em;"></i></span> </button>'
                              el.style.backgroundColor = `transparent`;                       
                              el.style.width = `15px`;
                              el.style.height = `15px`;
                              el.style.borderRadius = '50%';
                              el.style.backgroundSize = '100%';

                              new mapboxgl.Marker(el)
                                 .setLngLat([parseFloat(longi), parseFloat(lati)])
                                 .addTo(map);
                           }
                        })

                        
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

      //   function loadNeighbors(acctNo) {
      //       $.ajax({
      //           url : "{{ route('accountMasters.get-neighboring-by-account') }}",
      //           type : 'GET',
      //           data : {
      //               AccountNumber : acctNo,
      //           },
      //           success : function(res) {
      //               if (!jQuery.isEmptyObject(res)) {
      //                   $.each(res, function(index, element) {
      //                       if (!jQuery.isEmptyObject(res[index]['Item1'])) {
      //                           var loc = res[index]['Item1'].split(',')
      //                           if (loc.length == 2) {
      //                               var lat = loc[0]
      //                               var long = loc[1]

      //                               const el = document.createElement('div');
      //                               el.className = 'marker';
      //                               el.id = "neighbors";
      //                               el.title = res[index]['AccountNumber'] + " | " + res[index]['ConsumerName']
      //                               el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-primary" style="font-size: 2.2em;"></i></span> </button>'
      //                               el.style.backgroundColor = `transparent`;                       
      //                               el.style.width = `15px`;
      //                               el.style.height = `15px`;
      //                               el.style.borderRadius = '50%';
      //                               el.style.backgroundSize = '100%';

      //                               el.addEventListener('click', () => {
      //                                   Swal.fire({
      //                                       title : res[index]['ConsumerName'],
      //                                       text : res[index]['AccountNumber'] + " | Sequence: " + res[index]['SequenceNumber'] + " | Pole No: " + res[index]['Pole'],
      //                                   })
      //                               });

      //                               new mapboxgl.Marker(el)
      //                                       .setLngLat([parseFloat(long), parseFloat(lat)])
      //                                       .addTo(map);
      //                           }
      //                       }
      //                   })
      //               }
      //           },
      //           error : function(err) {
      //               Swal.fire({
      //                   icon : 'error',
      //                   text : 'Error getting neighbors'
      //               })
      //           }
      //       })
      //   }
        
    </script>
@endpush