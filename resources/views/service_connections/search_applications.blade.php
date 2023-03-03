@php
    use App\Models\ServiceConnections;
    use Illuminate\Support\Facades\Auth;
@endphp
    
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            {!! Form::open(['route' => 'serviceConnections.index', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-6 offset-md-3">
                        <input type="text" class="form-control" placeholder="Search Account # or Account Name" name="params" value="{{ isset($_GET['params']) ? $_GET['params'] : '' }}" autofocus>
                    </div>
                    <div class="col-md-3">
                        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </section>

    <div class="content px-3">
        <table class="table table-hover">
            <thead>
                <th>Turn On ID</th>
                <th>Date Applied</th>
                <th>Service Account Name</th>
                <th>Application</th>
                <th>Status</th>
                <th>Meter No.</th>
                <th>Office</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>
                            <strong><a href="{{ route('serviceConnections.show', [$item->ConsumerId]) }}">{{ $item->ConsumerId }}</a></strong>
                        </td>
                        <td>{{ $item->DateOfApplication != null ? date('M d, Y', strtotime($item->DateOfApplication)) : '-' }}</td>
                        <td>
                            <img src="{{ URL::asset('imgs/prof-icon.png'); }}" style="width: 30px; margin-right: 15px;" class="img-circle" alt="profile"><strong>{{ $item->ServiceAccountName }}</strong>
                        </td>    
                        <td>{{ $item->ConnectionApplicationType }}</td>
                        <td>
                            <span class="badge">{{ $item->Status }}</span>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar progress-bar-striped {{ ServiceConnections::getBgStatus($item->Status) }}" role="progressbar" style="width: {{ ServiceConnections::getProgressStatus($item->Status) }}%" aria-valuenow="{{ ServiceConnections::getProgressStatus($item->Status) }}" aria-valuemin="0" aria-valuemax="10"></div>
                            </div>
                        </td>       
                        <td>{{ $item->MeterSerialNumber }}</td>           
                        <td><span class="badge {{ ServiceConnections::getOfficeBg($item->Office) }}">{{ $item->Office }}</span></td>     
                        <td>
                            <a href="{{ route('serviceConnections.show', [$item->ConsumerId]) }}" class="float-right" style="margin-left: 10px;"><i class="fas fa-eye"></i></a>    
                            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor']))
                                @if ($item->ORNumber == null)
                                    <button class="btn btn-success btn-xs float-right" onclick="updateOR('{{ $item->ConsumerId }}')"><i class="fas fa-dollar-sign ico-tab-mini"></i>Update OR</button>
                                @else
                                    <span class="badge bg-warning float-right">{{ $item->ORNumber }}</span>
                                @endif                                
                            @endif
                        </td>                  
                    </tr>                    
                @endforeach
            </tbody>
        </table>

        {{ $data->links() }}
    </div>
@endsection

@include('service_connections.modal_update_or')

@push('page_scripts')
    <script>
        var scid = ''
        $(document).ready(function() {
            $('#save-or').on('click', function() {
                var or = $('#ornumber').val()
                var ordate = $('#orDate').val()
                if (jQuery.isEmptyObject(or) | jQuery.isEmptyObject(ordate)) {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Please input OR Number and OR Date'
                    })
                } else {
                    $.ajax({
                        url : "{{ route('serviceConnections.update-or') }}",
                        type : 'GET',
                        data : {
                            id : scid,
                            ORNumber : or,
                            ORDate : ordate
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'OR Updated!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error updating OR'
                            })
                        }
                    })
                }                
            })
        })

        function updateOR(id) {
            scid = id
            $('#modal-update-or').modal('show')
        }

    </script>
@endpush