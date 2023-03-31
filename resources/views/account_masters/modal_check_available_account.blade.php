<div class="modal fade" id="modal-check-available-acctno" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Available Account Numbers</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-10">
                        <input type="text" class="form-control form-control-sm" id="check-acct-no-route" maxlength="10" placeholder="Input Sample Account Number">
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-sm btn-primary" id="filter-btn">Filter</button>
                    </div>
                    {{-- LEFT --}}
                    <div class="col-lg-6">
                        <table class="table table-hover table-sm" id="check-acct-no-table-left">
                            <thead>
                                <th>Left Nearest Available</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>

                    {{-- RIGHT --}}
                    <div class="col-lg-6">
                        <table class="table table-hover table-sm" id="check-acct-no-table-right">
                            <thead>
                                <th>Right Nearest Available</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>
        $('#modal-check-available-acctno').on('shown.bs.modal', function () {
            fetchData()
        });

        $('#filter-btn').on('click', function() {
            fetchData()
        })

        function fetchData() {
            var sample = $('#check-acct-no-route').val()

            if (jQuery.isEmptyObject(sample)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Input Account Number First'
                })
            } else {
                if (sample.length == 10) {
                    $('#check-acct-no-table-left tbody tr').remove()
                    $('#check-acct-no-table-right tbody tr').remove()
                    $.ajax({
                        url : "{{ route('accountMasters.check-left-available-account-numbers') }}",
                        type : 'GET',
                        data : {
                            AccountNumberSample : sample,
                        },
                        success : function(res) {
                            $('#check-acct-no-table-left tbody').append(res['left'])
                            $('#check-acct-no-table-right tbody').append(res['right'])
                            // console.log(res)
                        },
                        error : function(res) {
                            Swal.fire({
                                title : 'Error getting accounts',
                                icon : 'error'
                            })
                        }
                    })
                } else {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Make sure that the account number inputted is 10-digits long'
                    })
                }                
            }            
        }

        function selectAccount(acct) {
            $('#AccountNumber').val(acct)
            $('#AccountNumber').focus()
            $('#modal-check-available-acctno').modal('hide')
            $('#check-acct-no-table-left tbody tr').remove()
            $('#check-acct-no-table-right tbody tr').remove()
        }
    </script>
@endpush