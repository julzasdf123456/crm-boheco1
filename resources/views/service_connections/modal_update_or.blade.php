{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-update-or" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>Input OR Number</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Input OR Number" id="ornumber" required>
                    <input type="text" class="form-control" placeholder="Input OR Date" id="orDate" style="margin-top: 10px;" required>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#orDate').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-or"><i class="fas fa-save ico-tab-mini"></i>Save OR</button>
            </div>
        </div>
    </div>
</div>