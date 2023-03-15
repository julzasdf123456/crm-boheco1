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
                <h4>Meter Transfer Inspection Monitoring <span class="text-muted" style="font-size: .7em; margin-left: 10px;">Press <strong>F3</strong> to search</span></h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'tickets.meter-transfer-inspections', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
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
                        <th>Ticket No/Acct.No</th>
                        <th>Consumer Name/Address</th>
                        <th>Date Filed/Status</th>
                        <th style="width: 200px;">Crew Assigned</th>
                        <th style="width: 80px;"></th>
                        <th style="width: 140px;">Date/Time Executed</th>
                        <th>Remarks</th>
                        <th style="width: 120px;">Edit Status</th>
                        <th width="40px"></th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr id="{{ $item->id }}">
                                <td>
                                    <strong><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></strong>
                                    <br>
                                    <span class="text-muted" style="font-size: .9em;">{{ $item->AccountNumber }}</span>
                                </td>
                                <td>
                                    <strong>{{ $item->ConsumerName }}</strong>
                                    <br>
                                    <span class="text-muted" style="font-size: .9em;">{{ Tickets::getAddress($item) }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ date('M d, Y', strtotime($item->created_at)) }}</span>
                                    <br>
                                    <span class="badge bg-info" id="status-{{ $item->id }}">{{ $item->Status }}</span>
                                </td>
                                <td>
                                    <select name="CrewAssigned" id="CrewAssigned-{{ $item->id }}" class="form-control form-control-sm" style="width: 200px;">
                                        <option value="">-</option>
                                        @foreach ($crews as $itemx)
                                            <option value="{{ $itemx->id }}" {{ $item->CrewAssigned==$itemx->id ? 'selected' : '' }}>{{ $itemx->StationName }}</option>
                                        @endforeach                     
                                    </select>
                                </td>
                                <td>
                                    <button onclick="forwardToCrew('{{ $item->id }}')" class="btn btn-sm btn-info" title="Assign and Forward to Crew"><i class="fas fa-arrow-right"></i></button>
                                    <a href="{{ route('tickets.print-ticket-go-back', [$item->id]) }}" class="btn btn-sm btn-warning" title="Print Ticket Order"><i class="fas fa-print"></i></a>
                                </td>
                                <td>
                                    <input type="datetime-local" class="form-control form-control-sm" id="executed-{{ $item->id }}" placeholder="Input date executed">                                        
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="remarks-{{ $item->id }}" placeholder="Remarks">                                        
                                </td>
                                <td>
                                    <select id="statusdropdown-{{ $item->id }}" class="form-control form-control-sm">
                                        <option value="Executed">Executed</option>
                                        <option value="Acted" {{ $item->Status=='Acted' ? 'selected' : '' }}>Acted</option>
                                        <option value="Not Executed">Not Executed</option>
                                    </select>
                                </td>
                                <td>
                                    <button onclick="update('{{ $item->id }}')" class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
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
            
        })

        function forwardToCrew(id) {
            var crew = $('#CrewAssigned-' + id).val()
            if (!jQuery.isEmptyObject(crew)) {
                $.ajax({
                    url : "{{ route('tickets.update-status-and-crew') }}",
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id,
                        CrewAssigned : crew,
                        Status : 'Forwarded to Crew',
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Crew assigned successfully'
                        })
                        $('#status-' + id).text('Forwarded to Crew')
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

        function update(id) {
            var executed = jQuery.isEmptyObject($('#executed-' + id).val()) ? null : moment($('#executed-' + id).val()).format('YYYY-MM-DD HH:mm:ss')
            var status = $('#statusdropdown-' + id).val()
            var crew = $('#CrewAssigned-' + id).val()
            var remarks = $('#remarks-' + id).val()
            
            $.ajax({
                url : "{{ route('tickets.update-transfer-inspection-data') }}",
                type : 'GET',
                data : {
                    id : id,
                    Executed : executed,
                    CrewAssigned : crew,
                    Status : status,
                    Remarks : remarks,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Execution updated!',
                    })
                    $('#' + id).remove()

                    // print the attached ticket
                    if (status == 'Executed') {
                        if (!jQuery.isEmptyObject(res)) {
                            var attachedId = res['TaggedTicketId']
                            location.href = "{{ url('/tickets/print-ticket-go-back') }}" + "/" + attachedId
                        }
                    }                    
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error updating status!'
                    })
                }
            })
        }
    </script>
@endpush