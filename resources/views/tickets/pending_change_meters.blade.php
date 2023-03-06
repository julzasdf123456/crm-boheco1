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
                <h4>Pending Change Meters</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'tickets.pending-change-meters', 'method' => 'GET']) !!}
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
                        <label for="Status">Status</label>
                        <select name="Status" id="Status" class="form-control form-control-sm">
                            <option value="All">All</option>
                            <option value="Received"  {{ isset($_GET['Status']) && $_GET['Status']=='Received' ? 'selected' : '' }}>Received/Logged</option>   
                            <option value="Acted"  {{ isset($_GET['Status']) && $_GET['Status']=='Acted' ? 'selected' : '' }}>Acted</option>         
                            <option value="Download by Crew"  {{ isset($_GET['Status']) && $_GET['Status']=='Download by Crew' ? 'selected' : '' }}>Download by Crew</option>                     
                        </select>
                    </div>

                    <div class="form-group col-lg-2">
                        <label for="From">From</label>
                        {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'From', 'required' => 'true']) !!}
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
                        {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'To', 'required' => 'true']) !!}
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

                    <div class="form-group col-md-2">
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
                        <th>Filed At</th>
                        <th>Crew Assigned</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->AccountNumber }}</td>
                                <td><strong>{{ $item->ConsumerName }}</strong></td>
                                <td>{{ Tickets::getAddress($item) }} 
                                    @if ($item->StationName == null)
                                        <i id="Indicator-{{ $item->id }}" class="fas fa-exclamation-circle text-danger float-right"></i>
                                    @endif
                                </td>
                                <td>{{ $item->created_at != null ? date('M d, Y', strtotime($item->created_at)) : '' }}</td>
                                <td>
                                    <select name="CrewItem" id="CrewItem-{{ $item->id }}" class="form-control form-control-sm">
                                        <option value="">-</option>
                                        @foreach ($crew as $itemx)
                                            <option value="{{ $itemx->id }}" {{ $itemx->StationName==$item->StationName ? 'selected' : '' }}>{{ $itemx->StationName }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $item->Status }}</td>
                                <td id="{{ $item->id }}-buttons">
                                    <button class="btn btn-xs btn-primary float-right" onclick="updateCrew('{{ $item->id }}')">Save</button>
                                    @if ($item->KwhRating == 'Forwarded to ESD')
                                        <button id="{{ $item->id }}-undo" class="btn btn-xs btn-success float-right ico-tab-mini" onclick="undoForward('{{ $item->id }}')">Forwarded</button>
                                    @else
                                        <button id="{{ $item->id }}-forward" class="btn btn-xs btn-danger float-right ico-tab-mini" onclick="forwardToESD('{{ $item->id }}')">Forward to ESD</button>
                                    @endif
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

        function updateCrew(id) {
            var crew = $('#CrewItem-' + id).val()
            $.ajax({
                url : "{{ route('tickets.update-crew-ajax') }}",
                type : 'GET',
                data : {
                    id : id,
                    Crew : crew,
                },
                success : function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Crew updated!'
                    })
                    $('#Indicator-' + id).remove()
                },
                error : function(error) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error updating crew'
                    })
                }
            })
        }

        function forwardToESD(id) {
            $.ajax({
                url : "{{ route('tickets.forward-to-esd') }}",
                type : 'GET',
                data : {
                    id : id
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Forwarded to ESD'
                    })
                    $('#' + id + "-forward").remove()
                    $('#' + id + "-buttons").append('<button id="' + id + '-undo" class="btn btn-xs btn-success float-right ico-tab-mini" onclick="undoForward(' + id + ')">Forwarded</button>')
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error Forwarded to ESD'
                    })
                }
            })
        }

        function undoForward(id) {
            $.ajax({
                url : "{{ route('tickets.undo-forward') }}",
                type : 'GET',
                data : {
                    id : id
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Forward undone'
                    })
                    $('#' + id + "-undo").remove()
                    $('#' + id + "-buttons").append('<button id="' + id + '-forward" class="btn btn-xs btn-danger float-right ico-tab-mini" onclick="forwardToESD(' + id+ ')">Forward to ESD</button>')
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error Undoing Forwarding'
                    })
                }
            })
        }

    </script>
@endpush