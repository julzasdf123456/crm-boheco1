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
                <span>
                    <h4 style="display: inline; margin-right: 15px;"><strong class="text-primary">Step 1.</strong> Account Migration Wizzard</h4>
                    <i class="text-muted">Validate Consumer Information</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row px-2">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'accountMasters.store']) !!}
            <div class="card-body">
                @include('adminlte-templates::common.errors')
                @include('flash::message')
                <div class="row">                    
                    {{-- HIDDEN FIELDS --}}
                    <input type="hidden" value="{{ $serviceConnection->id }}" name="ServiceConnectionId">
                    <input type="hidden" value="{{ $serviceConnection->id }}" name="UniqueID">
                    <input type="hidden" value="{{ $serviceConnection->ContactNumber }}" name="ContactNumber">
                    <input type="hidden" value="{{ $serviceConnection->EmailAddress }}" name="Email">
                    <input type="hidden" value="{{ Auth::user()->name }}" name="UserName">
                    <input type="hidden" value="{{ $inspection->GeoMeteringPole }}" name="Item1">
                    <input type="hidden" value="{{ $serviceConnection != null ? $serviceConnection->DateTimeOfEnergization : null }}" name="ConnectionDate">
                    <input type="hidden" value="{{ date('Y-m-d H:i:s') }}" name="DateEntry">
                    <input type="hidden" value="{{ $crew != null ? substr($crew->StationName, 0, 29) : null }}" name="InstalledBy">
                    <input type="hidden" value="READ" name="BillingStatus">
                    <input type="hidden" value="{{ AccountMaster::getInstallationType($serviceConnection->BuildingType) }}" name="InstallationType">
                    <input type="hidden" value="M" name="GroupTag">
                    <input type="hidden" value="{{ $barangay != null ? $barangay->Barangay : '' }}" name="Barangay">
                    <input type="hidden" value="{{ $town != null ? $town->Town : '' }}" name="Municipal">

                    {{-- <input type="hidden" name="Latitude" value="{{ ServiceAccounts::getLatitude($inspection->GeoMeteringPole) }}"/>
                    <input type="hidden" name="Longitude" value="{{ ServiceAccounts::getLongitude($inspection->GeoMeteringPole) }}"/> --}}

                    {{-- FIELDS --}}
                    <!-- Account Number Field -->
                    <div class="form-group col-lg-4 col-md-5 col-sm-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-4">
                                {!! Form::label('AccountNumber', 'Account Number:') !!}
                            </div>

                            <div class="col-lg-6 col-md-5">
                                <div class="input-group">
                                    {!! Form::text('AccountNumber', null, ['class' => 'form-control form-control-sm','maxlength' => 10, 'required' => true, 'autofocus' => true]) !!}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <button class="btn btn-sm btn-info" id="check-acct-availability" disabled>Check Available</button>
                            </div>
                        </div> 
                    </div>

                    <!-- Sequencecode Field -->
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                {!! Form::label('SequenceNumber', 'Seq. No:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('SequenceNumber', null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50, 'required' => true]) !!}
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <button class="btn btn-sm btn-info" id="check-sequence-availability">Check Available</button>
                            </div>
                        </div> 
                    </div>

                    <!-- Route Field -->
                    <div class="form-group col-lg-2 col-md-3 col-sm-12">
                        <div class="row">
                            <!-- Route Field -->
                            <div class="col-lg-5 col-md-6">
                                {!! Form::label('Route', 'Route:') !!}
                            </div>

                            <div class="col-lg-7 col-md-6">
                                <div class="input-group">
                                    {!! Form::text('Route', null, ['class' => 'form-control form-control-sm','maxlength' => 5, 'required' => true]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <!-- Area Field -->
                    <div class="form-group col-lg-2 col-md-3 col-sm-12">
                        <div class="row">
                            <!-- Area Field -->
                            <div class="col-lg-5 col-md-6">
                                {!! Form::label('Area', 'Area:') !!}
                            </div>

                            <div class="col-lg-7 col-md-6">
                                <div class="input-group">
                                    {!! Form::text('Area', $town != null ? $town->Station : $serviceConnection->Town, ['class' => 'form-control form-control-sm','maxlength' => 5, 'required' => true]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Serviceaccountname Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('ConsumerName', 'Account Name:') !!}
                            </div>

                            <div class="col-lg-5 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('ConsumerName', $serviceConnection!=null ? $serviceConnection->ServiceAccountName : '', ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600, 'required' => true]) !!}
                                </div>
                            </div>   
                            
                            <!-- ConsumerAddress Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('ConsumerAddress', 'Address:') !!}
                            </div>

                            <div class="col-lg-5 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('ConsumerAddress', ($barangay != null ? $barangay->Barangay : '-') . ', ' . ($town != null ? $town->Town : '-'), ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- STATUS --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Accountstatus Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountStatus', 'Status:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('AccountStatus', ['ACTIVE' => 'ACTIVE', 'DISCO' => 'DISCONNECTED'], null, ['class' => 'form-control form-control-sm']) !!}
                                </div>
                            </div>

                            <!-- Acct Type Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('ConsumerType', 'Acct. Type:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    <select class="form-control form-control-sm" name="ConsumerType" id="AccountType">
                                        @foreach ($accountTypes as $item)
                                            <option value="{{ AccountMaster::getTypeByAlias($item->Alias) }}" f-name="{{ AccountMaster::getTypeByAlias($item->Alias) }}" {{ $item->id==$serviceConnection->AccountType ? 'selected' : '' }}>{{ $item->AccountType }} ({{ $item->Alias }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1" style="margin-top: 5px;">
                                <i id="AccountTypeFull"></i>
                            </div>

                            <!-- Acct Type Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('ComputeMode', 'Compute Mode:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    <select class="form-control form-control-sm" name="ComputeMode" id="AccountType">
                                        <option value="Metered">Metered</option>
                                        <option value="FlatConsumption">Flat Consumption</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- NEIGHBOR --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Neighbor1 Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Neighbor1', 'Neighbor 1:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('Neighbor1', $inspection->FirstNeighborName, ['class' => 'form-control form-control-sm', 'readonly' => true]) !!}
                                </div>
                            </div>

                            <!-- Neighbor1Meter Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Neighbor1Meter', 'Nbr. 1 Meter:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('Neighbor1Meter', $inspection->FirstNeighborMeterSerial, ['class' => 'form-control form-control-sm', 'readonly' => true]) !!}
                                </div>
                            </div>

                            <!-- Neighbor2 Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Neighbor2', 'Neighbor 1:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('Neighbor2', $inspection->SecondNeighborName, ['class' => 'form-control form-control-sm', 'readonly' => true]) !!}
                                </div>
                            </div>

                            <!-- Neighbor2Meter Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Neighbor2Meter', 'Nbr. 2 Meter:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('Neighbor2Meter', $inspection->SecondNeighborMeterSerial, ['class' => 'form-control form-control-sm', 'readonly' => true]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Senior Citizen Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('SeniorCitizen', 'Senior Citizen:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('SeniorCitizen', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>

                            <!-- Contestable Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Contestable', 'Contestable:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('Contestable', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>

                            <!-- Net Metered Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('NetMetered', 'Net Metered:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('NetMetered', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>
                        </div> 
                    </div> --}}

                    @push('page_scripts')
                        <script>
                            $(document).ready(function() {
                                $('#AccountTypeFull').text($('#AccountType option:selected').attr('f-name'))

                                $('#AccountType').on('change', function() {
                                    $('#AccountTypeFull').text($('#AccountType option:selected').attr('f-name'))
                                })
                            })
                        </script>
                    @endpush
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary btn-sm float-right']) !!}
                <a href="{{ route('serviceConnections.show', [$serviceConnection->id]) }}" class="btn btn-success btn-sm" target="_blank">View Application Info</a>
                
                <button id="bapa-member" class="btn btn-default btn-sm" style="margin-left: 20px;">BAPA Member</button>
                <button id="eca-member" class="btn btn-default btn-sm">ECA Member</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

    {{-- MAP --}}
    <div class="col-lg-12 col-md-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span>
                    <strong>PARAMETERS:  </strong> 
                    Barangay
                    <input type="text" class="form-control form-control-sm" style="width: 180px; display: inline;" id="brgy" value="{{ $barangay != null ? $barangay->Barangay : '' }}">
                    Town
                    <input type="text" class="form-control form-control-sm" style="width: 180px; display: inline;" id="town" value="{{ $town != null ? $town->Town : '' }}">
                    <button class="btn btn-primary btn-sm" id="filter">Re-Filter</button>
                    <button class="btn btn-default btn-sm" id="clear">Clear Neighbors</button>
                </span>
            </div>

            <div class="card-body">
                <div id="map" style="width: 100%; height: 70vh;"></div>
            </div>
        </div>        
    </div>
</div>

@include('account_masters.modal_check_available_account')

@include('account_masters.modal_check_available_sequence')

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
            $('#AccountNumber').on('keyup', function() {
                var len = this.value.length
                if (len > 5) {
                    $('#check-acct-availability').removeAttr('disabled')
                    $('#Route').val(this.value.substring(2,6))
                } else {
                    $('#check-acct-availability').attr('disabled', 'true')
                }
            })

            $('#check-sequence-availability').on('click', function(e) {
                e.preventDefault()
                if ($('#AccountNumber').val().length == 10) {
                    $('#modal-check-available-sequence').modal('show')
                    $('#route-check').val('').val($('#Route').val())  
                } else {
                    Swal.fire({
                        icon : 'info',
                        text : 'Account number invalid'
                    })
                }
            })

            $('#check-acct-availability').on('click', function(e) {
                e.preventDefault()
                $('#modal-check-available-acctno').modal('show')
                $('#check-acct-no-route').val('').val($('#AccountNumber').val())                
            })

            $('#AccountNumber').on('change', function() {
                var len = this.value.length
                if (len > 9) {
                    $('#Route').val(this.value.substring(2,6))
                }
            })

            $('#filter').on('click', function() {
                loadNeighbors($('#brgy').val(), $('#town').val())
            })
            
            $('#clear').on('click', function() {
                $('div[id^="neighbors"]').remove()
            })

            $('#bapa-member').on('click', function(e) {
                e.preventDefault()

                Swal.fire({
                    title : 'Move to BAPA',
                    text: 'Are you sure you want to move this to the BAPA section?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.convert-to-bapa') }}",
                            type : 'GET',
                            data : {
                                id : "{{ $serviceConnection->id }}",
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Application moved to BAPA'
                                })
                                location.href = "{{ route('serviceAccounts.pending-accounts') }}"
                            }
                        })
                    } else if (result.isDenied) {
                        
                    }
                })
            })

            $('#eca-member').on('click', function(e) {
                e.preventDefault()

                Swal.fire({
                    title : 'Move to ECA',
                    text: 'Are you sure you want to move this to the ECA section?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.convert-to-eca') }}",
                            type : 'GET',
                            data : {
                                id : "{{ $serviceConnection->id }}",
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Application moved to ECA'
                                })
                                location.href = "{{ route('serviceAccounts.pending-accounts') }}"
                            }
                        })
                    } else if (result.isDenied) {
                        
                    }
                })
            })
        })

        /**
         *  MAP
         **/
        map.on('load', () => {
            loadServiceConnectionGPS()
            loadNeighbors($('#brgy').val(), $('#town').val())
        })

        function loadServiceConnectionGPS() {
            var meterLoc = "{{ $inspection->GeoMeteringPole }}"
            if (!jQuery.isEmptyObject(meterLoc)) {
                var meterSplit = meterLoc.split(',')
                if (meterSplit.length == 2) {
                    var meterLat = meterSplit[0].trim()
                    var meterLong = meterSplit[1].trim()

                    map.flyTo({
                        center: [parseFloat(meterLong), parseFloat(meterLat)],
                        zoom: 15,
                        bearing: 0,
                        speed: 1, // make the flying slow
                        curve: 1, // change the speed at which it zooms out
                        easing: (t) => t,
                        essential: true
                    });  

                    const el = document.createElement('div');
                    el.className = 'marker';
                    el.id = "{{ $serviceConnection->id }}";
                    el.title = "{{ $serviceConnection->ServiceAccountName }}"
                    el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 2.4em;"></i></span> </button>'
                    el.style.backgroundColor = `transparent`;                       
                    el.style.width = `15px`;
                    el.style.height = `15px`;
                    el.style.borderRadius = '50%';
                    el.style.backgroundSize = '100%';

                    el.addEventListener('click', () => {
                        Swal.fire({
                            title : "{{ $serviceConnection->ServiceAccountName }}",
                            text : 'Metering Pole Coordinates: ' + meterLat + ',' + meterLong,
                        })
                    });

                    new mapboxgl.Marker(el)
                                        .setLngLat([parseFloat(meterLong), parseFloat(meterLat)])
                                        .addTo(map);
                }                
            }
        }

        function loadNeighbors(barangay, town) {
            $.ajax({
                url : "{{ route('accountMasters.get-neighboring-by-barangay') }}",
                type : 'GET',
                data : {
                    Town : town,
                    Barangay : barangay,
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
                                            text : res[index]['AccountNumber'] + " | Sequence: " + res[index]['SequenceNumber'],
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