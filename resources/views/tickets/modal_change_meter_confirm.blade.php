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
                           <td class="text-danger">
                              <input type="number" step="any" class="form-control form-control-sm" id="old-ticket-reading">
                           </td>
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

                              <button class="btn btn-xs btn-danger float-right gone" id="force-compute">Force Compute</button>
                           </td>
                           <td class="text-danger">
                              <input type="number" class="form-control form-control-sm" id="additionalKwh">
                           </td>
                           <td class="text-primary"></td>
                        </tr>
                        <tr>
                           <td colspan="2">Select Billing Month to Charge</td>
                           <td>
                              <input id="period-to-charge" type="text" class="form-control form-control-sm" placeholder="yyyy-mm-dd">
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>

               <div class="card gone" id="computation">
                  <div class="card-body">
                     <p class="text-muted"><i>Computation for No Pull-out Reading</i></p>
                     <div class="divider"></div>
                     <div class="row">
                        <div class="col-lg-12">
                           <p class="no-pads"><strong>No. of Days</strong> = Pullout Date - Last Reading Date</p>
                           <p class="no-pads"><strong>No. of Days</strong> = <span class="text-danger"><strong id="comp-pullout-date"></strong></span> - <span class="text-primary"><strong id="comp-last-reading-date"></strong></span></p>
                           <p class="no-pads"><strong>No. of Days</strong> = <span class="text-success"><strong id="comp-days"></strong></span></p>

                           <div class="divider"></div>
                        </div>

                        <div class="col-lg-12">
                           <p class="no-pads"><strong>θ Ave. Kwh/Month</strong> = (New Meter Reading ÷ No. of Days) x 30 days</p>
                           <p class="no-pads"><strong>θ</strong> = (<span class="text-danger"><input type="number" id="comp-new-meter-reading" class="form-control form-control-sm" style="width: 120px; display: inline;" placeholder="Input New Met. Reading"></span> ÷ <span class="text-primary"><strong id="comp-days-theta"></strong></span>) x 30</p>
                           <p class="no-pads"><strong>θ</strong> = <span class="text-success"><strong id="comp-ave-kwh"></strong></span></p>

                           <div class="divider"></div>
                        </div>

                        <div class="col-lg-12">
                           <p class="no-pads"><strong>Additionl Kwh</strong> = θ - New Meter Reading</p>
                           <p class="no-pads"><strong>Additionl Kwh</strong> = <span class="text-danger"><strong id="comp-theta"></strong></span> - <span class="text-danger"><strong id="comp-new-meter-reading-ave"></strong></span></p>
                           <p class="no-pads"><strong>Additionl Kwh</strong> = <span class="text-success"><strong id="comp-additional-kwh"></strong></span></p>

                        </div>
                     </div>
                  </div>
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

         $('#modal-change-meter-confirm').on('hidden.bs.modal', function (e) {
            $('#additionalKwh').val("")
            $('#multiplier').val("1")
            $('#new-ticket-reading').val("")
            $('#old-ticket-reading').val("")
            $('#force-compute').addClass('gone')
            $('#computation').addClass('gone')
            $('#comp-new-meter-reading').val("")
         });

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
                     Multiplier : jQuery.isEmptyObject($('#multiplier').val()) ? 1 : $('#multiplier').val(),
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

         $('#force-compute').on('click', function() {
            $('#computation').removeClass('gone')
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
               $('#old-ticket-reading').val(res['CurrentMeterReading'])
               
               $('#new-ticket-brand').text(res['NewMeterBrand'])
               $('#new-ticket-serial').val(res['NewMeterNo'])
               $('#new-ticket-reading').val(res['NewMeterReading'])

               $('#period-to-charge').val(res['ServicePeriodEnd'])

               // WARNS IF METER NUMBE ALREADY EXSTS
               if (res['MeterNumberExists'] == true) {
                  exists = true
                  $('#save').attr('disabled', true)
                  $('#meter-warning').removeClass('gone')
               } else {
                  exists = false
                  $('#save').attr('disabled', false)
                  $('#meter-warning').addClass('gone')
               }

               if (isNumeric(res['CurrentMeterReading'])) {
                  // FETCH LAST READING IF THERE IS A PULLOUT READING
                  if (!jQuery.isEmptyObject(res['LastReading']) && !jQuery.isEmptyObject(res['CurrentMeterReading']) ) {
                     var lastReading = parseFloat(res['LastReading'])
                     var pullOutReading = parseFloat(res['CurrentMeterReading'])

                     var addKwh = pullOutReading - lastReading
                     if (addKwh < 0) {
                        addKwh = 0
                     }
                     $('#additionalKwh').val(Math.round((addKwh + Number.EPSILON) * 1) / 1)
                  }
               } else {
                  // USE FORMULA FOR NO PULLOUT READING                  
                  $('#force-compute').removeClass('gone')

                  // GET NO OF DAYS
                  $('#comp-pullout-date').text(moment(res['DateTimeLinemanExecuted']).format('MMM DD, YYYY'))
                  $('#comp-last-reading-date').text(moment(res['LastReadingDate']).format('MMM DD, YYYY'))
                  var start = moment(res['DateTimeLinemanExecuted'], "YYYY-MM-DD")
                  var end = moment(res['LastReadingDate'], "YYYY-MM-DD")
                  var days = moment.duration(start.diff(end)).asDays()
                  $('#comp-days').text(days)

                  // GET AVERAGE KWH PER MONTH
                  var averagePerMonth = 0
                  $('#comp-days-theta').text(days)
                  $('#comp-new-meter-reading').on('change', function() {
                     averagePerMonth = (parseInt(this.value) / days) * 30
                     var theta = Math.round((averagePerMonth + Number.EPSILON) * 1) / 1
                     $('#comp-ave-kwh').text(theta)

                     // GET ADDITIONAL KWH
                     var addKwh = theta - parseInt(this.value)
                     $('#comp-theta').text(theta)
                     $('#comp-new-meter-reading-ave').text(this.value)
                     $('#comp-additional-kwh').text(addKwh)
                     $('#additionalKwh').val(addKwh)
                  })
                  $('#comp-new-meter-reading').on('keyup', function() {
                     averagePerMonth = (parseInt(this.value) / days) * 30
                     var theta = Math.round((averagePerMonth + Number.EPSILON) * 1) / 1
                     $('#comp-ave-kwh').text(theta)

                     // GET ADDITIONAL KWH
                     var addKwh = theta - parseInt(this.value)
                     $('#comp-theta').text(theta)
                     $('#comp-new-meter-reading-ave').text(this.value)
                     $('#comp-additional-kwh').text(addKwh)
                     $('#additionalKwh').val(addKwh)
                  })
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

      function isNumeric(str) {
         if (typeof str != "string") return false 
         return !isNaN(str) && 
               !isNaN(parseFloat(str)) 
      }
   </script>    
@endpush