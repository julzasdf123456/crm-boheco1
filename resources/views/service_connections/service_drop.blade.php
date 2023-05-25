@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Energization Report | Service Drop Monitoring</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body">
                <form action="{{ route('serviceConnections.service-drop') }}" method="GET">
                    <div class="row">
                        {{-- FROM --}}
                        <div class="form-group col-lg-2">
                            <label for="From">From</label>
                            {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'From']) !!}
                        </div>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#From').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush

                        {{-- TO --}}
                        <div class="form-group col-lg-2">
                            <label for="To">To</label>
                            {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'To']) !!}
                        </div>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#To').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush

                        {{-- BUTTONS --}}
                        <div class="form-group col-lg-3">
                            <label style="opacity: 0; width: 100%;">Action</label>
                            <button class="btn btn-primary btn-sm" type="submit" title="Filter"><i class="fas fa-check"></i> Filter</button>
                            {{-- <button id="download" class="btn btn-sm btn-success"><i class="fas fa-download ico-tab-mini"></i>Download</button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

    {{-- TABLE --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed text-nowrap table-sm table-bordered" id="results-table-request">
                    <thead> 
                        <th style="width: 30px;"></th>
                        <th class="text-center">Turn On ID</th>
                        <th class="text-center">Consumer Name</th>
                        <th class="text-center">Barangay</th>
                        <th class="text-center">Town</th>
                        <th class="text-center">Application Type</th>
                        <th class="text-center">Date Energized</th>
                        <th class="text-center">Service Drop Len.(mtrs)<br>(Based From Inspection)</th>
                        <th class="text-center">Crew Assigned</th>
                        <th class="text-center">Verifier</th>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->Barangay }}</td>
                                <td>{{ $item->Town }}</td>
                                <td>{{ $item->ConnectionApplicationType }}</td>
                                <td>{{ $item->DateTimeOfEnergization != null ? date('M d, Y h:m:s A', strtotime($item->DateTimeOfEnergization)) : '' }}</td>
                                <td class="text-right text-danger"><strong>{{ $item->SDWLengthAsInstalled }}</strong></td>
                                <td>{{ $item->StationName }}</td>
                                <td>{{ $item->name }}</td>
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
</div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/service_connections/download-meter-installation') }}" + "/" + $('#From').val() + "/" + $('#To').val() + "/" + $('#Office').val() 
            })
        })
    </script>
@endpush