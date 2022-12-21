<div class="col-lg-12">
    <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#modal-add-log"><i class="fas fa-plus ico-tab"></i>Add Log/Remarks</button>
    <div class="timeline timeline-inverse" style="margin-top: 10px;">
        @if ($ticketLogs == null)
            <p><i>No ticketLogs recorded</i></p>
        @else
            @php
                $i = 0;
            @endphp
            @foreach ($ticketLogs as $item)
                <div class="time-label" style="font-size: .9em !important;">
                    <span class="{{ $i==0 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $item->Log }}
                    </span>
                </div>
                <div>
                <i class="fas fa-info-circle bg-primary"></i>

                <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> {{ date('h:i A', strtotime($item->created_at)) }}</span>

                        <p class="timeline-header"  style="font-size: .9em !important;"><a href="">{{ date('F d, Y', strtotime($item->created_at)) }}</a> by {{ $item->name }}</p>

                        @if ($item->LogDetails != null)
                            <div class="timeline-body" style="font-size: .9em !important;">
                                <?= $item->LogDetails ?>
                            </div>
                        @endif
                        
                    </div>
                </div>
                @php
                    $i++;
                @endphp
            @endforeach
        @endif
    </div>
</div>

{{-- MODAL FOR ADDING LOG MANUALLY --}}
<div class="modal fade" id="modal-add-log" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Log/Remarks</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                   
                    <div class="form-group col-lg-12">
                        <input type="text" id="title" placeholder="Title" class="form-control" autofocus="true">
                    </div>
                    <div class="form-group col-lg-12">
                        <textarea type="text" id="body" placeholder="Details/Description/Remarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="save-log">Save</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#save-log').on('click', function(e) {
                e.preventDefault()
                $.ajax({
                    url : "{{ route('tickets.save-ticket-log') }}",
                    type : 'GET',
                    data : {
                        TicketId : "{{ $tickets->id }}",
                        Description : $('#body').val(),
                        Title : $('#title').val()
                    },
                    success : function(res) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Log added'
                        })
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            title : 'Error adding logs',
                            icon : 'error'
                        })
                    }
                })
            })
        })
    </script>
@endpush