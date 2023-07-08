@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                     <h4>
                        <span class="text-muted">Disco Schedule: </span><strong>{{ $schedule->DisconnectorName }}</strong> | 
                        <span class="text-muted">Day: </span> {{ date('M d, Y', strtotime($schedule->Day)) }} | 
                        <span class="text-muted">Billing Month: </span> {{ date('F Y', strtotime($schedule->ServicePeriodEnd)) }}
                     </h4>
                </div>
            </div>
        </div>
    </section>

   <div class="row">
      <div class="col-lg-12">
         <div class="card shadow-none">
            <div class="card-header">
               <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Consumers in this Schedule</span>
            </div>
            <div class="card-body table-responsive p-0">
               <table class="table table-hover table-sm table-bordered">
                  <thead>
                     <th>#</th>
                     <th>Account Number</th>
                     <th>Consumer Name</th>
                     <th>Consumer Address</th>
                     <th>Meter Number</th>
                     <th>Account Type</th>
                     <th>Account Status</th>
                     <th>Billing Month</th>
                     <th>Amount Due</th>
                  </thead>
                  <tbody>
                     @php
                        $i = 1;
                     @endphp
                     @foreach ($data as $item)
                        <tr>
                           <td>{{ $i }}</td>
                           <td>{{ $item->AccountNumber }}</td>
                           <td><strong>{{ $item->ConsumerName }}</strong></td>
                           <td>{{ $item->ConsumerAddress }}</td>
                           <td>{{ $item->MeterNumber }}</td>
                           <td>{{ $item->ConsumerType }}</td>
                           <td>{{ $item->AccountStatus }}</td>
                           <td>{{ date('F Y', strtotime($item->ServicePeriodEnd)) }}</td>
                           <td class="text-right text-danger"><strong>{{ number_format($item->NetAmount, 2) }}</strong></td>
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