@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>New Change Meter Energized</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- HEad --}}
        <div class="col-lg-12">
            {!! Form::open(['route' => 'tickets.change-meter-unconfirmed', 'method' => 'GET']) !!}
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <label for="From">From</label>
                            <input type="text" id="From" name="From" class="form-control form-control-sm" required value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}">  
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#From').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush                      
                        </div>
                        <div class="col-lg-2">
                            <label for="To">To</label>
                            <input type="text" id="To" name="To" class="form-control form-control-sm" required value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}">       
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#To').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush                 
                        </div>
                        <div class="col-lg-2">
                            <label for="Office">Office</label>
                            <select id="Office" name="Office" class="form-control form-control-sm">
                                <option value="All" {{ isset($_GET['Office']) && $_GET['Office']=='All' ? 'selected' : '' }}>All</option>
                                <option value="MAIN OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                                <option value="SUB-OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                            </select>                            
                        </div>
                        <div class="col-lg-2">
                            <label for="">Action</label>
                            <br>
                            <button type="submit" class="btn btn-primary btn-sm" id="filterBtn" title="Filter"><i class="fas fa-check"></i> Filter</button>
                            <button class="btn btn-warning btn-sm" id="print" title="Print"><i class="fas fa-print"></i> Print</button>
                            <button class="btn btn-success btn-sm" id="printAccomplished" title="Print Accomplished Change Meters"><i class="fas fa-print"></i> Print Finished</button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        {{-- TABLE --}}
        <div class="col-lg-12">
            <table class="table table-hover table-resposive table-sm table-bordered" id="results-table">
                <thead>
                    <th>Ticket ID</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Old Meter No.</th>
                    <th>Old Meter Rdng.</th>
                    <th>New Meter No.</th>
                    <th>New Meter Rdng.</th>
                    <th>Date Executed</th>
                    <td></td>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr id="{{ $item->id }}">
                            <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->AccountNumber }}</a></td>
                            <td>{{ $item->ConsumerName }}</td>
                            <td>{{ Tickets::getAddress($item) }}</td>
                            <td class="text-danger">{{ $item->CurrentMeterNo }}</td>
                            <td class="text-danger"><strong>{{ $item->CurrentMeterReading }}</strong> kWh</td>
                            <td class="text-primary">{{ $item->NewMeterNo }}</td>
                            <td class="text-primary"><strong>{{ $item->NewMeterReading }}</strong> kWh</td>
                            <td>{{ date('M d, Y', strtotime($item->DateTimeLinemanExecuted)) }}</td>
                            <td>
                                @if ($item->AccountNumber != null)
                                    <button id="btn-{{ $item->id }}" class="btn btn-primary btn-xs" onclick="confirm('{{ $item->id }}')" ticket_id="{{ $item->id }}">Change</button>
                                @else
                                    <span class="badge bg-danger">No Acct. Number</span>
                                @endif
                                
                                <button id="btnremove-{{ $item->id }}" style="margin-left: 5px;" class="btn btn-link btn-xs float-right" onclick="remove('{{ $item->id }}')" ticket_id="{{ $item->id }}"><i class="fas fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@include('tickets.modal_change_meter_confirm')

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#printAccomplished').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/tickets/print-change-meter-accomplished') }}/" + $('#From').val() + "/" + $('#To').val()
            })

            $('#print').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/tickets/print-change-meters') }}/" + $('#From').val() + "/" + $('#To').val() + "/" + $('#Office').val()
            })
        })    

        function confirm(id) {
            $('#ticket-id').text(id)
            $('#modal-change-meter-confirm').modal('show')
        }

        function remove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Removing this will mark the ticket as change metered.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('tickets.mark-as-change-meter-done') }}",
                        type : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Change meter marked as confirmed'
                            })
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Error confirming change meter'
                            })
                        }
                    })
                }
            })
            
        }
    </script>    
@endpush