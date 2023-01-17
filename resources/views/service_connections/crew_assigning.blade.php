@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Servic Connection Crew Assigning | ESD</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                {!! Form::open(['route' => 'serviceConnections.crew-assigning', 'method' => 'GET']) !!}
                <div class="card-body">
                    <div class="row">
    
                        <div class="form-group col-md-3">
                            <label for="Office">Office</label>
                            <select name="Office" id="Office" class="form-control form-control-sm">
                                <option value="MAIN OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>    
                                <option value="SUB-OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>                            
                            </select>
                        </div>
    
                        <div class="form-group col-md-3">
                            <label for="Action">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check ico-tab-mini"></i>View</button>
                        </div>
                    </div>
                    
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <table class="table table-hover table-sm">
                <thead>
                    <th>Turn On ID</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Application</th>
                    <th>Datetime Applied</th>
                    <th>Status</th>
                    <th>Office</th>
                    <th>Load (kVA)</th>
                    <th>Select Crew</th>
                    <th width="40px"></th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr id="{{ $item->id }}">
                            <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                            <td><strong>{{ $item->ServiceAccountName }}</strong></td>
                            <td>{{ ServiceConnections::getAddress($item) }}</td>
                            <th>{{ $item->AccountType }}</th>
                            <td>{{ date('M d, Y', strtotime($item->DateOfApplication)) }}</td>
                            <td><span class="badge bg-info">{{ $item->Status }}</span></td>
                            <td>{{ $item->Office }}</td>
                            <td>{{ $item->LoadCategory }}</td>
                            <td>
                                <select id="crew-{{ $item->id }}" class="form-control form-control-sm">
                                    <option value="">-</option>
                                    @foreach ($crew as $crews)
                                        <option value="{{ $crews->id }}">{{ $crews->StationName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button onclick="changeCrew('{{ $item->id }}')" class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function changeCrew(id) {
            var crew = $('#crew-' + id).val()
            if (!jQuery.isEmptyObject(crew)) {
                $.ajax({
                    url : "{{ route('serviceConnections.assign-crew') }}",
                    type : 'GET',
                    data : {
                        id : id,
                        StationCrewAssigned : crew,
                    },
                    success : function(res) {
                        $('#' + id).remove()
                        Toast.fire({
                            icon : 'success',
                            text : 'Crew assigned successfully'
                        })
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'An error occurred while attempting to change crew. Contact support for more!'
                        })
                    }
                })
            } else {
                Toast.fire({
                    icon : 'warning',
                    text : 'No crew selected!'
                })
            }            
        }
    </script>
@endpush