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
            <span class="card-title"><i class="fas fa-dollar-sign ico-tab"></i>Collection Data</span>
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
                        <td><i class="fas {{ $icon }} ico-tab-mini"></i>{{ $item->AccountNumber }}</td>
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

               <div class="col-lg-3" style="border-right: 1px solid #9a9a9a;">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Amount Collected</p>
                  <h2 class="text-success text-center">{{ number_format($totalCollectionNoServiceFee + (33.6 * count($groupedData)), 2) }}</h2>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center"><i>With Service Fee</i></p>

                  <div class="divider"></div>
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Service Fee</p>
                  <h2 class="text-primary text-center">{{ number_format(count($groupedData) * 33.6, 2) }}</h2>
               </div>

               <div class="col-lg-3">
                  <label for="ORNumber">Input OR Number:</label>
                  <input type="number" placeholder="OR Number" class="form-control">
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

                  <button class="btn btn-success float-right"><i class="fas fa-check-circle ico-tab"></i>SAVE</button>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>
@endsection

@push('page_scripts')
    <script>
        
    </script>
@endpush