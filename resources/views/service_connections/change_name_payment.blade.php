@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;"><strong class="text-danger"></strong>Payment Computation</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-5 col-md-6">
         {{--  INFO --}}
        <div class="card shadow-none">
            <div class="card-header border-0">
                <span class="card-title">Consumer Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-borderless table-sm">
                    <tr>
                        <td class="text-muted">Old Account Name</td>
                        <td><i class="fas fa-user-circle ico-tab"></i>{{ $serviceConnection->ServiceAccountName }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">New Account Name</td>
                        <td><i class="fas fa-user-circle ico-tab"></i>{{ $serviceConnection->OrganizationAccountNumber }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Existing Account Number</td>
                        <td title="Account Number"><i class="fas fa-user-alt ico-tab"></i>{{ $serviceConnection->AccountNumber }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td title="Address"><i class="fas fa-hashtag ico-tab"></i>{{ ServiceConnections::getAddress($serviceConnection) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- BILLS --}}
        <div class="card shadow-none" style="height: 58vh;">
            <div class="card-header">
               <span class="card-title">Recent Bills Ledger</span>
            </div>
            <div class="card-body table-responsive p-0">
               <table class="table table-hover table-bordered table-sm">
                  <thead>
                     <th>Billing Month</th>
                     <th>Kwh Used</th>
                     <th>Bill Amount</th>
                  </thead>
                  <tbody>
                     @php
                        $ave = 0;
                        $i = 0;
                     @endphp
                     @foreach ($bills as $item)
                         <tr class="{{ $i < 3 ? 'bg-info' : '' }}">
                           <td>{{ date('M Y', strtotime($item->ServicePeriodEnd)) }}</td>
                           <td>{{ $item->PowerKWH }}</td>
                           <td>{{ number_format($item->NetAmount, 2) }}</td>
                         </tr>

                         @php
                              if ($i < 3) {
                                 $ave += $item->NetAmount;
                              }
                             $i++;
                         @endphp
                     @endforeach
                     @php
                         $ave = $ave/3;
                         $evat = $ave * .12;
                     @endphp
                  </tbody>
               </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-md-6">
        @include('flash::message')
        <div class="card shadow-none">
            <div class="card-header bg-success">
               <span class="card-title">Payment Module</span>
            </div>
            <div class="card-body">
               <form action="{{ route('serviceConnections.store-change-name') }}" method="POST">
               <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <table class="table table-sm table-borderless table-hover">
                  <tr>
                     <td title="Average Consumption for 3 Months">
                        <div class="custom-control custom-switch">
                           <input type="checkbox" class="custom-control-input" checked id="bill-deposit-switch">
                           <label class="custom-control-label" for="bill-deposit-switch" style="font-weight: normal"><strong>Bill Deposit</strong></label>
                        </div>
                     </td>
                     <td>
                        <input onkeyup="computeTotal()" class="form-control text-right" type="number" step="any" id="BillDeposit" name="BillDeposit" value="{{ round($ave, 3) }}">
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div class="custom-control custom-switch">
                           <input type="checkbox" class="custom-control-input" checked id="membership-switch">
                           <label class="custom-control-label" for="membership-switch" style="font-weight: normal"><strong>Membership Fee</strong></label>
                        </div>
                     </td>
                     <td>
                        <input onkeyup="computeTotal()" class="form-control text-right" type="number" step="any" id="MembershipFee" name="MembershipFee" value="5">
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div class="custom-control custom-switch">
                           <input type="checkbox" class="custom-control-input" checked id="evat-switch">
                           <label class="custom-control-label" for="evat-switch" style="font-weight: normal"><strong>EVAT (12%)</strong></label>
                        </div>
                     </td>
                     <td>
                        <input onkeyup="computeTotal()" class="form-control text-right" type="number" step="any" id="EVAT" name="EVAT" value="{{ round($evat, 2) }}">
                     </td>
                  </tr>
                  <tr>
                     <td><strong>TOTAL</strong></td>
                     <td>
                        <input class="form-control text-right text-danger" style="font-size: 1.5em; font-weight: bold;" type="number" step="any" id="Total" name="Total" required>
                     </td>
                  </tr>
               </table>
               <button type="submit" class="btn btn-success btn-lg float-right">Save</button>
               </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
   <script>

      var bdOg = jQuery.isEmptyObject($('#BillDeposit').val()) ? 0 : parseFloat($('#BillDeposit').val())
      var membershipOg = jQuery.isEmptyObject($('#MembershipFee').val()) ? 0 : parseFloat($('#MembershipFee').val())
      var evatOg = jQuery.isEmptyObject($('#EVAT').val()) ? 0 : parseFloat($('#EVAT').val())

      $(document).ready(function() {
         computeTotal()

         $('#bill-deposit-switch').on('change', function(e) {
            let cond = e.target.checked
            if (cond == true) {
               $('#BillDeposit').val(bdOg).change()
            } else {
               $('#BillDeposit').val(0).change()
            }
            computeTotal()
         })

         $('#membership-switch').on('change', function(e) {
            let cond = e.target.checked
            if (cond == true) {
               $('#MembershipFee').val(membershipOg).change()
            } else {
               $('#MembershipFee').val(0).change()
            }
            computeTotal()
         })

         $('#evat-switch').on('change', function(e) {
            let cond = e.target.checked
            if (cond == true) {
               $('#EVAT').val(evatOg).change()
            } else {
               $('#EVAT').val(0).change()
            }
            computeTotal()
         })
      })

      function computeTotal() {
         var bd = jQuery.isEmptyObject($('#BillDeposit').val()) ? 0 : parseFloat($('#BillDeposit').val())
         var membership = jQuery.isEmptyObject($('#MembershipFee').val()) ? 0 : parseFloat($('#MembershipFee').val())
         var evat = jQuery.isEmptyObject($('#EVAT').val()) ? 0 : parseFloat($('#EVAT').val())

         var total = bd + membership + evat

         $('#Total').val(Number(total).toLocaleString(2))
      }
   </script>
@endpush