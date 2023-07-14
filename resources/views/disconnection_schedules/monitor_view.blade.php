@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-12">
                <h4>
                   <span class="text-muted">Disco Schedule: </span><strong>{{ $disconnectionSchedules->DisconnectorName }}</strong> | 
                   <span class="text-muted">Day: </span> <span class="text-danger">{{ date('M d, Y', strtotime($disconnectionSchedules->Day)) }}</span> | 
                   <span class="text-muted">Billing Month: </span> {{ date('F Y', strtotime($disconnectionSchedules->ServicePeriodEnd)) }}
                </h4>
           </div>
       </div>
   </div>
</section>

<div class="row">
   {{-- SUMMARY --}}
   <div class="col-lg-12">
      <div class="card shadow-none">
         <div class="card-body">
            <div class="row">
               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Amount Collected</p>
                  <h2 class="text-primary text-center">{{ $totalCollection != null && is_numeric($totalCollection->PaidAmount) ? number_format($totalCollection->PaidAmount, 2) : '0' }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Disconnected</p>
                  <h2 class="text-danger text-center">{{ $poll != null && is_numeric($poll->Disconnected) ? number_format($poll->Disconnected) : '0' }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Paid</p>
                  <h2 class="text-success text-center">{{ $poll != null && is_numeric($poll->Paid) ? number_format($poll->Paid) : '0' }}</h2>
               </div>

               <div class="col-lg-3">
                  <p style="margin: 0; padding: 0;" class="text-muted text-center">Total Promised</p>
                  <h2 class="text-warning text-center">{{ $poll != null && is_numeric($poll->Promised) ? number_format($poll->Promised) : '0' }}</h2>
               </div>
            </div>
         </div>
      </div>
   </div>

   {{-- ALL DATA --}}
   <div class="col-lg-12">
      <div class="card shadow-none" style="height: 70vh;">
         <div class="card-header">
            <span class="card-title"><i class="fas fa-list ico-tab"></i>Accomplishment Data</span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-sm">
               <thead>
                  <th>#</th>
                  <th>Account Number</th>
                  <th>Consumer Name</th>
                  <th>Consumer Address</th>
                  <th>Billing Month</th>
                  <th>Account<br>Type</th>
                  <th>Account Status</th>
                  <th>Amount Due</th>
                  <th>Disconnection<br>Assessment</th>
                  <th>Amount Paid</th>
                  <th>Date<br>Acted</th>
            </thead>
            <tbody>
                  @php
                     $i = 1;
                     $icon = "";
                     $bg = "";
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
                        <td>{{ $item->ConsumerType }}</td>
                        <td>{{ $item->AccountStatus }}</td>
                        <td class="text-right text-danger"><strong>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0 }}</strong></td>
                        <td class="text-center"><span class="badge {{ $item->Status=='Promised' ? 'bg-warning' : ($item->Status=='Paid' ? 'bg-success' : 'bg-danger') }}">{{ $item->Status }}</span></td>
                        <td class="text-right text-primary"><strong>{{ is_numeric($item->AmountPaid) ? number_format($item->AmountPaid, 2) : 0 }}</strong></td>
                        <td>{{ $item->DisconnectionDate != null ? date('M d, Y h:i A', strtotime($item->DisconnectionDate)) : '' }}</td>
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
@endsection