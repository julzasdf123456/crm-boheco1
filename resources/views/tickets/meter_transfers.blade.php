@php
    use App\Models\MemberConsumers;
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Meter Transfer Installation Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'tickets.meter-transfers', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="From">From</label>
                        {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'From', ]) !!}
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

                    <div class="form-group col-lg-2">
                        <label for="To">To</label>
                        {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'To', ]) !!}
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

                    <div class="form-group col-md-3">
                        <label for="Office">Office</label>
                        <select name="Office" id="Office" class="form-control form-control-sm">
                            <option value="All"  {{ isset($_GET['Office']) && $_GET['Office']=='All' ? 'selected' : '' }}>ALL</option>  
                            <option value="MAIN OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>    
                            <option value="SUB-OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>                            
                        </select>
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
        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm table-bordered" id="results-table">
                    <thead>
                        <th style="width: 30px;"></th>
                        <th>Executed At</th>
                        <th>Ticket ID</th>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Old Meter Brand</th>
                        <th>Old Meter No</th>
                        <th>Old Meter Reading</th>
                        <th>New Meter Brand</th>
                        <th>New Meter No</th>
                        <th>New Meter Reading</th>
                        <th>Crew Assigned</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $item->DateTimeLinemanExecuted != null ? date('M d, Y', strtotime($item->DateTimeLinemanExecuted)) : '' }}</td>
                                <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->AccountNumber }}</td>
                                <td><strong>{{ $item->ConsumerName }}</strong></td>
                                <td>{{ Tickets::getAddress($item) }} </td>
                                <td>{{ $item->CurrentMeterBrand }}</td>
                                <td>{{ $item->CurrentMeterNo }}</td>
                                <td>{{ $item->CurrentMeterReading }}</td>
                                <td>{{ $item->NewMeterBrand }}</td>
                                <td>{{ $item->NewMeterNo }}</td>
                                <td>{{ $item->NewMeterReading }}</td>
                                <td>{{ $item->StationName }}</td>
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

                window.location.href = "{{ url('/tickets/download-meter-transfers/') }}" + "/" + $('#From').val() + "/" + $('#To').val() + "/" + $('#Office').val()
            })
        })
    </script>
@endpush