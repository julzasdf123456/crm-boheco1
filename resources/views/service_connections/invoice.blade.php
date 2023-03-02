<?php
    use App\Models\ServiceConnections;
?>

@if($totalTransactions == null)
    <p class="text-center"><i>No payment transactions recorded!</i></p>
    @if ($serviceConnectionInspections != null)
        @if ($serviceConnectionInspections->Status != "Approved")
            <p class="text-danger"><i class="fas fa-info-circle ico-tab"></i> <i>NOTE that you can't create payment invoice if the inspection isn't approved or successful.</i></p>
        @else
            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor'])) 
                <a href="{{ route('serviceConnectionPayTransactions.create-step-four', [$serviceConnections->id]) }}" class="btn btn-primary btn-sm" title="Add Payment Transaction">
                    <i class="fas fa-plus ico-tab"></i>
                    Create Payment Invoice</a>
            @endif
        @endif
    @endif
@else
    
<div class="row">
    @if ($serviceConnections->ORNumber != null)
        <div class="col-lg-12">
            <div class="card bg-success">
                <div class="card-body">
                    <span><strong>PAID</strong> - OR Number: <strong>{{ $serviceConnections->ORNumber }}</strong> | Payment Date: <strong>{{ $serviceConnections->ORDate }}</strong></span>
                </div>
            </div>
        </div>
    @endif

    {{-- TOTAL PAYMENT --}}
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-header border-0 bg-success">
                <span>
                    <strong>Payment Summary</strong>
                </span>
                @if ($totalTransactions->Notes != null) 
                    <span class="badge bg-warning" style="padding: 6px; margin-left: 10px;"><i class="fas fa-check-circle ico-tab-mini"></i>Paid</span>
                @endif

                <div class="card-tools">
                    @if ($totalTransactions != null)
                        <div class="col-md-12">
                            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor'])) 
                                @if ($totalTransactions->Notes == null && $serviceConnections->ORNumber == null) 
                                    <a href="{{ route('serviceConnectionPayTransactions.create-step-four', [$serviceConnections->id]) }}" class="btn btn-tool text-white">
                                    <i class="fas fa-pen"></i></a>
                                @endif
                                
                                <a href="{{ route('serviceConnections.print-invoice', [$serviceConnections->id]) }}" class="btn btn-tool text-white"> <i class="fas fa-print"></i></a>
                            @endif
                        </div>
                    @else
                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor'])) 
                            <a href="{{ route('serviceConnectionPayTransactions.create-step-four', [$serviceConnections->id]) }}" class="btn btn-tool text-white">
                                <i class="fas fa-pen"></i></a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="card-body">
                <table class="table table-hover table-sm table-borderless">
                    <tr>
                        <td>Service Connection Fee</td>
                        <th class="text-right text-primary" onclick="showServiceConnectionFeeComputation()">₱ <span id="service-connection-fee-display">{{ $totalTransactions != null ? number_format($totalTransactions->ServiceConnectionFee, 2) : "0" }}</span></th>
                    </tr>
                    <tr>
                        <td>Elec. Labor Charge</td>
                        <th class="text-right text-primary">₱ <span id="wiring-labor-charge-display" data-toggle="tooltip" data-placement="left">{{ $totalTransactions != null ? number_format($totalTransactions->LaborCharge, 2) : '0.00' }}</span></th>
                    </tr>
                    <tr>
                        <td>BOHECO I Share</td>
                        <th class="text-right text-primary">₱ <span id="wiring-labor-charge-display" data-toggle="tooltip" data-placement="left">{{ $totalTransactions != null ? number_format($totalTransactions->BOHECOShare, 2) : '0.00' }}</span></th>
                    </tr>
                    <tr>
                        <td>Other Payables</td>
                        <th class="text-right text-primary">₱ <span id="bill-deposit-display" data-toggle="tooltip" data-placement="left">{{ $totalTransactions != null ? number_format($totalTransactions->Particulars, 2) : '0.00' }}</span></th>
                    </tr>
                    <tr>
                        <td>Bill Deposit</td>
                        <th class="text-right text-primary">₱ <span id="bill-deposit-display" data-toggle="tooltip" data-placement="left">{{ $totalTransactions != null ? (is_numeric($totalTransactions->BillDeposit) ? number_format($totalTransactions->BillDeposit, 2) : '0.00') : '0.00' }}</span></th>
                    </tr>
                    <tr>
                        <td>Total VAT</td>
                        <th class="text-right text-primary">₱ <span id="total-vat-display" data-toggle="tooltip" data-placement="left">{{ $totalTransactions != null ? (is_numeric($totalTransactions->TotalVat) ? number_format($totalTransactions->TotalVat, 2) : '0.00') : '0.00' }}</span></th>
                    </tr>
                    <tr>
                        <td>2% WT</td>
                        <th class="text-right text-danger">- ₱ <span id="two-percent-display">{{ $totalTransactions != null ? ($totalTransactions->Form2307TwoPercent != null ? number_format($totalTransactions->Form2307TwoPercent, 2) : '') : '' }}</span></th>
                    </tr>
                    <tr>
                        <td>5% WT</td>
                        <th class="text-right text-danger">- ₱ <span id="five-percent-display">{{ $totalTransactions != null ? ($totalTransactions->Form2307FivePercent != null ? number_format($totalTransactions->Form2307FivePercent, 2) : '') : '' }}</span></th>
                    </tr>
                    <tr>
                        <th style="border-top: 1px solid #cdcdcd">Over All Total</th>
                        <th class="text-right text-primary" style="font-size: 2em; border-top: 1px solid #cdcdcd">                                
                            ₱ <span id="overall-total-display">{{ $totalTransactions != null ? (is_numeric($totalTransactions->Total) ? number_format($totalTransactions->Total, 2) : '0.00') : '0.00' }}</span></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ELECTRICIAN --}}
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-header">
                <span><strong>Electrician Information</strong></span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table tabl-sm table-hover">
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td>BOHECO I Accredited</td>
                            <th>{{ $serviceConnections->ElectricianAcredited=='Yes' ? 'Yes' : 'No' }}</th>
                        </tr>
                        <tr>
                            <td>Electrician</td>
                            <th>{{ $serviceConnections->ElectricianName }}</th>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <th>{{ $serviceConnections->ElectricianAddress }}</th>
                        </tr>
                        <tr>
                            <td>Contact No</td>
                            <th>{{ $serviceConnections->ElectricianContactNo }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- LABOR CHARGE --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span><strong>Installation and Wiring Labor Details</strong></span>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th>Item</th>
                        <th class="text-right">Rate per Unit</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">VAT (12%)</th>
                        <th class="text-right">Total</th>
                    </thead>
                    <tbody>
                        @foreach ($laborWiringCharges as $item)
                            <tr>
                                <td>{{ $item->Material }}</td>
                                <td class="text-right">{{ $item->Rate }}</td>
                                <td class="text-right">{{ $item->Quantity }}</td>
                                <td class="text-right">{{ number_format($item->Vat, 2) }}</td>
                                <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- OTHER PAYABLES --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span><strong>Other Payables</strong></span>
            </div>

            <div class="card-body table-responsive p-0">
                <table id="particulars_table" class="table table-sm table-bordered table-hover">
                    <thead>
                        <th>Item</th>
                        <th class="text-right">Amnt</th>
                        <th class="text-right">VAT</th>
                        <th class="text-right">Total</th>
                    </thead>
                    <tbody>
                        @if ($particularPayments != null)
                            @foreach ($particularPayments as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ $item->Particular }}</td>  
                                    <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->Vat, 2) }}</td>  
                                    <td class="text-right">{{ number_format($item->Total, 2) }}</td> 
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- BILL DEPOSIT --}}
    <div class="card shadow-none">
        <div class="card-header">
            <span><strong>Bill Deposit Computation</strong></span>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-sm">
                <thead>
                    <th colspan="2">Load (kVA)</th>
                    <th colspan="2" title="85% Power Factor">85% PF</th>
                    <th colspan="2" title="Dynamic Demand Factor depends on the consumer type">Dynamic DF %</th>
                    <th colspan="2">Hours</th>
                    <th>Average Rate (12 Mo.)</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input id="Load" name="Load" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $serviceConnections->LoadCategory }}" disabled>
                        </td>
                        <td>x</td>
                        <td>
                            <input id="PowerFactor" name="PowerFactor" type="number" step="any" class="form-control form-control-sm text-right" value=".85" disabled>
                        </td>
                        <td>x</td><td>
                            <input id="DemandFactor" name="DemandFactor" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null ? $billDeposit->DemandFactor : '' }}">
                        </td>
                        <td>x</td>
                        <td>
                            <input id="Hours" name="Hours" type="number" step="any" class="form-control form-control-sm text-right" value="720" disabled>
                        </td>
                        <td>x</td><td>
                            <input id="AverageRate" name="AverageRate" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null ? $billDeposit->AverageRate : '' }}" disabled>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endif