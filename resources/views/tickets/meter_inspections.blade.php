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
                <h4>Meter Inspection Monitoring</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'tickets.meter-inspections', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">

                    <div class="form-group col-md-3">
                        <label for="Crew">Crew</label>
                        <select name="Crew" id="Crew" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($crew as $item)
                                <option value="{{ $item->id }}"  {{ isset($_GET['Crew']) && $_GET['Crew']==$item->id ? 'selected' : '' }}>{{ $item->StationName }}</option>
                            @endforeach                            
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="Office">Office</label>
                        <select name="Office" id="Office" class="form-control form-control-sm">
                            <option value="All"  {{ isset($_GET['Office']) && $_GET['Office']=='All' ? 'selected' : '' }}>ALL</option>  
                            <option value="MAIN OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>    
                            <option value="SUB-OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>                            
                        </select>
                    </div>

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
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check ico-tab-mini"></i>View</button>
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
                <table class="table table-hover table-sm" id="results-table">
                    <thead>
                        <th>Ticket ID</th>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Ticket</th>
                        <th>Crew Assigned</th>
                        <th>Status</th>
                        <th>Office</th>
                        <th>Filed At</th>
                        <th>Executed At</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->AccountNumber }}</td>
                                <td><strong>{{ $item->ConsumerName }}</strong></td>
                                <td>{{ Tickets::getAddress($item) }} </td>
                                <td>{{ $item->ParentTicket . '-' . $item->Ticket }}</td>
                                <td>{{ $item->StationName }}</td>
                                <td>{{ $item->Status }}</td>
                                <td>{{ $item->Office }}</td>
                                <td>{{ $item->created_at != null ? date('M d, Y', strtotime($item->created_at)) : '' }}</td>
                                <td>{{ $item->DatetimeLinemanExecuted != null ? date('M d, Y', strtotime($item->DatetimeLinemanExecuted)) : '' }}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary float-right" href="{{ route('tickets.create-change-meter', [$item->AccountNumber]) }}"><i class="fas fa-plus"></i> Change Meter</a>
                                </td>
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

                window.location.href = "{{ url('/tickets/download-monthly-per-town/') }}" + "/" + $('#Month').val() + "/" + $('#Year').val()
            })
        })
    </script>
@endpush