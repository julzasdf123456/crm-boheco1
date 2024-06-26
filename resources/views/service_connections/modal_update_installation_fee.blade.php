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
            <table class="table table-sm table-hover">
               <tbody>
                  <tr>
                     <td class="text-muted">Materials Total</td>
                     <td class="text-right">
                        <strong>{{ $totalTransactions != null ? number_format($totalTransactions->MaterialCost, 2) : 0 }}</strong>
                     </td>
                  </tr>
                  <tr>
                     <td class="text-muted">Labor Cost</td>
                     <td class="text-right">
                        <strong>{{ $totalTransactions != null ? number_format($totalTransactions->LaborCost, 2) : 0 }}</strong>
                     </td>
                  </tr>
                  <tr>
                     <td class="text-muted">Contingency, Handling, etc.</td>
                     <td class="text-right">
                        <strong>{{ $totalTransactions != null ? number_format($totalTransactions->ContingencyCost, 2) : 0 }}</strong>
                     </td>
                  </tr>
                  <tr>
                     <td class="text-muted">Materials VAT (12%)</td>
                     <td class="text-right">
                        <strong>{{ $totalTransactions != null ? number_format($totalTransactions->MaterialsVAT, 2) : 0 }}</strong>
                     </td>
                  </tr>
                  @php
                     $materials = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost) : 0;
                     $materialsVat = $totalTransactions != null ? ($totalTransactions->MaterialsVAT) : 0;
                     $materialsTotal = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost + $totalTransactions->MaterialsVAT) : 0;
                  @endphp
                  <tr>
                     <td class="text-muted"><strong>Total Installation Fee</strong></td>
                     <td class="text-right">
                        <strong>{{ $totalTransactions != null ? number_format($materialsTotal, 2) : 0 }}</strong>
                     </td>
                  </tr>
               </tbody>
            </table>
            <div class="divider"></div>
            <div style="width: 100%; margin-bottom: 10px;">
               <div class="col-lg-6 custom-control custom-switch">
                  <input type="checkbox" {{ $totalTransactions != null && $totalTransactions->InstallationPartial ? 'checked' : '' }} class="custom-control-input" id="PromisoryToggle">
                  <label class="custom-control-label" for="PromisoryToggle" id="PromisoryToggleLabel">If Installation Fee has a Promisory Note</label>
               </div>
            </div>
            <div class="form-group row">
               <label for="DownPaymentPercentage" class="col-lg-6">Down Payment Percentage</label>
               <input type="number" id="DownPaymentPercentage" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->InstallationFeeDownPaymentPercentage ? $totalTransactions->InstallationFeeDownPaymentPercentage : env('INSTALLATION_FEE_PARTIAL_DP_PERCENTAGE') }}" disabled>
            </div>

            <div class="form-group row">
               <label for="DownPaymentAmount" class="col-lg-6">Down Payment Amount</label>
               <input type="number" id="DownPaymentAmount" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->InstallationPartial ? $totalTransactions->InstallationPartial : null }}" disabled>
            </div>

            <div class="form-group row">
               <label for="Balance" class="col-lg-6">Balance</label>
               <input type="number" id="Balance" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->InstallationFeeBalance ? $totalTransactions->InstallationFeeBalance : null }}" disabled>
            </div>

            <div class="form-group row">
               <label for="Terms" class="col-lg-6">Terms (in Months)</label>
               <input type="number" id="Terms" class="col-lg-6 form-control form-control-sm text-right" step="any"  value="{{ $totalTransactions != null && $totalTransactions->InstallationFeeTerms ? $totalTransactions->InstallationFeeTerms : 1 }}" disabled>
            </div>

            <div class="form-group row">
               <label for="TermAmount" class="col-lg-6">Term Amount Per Month</label>
               <input type="number" id="TermAmount" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->InstallationFeeTermAmountPerMonth ? $totalTransactions->InstallationFeeTermAmountPerMonth : null }}" disabled>
            </div>

            {{-- MATERIALS WITHHOLDING 1% and 2% --}}
            <div class="divider"></div>
            <div class="form-group row">
               <div class="col-lg-12 custom-control custom-switch">
                  <input type="checkbox" {{ $totalTransactions != null && $totalTransactions->WithholdingTwoPercent != null && $totalTransactions->WithholdingTwoPercent > 0 ? 'checked' : '' }} class="custom-control-input" id="MaterialsWTSwitch">
                  <label class="custom-control-label" for="MaterialsWTSwitch" id="MaterialsWTSwitchLabel">Withholding Taxes For Materials (1% for Mat., 2% for Labor & Svcs.)</label>
              </div>
            </div>

            <div class="form-group row">
               <div class="col-lg-6">
                  <label>Materials 1%</label>
              </div>
               <input type="number" id="MaterialsOnePercent" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->WithholdingTwoPercent ? $totalTransactions->WithholdingTwoPercent : null }}" readonly>
            </div>

            <div class="form-group row">
               <div class="col-lg-6">
                  <label>Transformer 1%</label>
              </div>
               <input type="number" id="TransformerOnePercent" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->TransformerTwoPercentWT ? $totalTransactions->TransformerTwoPercentWT : null }}" readonly>
            </div>

            <div class="form-group row">
               <div class="col-lg-6">
                  <label>Labor & Services 2%</label>
              </div>
               <input type="number" id="LaborTwoPercent" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->Item1 ? $totalTransactions->Item1 : null }}" readonly>
            </div>

            {{-- MATERIALS WITHHOLDING --}}
            <div class="divider"></div>
            <span class="text-muted"><i>Withholding Taxes For Installation Fee (Materials VAT)</i></span>

            <div class="form-group row">
               <div class="col-lg-6 custom-control custom-switch">
                  <input type="checkbox" {{ $totalTransactions != null && $totalTransactions->WithholdingFivePercent != null && $totalTransactions->WithholdingFivePercent > 0  ? 'checked' : '' }} class="custom-control-input" id="WithholdingFivePercent">
                  <label class="custom-control-label" for="WithholdingFivePercent" id="WithholdingFivePercentLabel">Installation WT 5%</label>
              </div>
               <input type="number" id="WithholdingFivePercentAmount" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->WithholdingFivePercent ? $totalTransactions->WithholdingFivePercent : null }}" readonly>
            </div>

            {{-- TRANSFORMER WITHOLDING WITHHOLDING --}}
            <div class="divider"></div>
            <span class="text-muted"><i>Withholding Taxes For Transformer (Transformer VAT = {{ $totalTransactions != null ? number_format($totalTransactions->TransformerVAT, 2) : 0 }})</i></span>
           
            <div class="form-group row">
               <div class="col-lg-6 custom-control custom-switch">
                  <input type="checkbox" {{ $totalTransactions != null && $totalTransactions->TransformerFivePercentWT != null && $totalTransactions->TransformerFivePercentWT > 0  ? 'checked' : '' }} class="custom-control-input" id="TransformerFivePercentWT">
                  <label class="custom-control-label" for="TransformerFivePercentWT" id="TransformerFivePercentWTLabel">Transformer WT 5%</label>
              </div>
               <input type="number" id="TransformerFivePercentWTAmount" class="col-lg-6 form-control form-control-sm text-right" step="any" value="{{ $totalTransactions != null && $totalTransactions->TransformerFivePercentWT ? $totalTransactions->TransformerFivePercentWT : null }}" readonly>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="save"><i class="fas fa-download ico-tab-mini"></i>Save and Confirm Payment</button>
         </div>
       </div>
   </div>
</div>

@push('page_scripts')
   <script>
      $(document).ready(function() {
         $('#PromisoryToggle').on('change', function(e) {
            if (e.target.checked) {
               $('#DownPaymentPercentage').prop('disabled', false)
               $('#DownPaymentAmount').prop('disabled', false)
               $('#Balance').prop('disabled', false)
               $('#Terms').prop('disabled', false)
               $('#TermAmount').prop('disabled', false)
               computePromisory()
            } else {
               $('#DownPaymentPercentage').prop('disabled', true)
               $('#DownPaymentAmount').prop('disabled', true)
               $('#Balance').prop('disabled', true)
               $('#Terms').prop('disabled', true)
               $('#TermAmount').prop('disabled', true)
               clearFields()
            }
         })

         $('#MaterialsWTSwitch').on('change', function(e) {
            if (e.target.checked) {
               getOnePercentMaterials()
               getOnePercentTransformer()
               getTwoPercentLabor()
            } else {
               $('#MaterialsOnePercent').val("")
               $('#TransformerOnePercent').val("")
               $('#LaborTwoPercent').val("")
            }
         })

         $('#WithholdingFivePercent').on('change', function(e) {
            if (e.target.checked) {
              getFivePercentMaterials()
            } else {
               $('#WithholdingFivePercentAmount').val('')
            }
         })

         $('#TransformerFivePercentWT').on('change', function(e) {
            if (e.target.checked) {
              getFivePercentTransformer()
            } else {
               $('#TransformerFivePercentWTAmount').val('')
            }
         })

         $('#DownPaymentPercentage').on('change', function() {
            computePromisory()
         })

         $('#DownPaymentPercentage').on('keyup', function() {
            computePromisory()
         })

         $('#Terms').on('change', function() {
            computePromisory()
         })

         $('#Terms').on('keyup', function() {
            computePromisory()
         })

         $('#save').on('click', function() {
            $.ajax({
               url : "{{ route('serviceConnectionPayTransactions.save-installation-fee') }}",
               type : 'GET',
               data : {
                  ServiceConnectionId : "{{ $serviceConnections->id }}",
                  WithholdingTwoPercent : jQuery.isEmptyObject($('#MaterialsOnePercent').val()) ? null : $('#MaterialsOnePercent').val(),
                  WithholdingFivePercent  : jQuery.isEmptyObject($('#WithholdingFivePercentAmount').val()) ? null : $('#WithholdingFivePercentAmount').val(),
                  TransformerTwoPercentWT : jQuery.isEmptyObject($('#TransformerOnePercent').val()) ? null : $('#TransformerOnePercent').val(),
                  Item1 : jQuery.isEmptyObject($('#LaborTwoPercent').val()) ? null : $('#LaborTwoPercent').val(),
                  TransformerFivePercentWT  : jQuery.isEmptyObject($('#TransformerFivePercentWTAmount').val()) ? null : $('#TransformerFivePercentWTAmount').val(),
                  InstallationFeeDownPaymentPercentage  : jQuery.isEmptyObject($('#DownPaymentPercentage').val()) ? null : $('#DownPaymentPercentage').val(),
                  InstallationPartial  : jQuery.isEmptyObject($('#DownPaymentAmount').val()) ? null : $('#DownPaymentAmount').val(),
                  InstallationFeeBalance  : jQuery.isEmptyObject($('#Balance').val()) ? null : $('#Balance').val(),
                  InstallationFeeTerms  : jQuery.isEmptyObject($('#Terms').val()) ? null : $('#Terms').val(),
                  InstallationFeeTermAmountPerMonth  : jQuery.isEmptyObject($('#TermAmount').val()) ? null : $('#TermAmount').val(),
               },
               success : function(res) {
                  Toast.fire({
                     icon : 'success',
                     text : 'Quotation Modified!'
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

      function computePromisory() {
         var percentage = parseFloat($('#DownPaymentPercentage').val())
         var materials = "{{ $materials }}"
         var vat = "{{ $materialsVat }}"
         materials = parseFloat(materials)
         vat = parseFloat(vat)

         var dpAmount = (percentage * materials) + vat
         var balance = materials - (percentage * materials)
         var terms = jQuery.isEmptyObject($('#Terms').val()) ? 1 : parseInt($('#Terms').val())
         var termAmount = balance / terms

         $('#DownPaymentAmount').val(Math.round((dpAmount + Number.EPSILON) * 100) / 100)
         $('#Balance').val(Math.round((balance + Number.EPSILON) * 100) / 100)
         $('#TermAmount').val(Math.round((termAmount + Number.EPSILON) * 100) / 100)
      }

      function clearFields() {
         $('#DownPaymentAmount').val("")
         $('#Balance').val("")
         $('#TermAmount').val("")
      }



      function getOnePercentMaterials() {
         var materials = "{{ $totalTransactions != null ? $totalTransactions->MaterialCost : 0 }}"

         var vatables = parseFloat(materials)
         $('#MaterialsOnePercent').val(Math.round(((vatables * (.01)) + Number.EPSILON) * 100) / 100)
      }

      function getOnePercentTransformer() {
         var transformer = "{{ $totalTransactions != null ? $totalTransactions->TransformerCost : 0 }}"

         var vatables = parseFloat(transformer)
         $('#TransformerOnePercent').val(Math.round(((vatables * (.01)) + Number.EPSILON) * 100) / 100)
      }

      function getTwoPercentLabor() {
         var labor = "{{ $totalTransactions != null ? $totalTransactions->LaborCost : 0 }}"
         var contingency = "{{ $totalTransactions != null ? $totalTransactions->ContingencyCost : 0 }}"

         var vatables = parseFloat(labor) + parseFloat(contingency)
         // $('#LaborTwoPercent').val(Math.round(((vatables * (2/12)) + Number.EPSILON) * 100) / 100)
         $('#LaborTwoPercent').val(Math.round(((vatables * (.02)) + Number.EPSILON) * 100) / 100)
      }

      function getFivePercentMaterials() {
         var materialsVat = "{{ $totalTransactions != null ? $totalTransactions->MaterialsVAT : 0 }}"

         var vatables = parseFloat(materialsVat)
         $('#WithholdingFivePercentAmount').val(Math.round(((vatables * (5/12)) + Number.EPSILON) * 100) / 100)
      }

      function getFivePercentTransformer() {
         var materialsVat = "{{ $totalTransactions != null ? $totalTransactions->TransformerVAT : 0 }}"

         var vatables = parseFloat(materialsVat)
         $('#TransformerFivePercentWTAmount').val(Math.round(((vatables * (5/12)) + Number.EPSILON) * 100) / 100)
      }
   </script>    
@endpush