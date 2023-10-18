{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-installation-fee" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
           <div class="modal-header">
               <div>
                   <h4>Installation Fees (BoM)</h4>
               </div>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
            <span class="text-muted"><i>If Transformer is paid in full together with the installation fee.</i></span>
               <div class="form-group row">
                   <label for="InstallationFee" class="col-lg-6">Installation Fee</label>
                   <input type="number" id="InstallationFee" class="col-lg-6 form-control form-control-sm text-right" step="any" autofocus>
               </div>
               <div class="form-group row">
                  <label for="LaborCost" class="col-lg-6">Labor Cost & Contingencies</label>
                  <input type="number" id="LaborCost" class="col-lg-6 form-control form-control-sm text-right" step="any">
               </div>
               <div class="form-group row">
                  <label for="VAT" class="col-lg-6">12% EVAT</label>
                  <input type="number" id="VAT" class="col-lg-6 form-control form-control-sm text-right" step="any">
               </div>

               <div class="divider"></div>
               <span class="text-muted"><i>If Installation Fee has a Promisory Note.</i></span>
               <div class="form-group row">
                  <label for="InstallationFeePartial" class="col-lg-6">Installation Fee Partial</label>
                  <input type="number" id="InstallationFeePartial" class="col-lg-6 form-control form-control-sm text-right" step="any">
               </div>

               <div class="divider"></div>
               <span class="text-muted"><i>If Transformer is ammortized (Skip this if transformer is paid in full).</i></span>
               <div class="form-group row">
                  <label for="TransformerDownpayment" class="col-lg-6">Transformer Downpayment</label>
                  <input type="number" id="TransformerDownpayment" class="col-lg-6 form-control form-control-sm text-right" step="any">
               </div>
               <div class="form-group row">
                  <label for="TransformerVAT" class="col-lg-6">Transformer Full VAT</label>
                  <input type="number" id="TransformerVAT" class="col-lg-6 form-control form-control-sm text-right" step="any">
               </div>

               <div class="divider"></div>
               <div class="form-group row">
                  <label for="Total" class="col-lg-6">BOM TOTAL</label>
                  <h2 id="Total" class="col-lg-6 text-danger text-right">P 0.0</h2>
               </div>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-primary" id="save"><i class="fas fa-save ico-tab-mini"></i>Save</button>
           </div>
       </div>
   </div>
</div>

@push('page_scripts')
   <script>
      $(document).ready(function() {
         $('#InstallationFee').keyup(function(e) {
            getTotal()
         })

         $('#LaborCost').keyup(function(e) {
            getTotal()
         })

         $('#VAT').keyup(function(e) {
            getTotal()
         })

         $('#save').on('click', function() {
            $.ajax({
               url : "{{ route('serviceConnectionPayTransactions.save-installation-fee') }}",
               type : 'GET',
               data : {
                  ServiceConnectionId : "{{ $serviceConnections->id }}",
                  InstallationFee : jQuery.isEmptyObject($('#InstallationFee').val()) ? 0 : $('#InstallationFee').val(),
                  LaborCost : jQuery.isEmptyObject($('#LaborCost').val()) ? 0 : $('#LaborCost').val(),
                  Evat : jQuery.isEmptyObject($('#VAT').val()) ? 0 : $('#VAT').val(),
               },
               success : function(res) {
                  Toast.fire({
                     icon : 'success',
                     text : 'Installation Fee Added!'
                  })
                  location.reload()
               },
               error : function(err) {
                  Swal.fire({
                     icon : 'error',
                     text : 'Error adding Installation Fees'
                  })
               }
            })
         })
      })

      function getTotal() {
         var installationFee = jQuery.isEmptyObject($('#InstallationFee').val()) ? 0 :  parseFloat($('#InstallationFee').val())
         var laborCost = jQuery.isEmptyObject($('#LaborCost').val()) ? 0 : parseFloat($('#LaborCost').val())
         var vat = jQuery.isEmptyObject($('#VAT').val()) ? 0 :  parseFloat($('#VAT').val())

         var total = installationFee + laborCost + vat

         $('#Total').text('₱ ' + Number(total).toLocaleString(2))
      }
   </script>    
@endpush