@php
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Tickets Quarterly Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'tickets.quarterly-report', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Town">Town</label>
                        <select id="Town" name="Town" class="form-control form-control-sm">
                            <option value="All" {{ isset($_GET['Town']) && $_GET['Town']=='All' ? 'selected' : '' }}>ALL</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Town']) ? ($_GET['Town']=='All' ? '' : ($_GET['Town']==$item->id ? 'selected' : (env('APP_AREA_CODE')==$item->id ? 'selected' : '') ) ) : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="Quarter">Quarter</label>
                        <select name="Quarter" id="Quarter" class="form-control form-control-sm">
                            <option value="01"  {{ isset($_GET['Quarter']) && $_GET['Quarter']=='01' ? 'selected' : '' }}>1st Quarter</option>
                            <option value="02"  {{ isset($_GET['Quarter']) && $_GET['Quarter']=='02' ? 'selected' : '' }}>2nd Quarter</option>
                            <option value="03"  {{ isset($_GET['Quarter']) && $_GET['Quarter']=='03' ? 'selected' : '' }}>3rd Quarter</option>
                            <option value="04"  {{ isset($_GET['Quarter']) && $_GET['Quarter']=='04' ? 'selected' : '' }}>4th Quarter</option>
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
                <table class="table table-hover table-sm" id="results-table">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Account Number</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Nature of Complaint</th>
                        <th>Date Received</th>
                        <th>Action Desired</th>
                        <th>Action Taken</th>
                        <th>Date Acted</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $item->AccountNumber }}</td>
                                <td>{{ $item->ConsumerName}}</td>
                                <td>{{ Tickets::getAddress($item) }}</td>
                                <td>{{ $item->ParentTicket }} - {{ $item->Ticket }}</td>
                                <td>{{ date('m/d/Y h:i A', strtotime($item->created_at)) }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ date('m/d/Y h:i A', strtotime($item->DatetimeLinemanExecuted)) }}</td>
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
                window.location.href = "{{ url('/tickets/download-quarterly-report') }}" + "/" + $('#Town').val() + "/" + $('#Quarter').val() + "/" + $('#Year').val()
            })
        })

    </script>
@endpush