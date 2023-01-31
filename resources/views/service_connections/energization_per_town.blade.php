@php
    
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Energization Report per Town/Area</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'serviceConnections.energization-per-town', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Month">Month</label>
                        <select name="Month" id="Month" class="form-control form-control-sm">
                            <option value="01"  {{ isset($_GET['Month']) && $_GET['Month']=='01' ? 'selected' : '' }}>JANUARY</option>
                            <option value="02"  {{ isset($_GET['Month']) && $_GET['Month']=='02' ? 'selected' : '' }}>FEBRUARY</option>
                            <option value="03"  {{ isset($_GET['Month']) && $_GET['Month']=='03' ? 'selected' : '' }}>MARCH</option>
                            <option value="04"  {{ isset($_GET['Month']) && $_GET['Month']=='04' ? 'selected' : '' }}>APRIL</option>
                            <option value="05"  {{ isset($_GET['Month']) && $_GET['Month']=='05' ? 'selected' : '' }}>MAY</option>
                            <option value="06"  {{ isset($_GET['Month']) && $_GET['Month']=='06' ? 'selected' : '' }}>JUNE</option>
                            <option value="07"  {{ isset($_GET['Month']) && $_GET['Month']=='07' ? 'selected' : '' }}>JULY</option>
                            <option value="08"  {{ isset($_GET['Month']) && $_GET['Month']=='08' ? 'selected' : '' }}>AUGUST</option>
                            <option value="09"  {{ isset($_GET['Month']) && $_GET['Month']=='09' ? 'selected' : '' }}>SEPTEMBER</option>
                            <option value="10"  {{ isset($_GET['Month']) && $_GET['Month']=='10' ? 'selected' : '' }}>OCTOBER</option>
                            <option value="11"  {{ isset($_GET['Month']) && $_GET['Month']=='11' ? 'selected' : '' }}>NOVEMBER</option>
                            <option value="12"  {{ isset($_GET['Month']) && $_GET['Month']=='12' ? 'selected' : '' }}>DECEMBER</option>
                        </select>
                    </div>

                    <div class="form-group col-md-1">
                        <label for="Year">Year</label>
                        <input type="text" maxlength="4" id="Year" name="Year" placeholder="Year" value="{{ isset($_GET['Year']) ? $_GET['Year'] : date('Y') }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check ico-tab-mini"></i>View</button>
                        <button id="download" class="btn btn-sm btn-success"><i class="fas fa-download ico-tab-mini"></i>Download</button>
                    </div>
                </div>
                
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm table-bordered" id="results-table">
                    <thead>
                        <th>Town</th>
                        <th class="text-right">Consumer Count</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->Town }}</td>
                                <td class="text-right"><strong>{{ $item->ConsumerCount }}</strong></td>
                            </tr>
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
                window.location.href = "{{ url('/service_connections/download-energization-per-town') }}" + "/" + $('#Month').val() + "/" + $('#Year').val() 
            })
        })
    </script>
@endpush