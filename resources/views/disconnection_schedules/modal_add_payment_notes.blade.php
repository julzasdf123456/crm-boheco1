{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-payment-notes" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>Add Notes/Remarks</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">                
                <div class="form-group">
                    <label>Additional Info</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="PaymentNotes" id="promisory" value="With Promisory">
                        <label class="form-check-label" for="promisory">With Promisory Note</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="PaymentNotes" id="priority" value="Disconnection Priority">
                        <label class="form-check-label" for="priority">Priority</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="PaymentNotes" id="collection" value="For Collection Only">
                        <label class="form-check-label" for="collection">For Collection Only</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" id="remarks" rows="3" placeholder="Add remarks here"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-notes"><i class="fas fa-save ico-tab-mini"></i>Save</button>
            </div>
        </div>
    </div>
</div>