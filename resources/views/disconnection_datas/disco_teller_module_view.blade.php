@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-12">
                <h4>
                     <span class="text-primary">Disco Collection </span> - 
                     <span class="text-muted">Disconnector: </span><strong>{{ $name }}</strong> | 
                     <span class="text-muted">Day: </span> <span class="text-danger">{{ date('F d, Y', strtotime($date)) }}</span>
                </h4>
           </div>
       </div>
   </div>
</section>

<div class="row">
   {{-- ALL DATA --}}
   <div class="col-lg-12">
      <div class="card shadow-none" style="height: 60vh;">
         <div class="card-header">
            <span class="card-title">
               <i class="fas fa-dollar-sign ico-tab"></i>Collection Data
               @if (count($doublePayments) > 0)
                  <button class="btn btn-sm btn-danger" style="margin-left: 10px;" data-toggle="modal" data-target="#modal-double-payments"><i class="fas fa-exclamation-circle ico-tab-mini"></i>Double Payments ({{ count($doublePayments) }})</button>
               @endif
            </span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-sm">
               <thead>
                  <th>#</th>
                  <th>Account Number</th>
                  <th>Consumer Name</th>
                  <th>Consumer Address</th>
                  <th>Billing Month</th>
                  <th>Amount Due</th>
                  <th>Surcharge</th>
                  <th>Amount Paid</th>
                  <th>Collection Date</th>
            </thead>
            <tbody>
                  @php
                     $i = 1;
                     $icon = "";
                     $bg = "";
                     $totalCollectionNoServiceFee = 0;
                     $unPosted = 0;
                  @endphp
                  @foreach ($data as $item)
                     @if ($item->Status == null)
                        @php
                           $icon = 'fa-exclamation-circle text-danger';
                        @endphp
                     @else
                        @php
                           $icon = 'fa-check-circle text-success';
                        @endphp
                     @endif
                     <tr>
                        <td>{{ $i }}</td>
                        <td><i class="fas {{ $icon }} ico-tab-mini"></i>
                           {{ $item->AccountNumber }}
                           @if ($item->PORNumber != null)
                               <span class="badge bg-success">POSTED</span>             
                           @else    
                              @php
                                 $unPosted += 1;
                              @endphp              
                           @endif
                        </td>
                        <td>{{ $item->ConsumerName }}</td>
                        <td>{{ $item->ConsumerAddress }}</td>
                        <td>{{ date('M Y', strtotime($item->ServicePeriodEnd)) }}</td>
                        <td class="text-right text-danger"><strong>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0 }}</strong></td>
                        <td class="text-right text-danger"><strong>{{ is_numeric($item->Surcharge) ? number_format($item->Surcharge, 2) : 0 }}</strong></td>
                        <td class="text-right text-primary"><strong>{{ is_numeric($item->PaidAmount) ? number_format($item->PaidAmount, 2) : 0 }}</strong></td>
                        <td>{{ $item->DisconnectionDate != null ? date('M d, Y h:i A', strtotime($item->DisconnectionDate)) : '' }}</td>
                     </tr>
                     @php
                        $totalCollectionNoServiceFee += is_numeric($item->PaidAmount) ? round($item->PaidAmount, 2) : 0;
                        $i++;
                     @endphp
                  @endforeach
            </tbody>
            </table>
         </div>
      </div>
   </div>  

   {{-- SUMMARY --}}
   <div class="col-lg-12">
      <div class="card shadow-none">
         <div class="card-body">
            <div class="row">
               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Amount Collected</p>
                  <h2 class="text-primary text-center">{{ number_format($totalCollectionNoServiceFee, 2) }}</h2>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center"><i>Without Service Fee</i></p>

                  <div class="divider"></div>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">No. of Accounts Paid</p>
                  <h2 class="text-primary text-center">{{ count($groupedData) }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Amount Collected</p>
                  <h2 class="text-success text-center">{{ number_format($totalCollectionNoServiceFee + (33.6 * count($groupedData)), 2) }}</h2>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center"><i>With Service Fee</i></p>

                  <div class="divider"></div>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Service Fee</p>
                  <h2 class="text-primary text-center">{{ number_format(count($groupedData) * 33.6, 2) }}</h2>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center"><i>Service Fee: </i><strong>{{ number_format(count($groupedData) * 30, 2) }}</strong></p>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center"><i>Service Fee VAT: </i><strong>{{ number_format(count($groupedData) * 3.6, 2) }}</strong></p>
               </div>

               @if ($unPosted > 0)
                  <div class="col-lg-3" style="border-left: 1px solid #9a9a9a;">
                     <label for="ORNumber">Input OR Number:</label>
                     <input type="number" id="ORNumber" placeholder="OR Number" class="form-control">
                  </div>

                  <div class="col-lg-3">
                     <label for="ORDate">Input OR Date:</label>
                     <input type="text" id="ORDate" placeholder="OR Date" class="form-control">
                     @push('page_scripts')
                        <script type="text/javascript">
                           $('#ORDate').datetimepicker({
                                 format: 'YYYY-MM-DD',
                                 useCurrent: true,
                                 sideBySide: true
                           })
                        </script>
                     @endpush

                     <br>

                     <button class="btn btn-success float-right" id="post-btn"><i class="fas fa-check-circle ico-tab"></i>SAVE & POST</button>
                  </div>
               @endif               
            </div>
         </div>
      </div>
   </div>

</div>
@endsection

@include('disconnection_datas.modal_double_payments')

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#post-btn').on('click', function() {
               var orNumber = $('#ORNumber').val()
               var orDate = $('#ORDate').val()

               if (jQuery.isEmptyObject(orNumber) | jQuery.isEmptyObject(orDate)) {
                  Toast.fire({
                     icon : 'warning',
                     text : 'Please input OR Number and OR Date'
                  })
               } else {
                  Swal.fire({
                     title: 'Posting Confirmation',
                     text : 'By posting these payments, you have verified that the details you inputted are correct.',
                     showDenyButton: true,
                     confirmButtonText: 'POST',
                     denyButtonText: `CANCEL`,
                  }).then((result) => {
                     /* Read more about isConfirmed, isDenied below */
                     if (result.isConfirmed) {
                        $('#post-btn').prop('disabled', true)
                        $.ajax({
                           url : "{{ route('disconnectionDatas.post-payments') }}",
                           type : "GET",
                           data : {
                              DisconnectorName : "{{ $name }}",
                              DisconnectionDate : "{{ $date }}",
                              ORNumber : orNumber,
                              ORDate : orDate
                           },
                           success : function(res) {
                              Toast.fire({
                                 icon : 'success',
                                 text : 'Payment Posted!'
                              })
                              location.reload()
                           },
                           error : function(err) {
                              Swal.fire({
                                 icon : 'error',
                                 text : 'Error posting payments!'
                              })
                           }
                        })
                     } 
                  })
               }
            })
        })
    </script>
@endpush