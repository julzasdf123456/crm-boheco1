<div class="modal fade" id="modal-check-available-sequence" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Available Sequence Numbers</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader" class="spinner-border gone text-success float-right" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="route-check">
                <table class="table table-hover table-sm" id="sequence-no-table">
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>
        $('#modal-check-available-sequence').on('shown.bs.modal', function () {
            $('#sequence-no-table tbody tr').remove()
            $('#loader').removeClass('gone')
            $.ajax({
                url : "{{ route('accountMasters.get-available-sequence-numbers') }}",
                type : 'GET',
                data : {
                    Route : $('#route-check').val(),
                },
                success : function(res) {
                    $('#sequence-no-table tbody').append(res)
                    $('#loader').addClass('gone')
                },
                error : function(res) {
                    Swal.fire({
                        title : 'Error getting sequences',
                        icon : 'error'
                    })
                    $('#loader').addClass('gone')
                }
            })
        });

        function selectRoute(acct) {
            $('#SequenceNumber').val(acct)
            $('#SequenceNumber').focus()
            $('#modal-check-available-sequence').modal('hide')
            $('#sequence-no-table tbody tr').remove()
        }
    </script>
@endpush