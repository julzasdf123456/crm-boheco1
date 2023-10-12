<div class="modal fade" id="modal-change-meter-confirm" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
           <div class="modal-header">
               <div>
                   <h4>Confirm Change Meter
                     <div id="loader" class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                     </div>
                   </h4>
                   
                   <p class="gone" id="ticket-id"></p>
               </div>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="row">
                  <table class="table table-hover table-bordered table-sm">
                     <thead>
                        <th></th>
                        <th>Old Meter Details</th>
                        <th>New Meter Details</th>
                     </thead>
                     <tbody>
                        <tr>
                           <td>Brand</td>
                           <td class="text-danger" id="old-ticket-brand"></td>
                           <td class="text-primary" id="new-ticket-brand"></td>
                        </tr>
                        <tr>
                           <td>Serial</td>
                           <td class="text-danger" id="old-ticket-serial"></td>
                           <td class="text-primary">
                              <input type="text" class="form-control form-control-sm" id="new-ticket-serial">
                              <label for="new-ticket-serial" class="text-danger gone" id="meter-warning">Meter number already exists!</label>
                           </td>
                        </tr>
                        <tr>
                           <td>Reading</td>
                           <td class="text-danger" id="old-ticket-reading"></td>
                           <td class="text-primary">
                              <input type="number" step="any" class="form-control form-control-sm" id="new-ticket-reading">
                           </td>
                        </tr>
                        <tr>
                           <td>Multiplier</td>
                           <td class="text-danger"></td>
                           <td class="text-primary">
                              <input type="number" step="any" class="form-control form-control-sm" id="multiplier" value="1">
                           </td>
                        </tr>
                        <tr>
                           <td>Additional Kwh

                              <span title="Additional kWH = LAST READING - PULLOUT READING"><i class="text-info fas fa-question-circle"></i></span>
                           </td>
                           <td class="text-danger">
                              <input type="number" class="form-control form-control-sm" id="additionalKwh">
                           </td>
                           <td class="text-primary"></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-primary" id="save"><i class="fas fa-check ico-tab-mini"></i>Confirm Change</button>
           </div>
       </div>
   </div>
</div>

@push('page_scripts')
   <script>
      var exists = false
      $(document).ready(function() {
         $('#modal-change-meter-confirm').on('shown.bs.modal', function (e) {
            getData($('#ticket-id').text())
         })

         $('#new-ticket-serial').keyup(function() {
            $('#loader').removeClass('gone')
            $.ajax({
               url : "{{ route('tickets.get-meter-details') }}",
               type : "GET",
               data : {
                  MeterNumber : this.value,
               },
               success : function(res) {
                  if (!jQuery.isEmptyObject(res)) {
                     exists = true
                     $('#save').attr('disabled', true)
                     $('#meter-warning').removeClass('gone')
                  } else {
                     exists = false
                     $('#save').attr('disabled', false)
                     $('#meter-warning').addClass('gone')
                  }
                  $('#loader').addClass('gone')
               },
               error : function(err) {
                  $('#loader').addClass('gone')
                  Toast.fire({
                     icon : 'error',
                     text : 'Error validating meter!'
                  })
               }
            })
         })

         $('#save').on('click', function() {
            if (jQuery.isEmptyObject($('#new-ticket-serial').val())) {
               Swal.fire({
                  icon : 'warning',
                  text : 'Please input meter number!'
               })
            } else {
               $.ajax({
                  url : "{{ route('tickets.confirm-change-meter') }}",
                  type : "GET",
                  data : {
                     Id : $('#ticket-id').text(),
                     MeterNumber : $('#new-ticket-serial').val(),
                     KwhStart : jQuery.isEmptyObject($('#new-ticket-reading').val()) ? 0 : $('#new-ticket-reading').val(),
                     Multiplier : jQuery.isEmptyObject($('#multiplier').val()) ? 0 : $('#multiplier').val(),
                     AdditionalKwh : jQuery.isEmptyObject($('#additionalKwh').val()) ? 0 : $('#additionalKwh').val(),
                  },
                  success : function(res) {
                     $('#' + $('#ticket-id').text()).remove()
                     $('#modal-change-meter-confirm').modal('hide')
                     Toast.fire({
                        icon : 'success',
                        text : 'Change meter success!'
                     })

                     $('#old-ticket-brand').text("")
                     $('#old-ticket-serial').text("")
                     $('#old-ticket-reading').text("")
                     
                     $('#new-ticket-brand').text("")
                     $('#new-ticket-serial').val("")
                     $('#new-ticket-reading').val("")
                  },
                  error : function(err) {
                     Toast.fire({
                        icon : 'error',
                        text : 'Error validating meter!'
                     })
                  }
               })
            }            
         })
      })

      function getData(id) {
         $.ajax({
            url : "{{ route('tickets.get-ticket-ajax') }}",
            type : "GET",
            data : {
               Id : id,
            },
            success : function(res) {
               $('#loader').addClass('gone')
               $('#old-ticket-brand').text(res['CurrentMeterBrand'])
               $('#old-ticket-serial').text(res['CurrentMeterNo'])
               $('#old-ticket-reading').text(res['CurrentMeterReading'] + " kWh")
               
               $('#new-ticket-brand').text(res['NewMeterBrand'])
               $('#new-ticket-serial').val(res['NewMeterNo'])
               $('#new-ticket-reading').val(res['NewMeterReading'])

               if (res['MeterNumberExists'] == true) {
                  exists = true
                  $('#save').attr('disabled', true)
                  $('#meter-warning').removeClass('gone')
               } else {
                  exists = false
                  $('#save').attr('disabled', false)
                  $('#meter-warning').addClass('gone')
               }

               if (!jQuery.isEmptyObject(res['LastReading']) && !jQuery.isEmptyObject(res['CurrentMeterReading']) ) {
                  var lastReading = parseFloat(res['LastReading'])
                  var pullOutReading = parseFloat(res['CurrentMeterReading'])

                  var addKwh = pullOutReading - lastReading
                  $('#additionalKwh').val(addKwh)
               }
            },
            error : function(err) {
               console.log(err)
               $('#loader').addClass('gone')
               Toast.fire({
                  icon : 'error',
                  text : 'Error getting ticket details'
               })
            }
         })
      }
   </script>    
@endpush