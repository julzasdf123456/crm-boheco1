@extends('layouts.app')

@section('content')
   @if ($schedule != null)
      <div class="row">
         {{-- HEADER --}}
         <div class="col-lg-12">
            <div class="card shadow-none" style="margin-top: 10px;">
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-3">
                        <span class="text-muted">Disconnector: </span>
                        <h4 class="text-primary">{{ $schedule->DisconnectorName }}</h4>
                     </div>

                     <div class="col-lg-3">
                        <span class="text-muted">Day: </span>
                        <h4 class="text-danger">{{ date('M d, Y', strtotime($schedule->Day)) }}</h4>
                     </div>
                     
                     <div class="col-lg-3">
                        <span class="text-muted">Billing Month: </span>
                        <h4>{{ date('F Y', strtotime($schedule->ServicePeriodEnd)) }}</h4>
                     </div>

                     <div class="col-lg-3">
                        <span class="text-muted">No. of Bills: </span>
                        <h4>{{ count($data) }}</h4>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         {{-- TABLE --}}
         <div class="col-lg-12">
            <div class="card shadow-none">
               <div class="card-header">
                  <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Consumers in this Schedule ({{ count($groupedData) }})</span>
               </div>
               <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-sm table-bordered">
                     <thead>
                        <th>#</th>
                        <th>Account No.</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                        <th>Meter No.</th>
                        <th>Pole No.</th>
                        <th>Account<br>Type</th>
                        <th>Account<br>Status</th>
                        <th>Total<br>Amount Due</th>
                        <th>No. Of<br>Months</th>
                        <th>Comment</th>
                        <th>Office Remarks</th>
                        <th></th>
                     </thead>
                     <tbody>
                        @php
                           $i = 1;
                        @endphp
                        @foreach ($groupedData as $item)
                           <tr>
                              <td>{{ $i }}</td>
                              <td>{{ $item->AccountNumber }}</td>
                              <td><strong>{{ $item->ConsumerName }}</strong></td>
                              <td>{{ $item->ConsumerAddress }}</td>
                              <td>{{ $item->MeterNumber }}</td>
                              <td>{{ $item->Pole }}</td>
                              <td>{{ $item->ConsumerType }}</td>
                              <td>{{ $item->AccountStatus }}</td>
                              <td class="text-right text-danger"><strong>{{ number_format($item->TotalAmountDue, 2) }}</strong></td>
                              <td class="text-right text-danger"><strong>{{ round($item->NoOfMonths, 2) }}</strong></td>
                              <td id="comment-{{ $item->AccountNumber }}">{{ $item->PaymentNotes }}</td>
                              <td id="remarks-{{ $item->AccountNumber }}">{{ $item->Notes }}</td>
                              <td>
                                 <button onclick="addRemarks(`{{ $item->AccountNumber }}`)" class="btn text-primary btn-sm float-right" title="Add Notes/Remarks"><i class="fas fa-pen"></i></button>
                              </td>
                           </tr>
                           @php
                              $i++;
                           @endphp
                        @endforeach
                     </tbody>
                  </table>            
               </div>
            </div>
         </div>      
      </div>
   @else
       <p class="text-center">No Data Found!</p>
   @endif
    
@endsection

@include('disconnection_schedules.modal_add_payment_notes')

@push('page_scripts')
   <script>
      var activeAcctNo = ""

      $(document).ready(function() {
         $('#save-notes').on('click', function() {
            $.ajax({
               url : "{{ route('disconnectionSchedules.add-payment-notes') }}",
               type : 'GET',
               data : {
                  AccountNumber : activeAcctNo,
                  ScheduleId : "{{ $schedule->id }}",
                  PaymentNotes : $('input[name="PaymentNotes"]:checked').val(),
                  Notes : $('#remarks').val()
               },
               success : function(res) {
                  Toast.fire({
                     icon : 'success',
                     text : 'Remarks added!'
                  })

                  $('#comment-' + activeAcctNo).text($('input[name="PaymentNotes"]:checked').val())
                  $('#remarks-' + activeAcctNo).text($('#remarks').val())

                  $('#remarks').val('')
                  $('#modal-payment-notes').modal('hide')
                  $('.form-check-input').prop('checked', false)
               },
               error : function(err) {
                  $('#modal-payment-notes').modal('hide')
                  $('.form-check-input').prop('checked', false)
                  Swal.fire({
                     icon : 'error',
                     text : 'Error adding remark!'
                  })
               }
            })
         })
      })

      function addRemarks(acctNo) {
         activeAcctNo = acctNo
         $('#modal-payment-notes').modal('show')
      }
   </script>
@endpush