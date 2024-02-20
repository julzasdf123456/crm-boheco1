@php
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <span class="badge-lg {{ $tickets->Status=="Executed" ? 'bg-success' : 'bg-warning' }}"><strong>{{ $tickets->Status }}</strong></span>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-default float-right"
                    href="{{ route('tickets.index') }}">
                    Back
                </a>
            </div>
        </div>
    </div>
</section>
<div class="content px-3">
    <div class="card">
        <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#logs" data-toggle="tab">
                    <i class="fas fa-info-circle"></i>
                    Ticket Details</a></li>
                <li class="nav-item"><a class="nav-link" href="#verification" data-toggle="tab">
                    <i class="fas fa-clipboard-check"></i>
                    Logs</a></li>
                @if ($serviceConnectionInspections != null)
                    <li class="nav-item"><a class="nav-link" href="#inspection" data-toggle="tab">
                        <i class="fas fa-info-circle"></i>
                        Inspection Ticket</a></li>
                @endif
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="logs">
                    @include('tickets.ticket_details')
                </div>

                <div class="tab-pane" id="verification">
                    @include('tickets.ticket_logs')
                </div>

                @if ($serviceConnectionInspections != null)
                    <div class="tab-pane" id="inspection">
                        @include('tickets.ticket_inspection_ticket')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR UPDATING CREATED AT --}}
<div class="modal fade" id="modal-date-filed" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Date Filed</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label for="created_at">Filed At</label>
                        <input type="text" name="created_at" id="created_at" value="{{ $tickets->created_at }}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-created-at">Save changes</button>
                {{-- <input type="submit" value="Save changes" id="submit" class="btn btn-primary"> --}}
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#created_at').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

{{-- MODAL FOR UPDATING SENT TO LINEMAN --}}
<div class="modal fade" id="modal-lineman-sent" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Lineman Receiving Date</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label for="DateTimeDownloaded">Sent to Lineman at</label>
                        <input type="text" name="DateTimeDownloaded" id="DateTimeDownloaded" value="{{ $tickets->DateTimeDownloaded }}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-lenman-sent">Save changes</button>
                {{-- <input type="submit" value="Save changes" id="submit" class="btn btn-primary"> --}}
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeDownloaded').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

{{-- MODAL FOR UPDATING LINEMAN ARRIVED AT SITE --}}
<div class="modal fade" id="modal-lineman-arrived" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Lineman Arrived on Site</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label for="DateTimeLinemanArrived">Lineman Arrived at</label>
                        <input type="text" name="DateTimeLinemanArrived" id="DateTimeLinemanArrived" value="{{ $tickets->DateTimeLinemanArrived }}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-lenman-arrival">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeLinemanArrived').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

{{-- MODAL FOR UPDATING OF EXECUTION --}}
<div class="modal fade" id="modal-execution" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Field Assessment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label>Assessment</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Status" id="executed" value="Executed" {{ $tickets->Status=='Executed' ? 'checked' : '' }}>
                            <label class="form-check-label" for="executed">Executed</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Status" id="not-executed" value="Not Executed" {{ $tickets->Status=='Not Executed' ? 'checked' : '' }}>
                            <label class="form-check-label" for="not-executed">Not Executed</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Status" id="acted" value="Acted" {{ $tickets->Status=='Acted' ? 'checked' : '' }}>
                            <label class="form-check-label" for="not-executed">Acted</label>
                        </div>
                    </div>

                    @if (in_array($tickets->TicketRepoId, Tickets::getMeterInspectionsId()))
                        <div class="form-group">
                            <label>Recommendation</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Assessment" id="replace" value="Replace" {{ $tickets->Assessment=='Replace' ? 'checked' : '' }}>
                                <label class="form-check-label" for="replace">Replace</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Assessment" id="retain" value="Retain" {{ $tickets->Assessment=='Retain' ? 'checked' : '' }}>
                                <label class="form-check-label" for="retain">Retain</label>
                            </div>
                        </div>
                    @else
                        <input class="form-check-input" type="hidden" name="Assessment" value="">
                    @endif

                    <div class="form-group">
                        <label for="DateTimeLinemanExecuted">Date of Execution</label>
                        <input type="text" name="DateTimeLinemanExecuted" id="DateTimeLinemanExecuted" value="{{ $tickets->DateTimeLinemanExecuted }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="Notes">Notes/Field Remarks</label>                        
                        <textarea type="text" class="form-control" name="Notes" id="Notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-execution">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeLinemanExecuted').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // UPDATE CREATED AT
            $('#update-created-at').on('click', function() {
                $.ajax({
                    url : '{{ url("/tickets/update-date-filed") }}',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        created_at : $('#created_at').val(),
                        id : "{{ $tickets->id }}",
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        // alert(error);
                    }
                })
            });

            // UPDATE SENT TO LINEMAN
            $('#update-lenman-sent').on('click', function() {
                $.ajax({
                    url : '{{ url("/tickets/update-date-downloaded") }}',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateTimeDownloaded : $('#DateTimeDownloaded').val(),
                        id : "{{ $tickets->id }}",
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        // alert(error);
                    }
                })
            });

            // UPDATE LINEMAN ARRIVED ON SITE
            $('#update-lenman-arrival').on('click', function() {
                $.ajax({
                    url : '{{ url("/tickets/update-date-arrival") }}',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateTimeLinemanArrived : $('#DateTimeLinemanArrived').val(),
                        id : "{{ $tickets->id }}",
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        // alert(error);
                    }
                })
            });

            // UPDATE EXECUTION STATUS
            $('#update-execution').on('click', function() {
                $.ajax({
                    url : '{{ url("/tickets/update-execution") }}',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateTimeLinemanExecuted : $('#DateTimeLinemanExecuted').val(),
                        id : "{{ $tickets->id }}",
                        Status : $('input[name="Status"]:checked').val(),
                        Assessment : $('input[name="Assessment"]:checked').val(),
                        Notes : $('#Notes').val(),
                    },
                    success : function(response) {
                        var ticket = response['Ticket']
                        if (ticket=='1668541254390' || ticket=='1672792232225') {
                            window.location.href = "{{ url('/tickets/change-meter-update') }}" + "/" + response['id']
                        } else {
                            location.reload()
                        }
                        
                    },
                    error : function(error) {
                        // alert(error);
                        console.log(error)
                    }
                })
            });
        })
    </script>
@endpush
