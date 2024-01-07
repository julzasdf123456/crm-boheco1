<?php

use App\Models\IDGenerator;
use App\Models\ServiceConnections;
use App\Models\UnbundledRates;

$id = IDGenerator::generateID();

?>

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <p><strong><span class="badge-lg bg-warning">Step 6</span>Service Connection and Inspection Fees</strong></p>
            </div>
        </div>
    </div>
</section>

{{-- HIDDEN FIELDS --}}
<span id="account-type-alias" style="display: none;">{{ $serviceConnection->Alias }}</span>

<div class="row">
    @include('adminlte-templates::common.errors')
    <div class="col-lg-8">
        {{-- ELECTRICIAN --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span><strong>Electrician Information</strong></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>BOHECO I Accredited</td>
                                <td class="text-right">
                                    <input type="checkbox" name="ElectricianAcredited" id="ElectricianAcredited" {{ $serviceConnection->ElectricianAcredited==null ? '' : ($serviceConnection->ElectricianAcredited=='Yes' ? 'checked' : '') }} data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <select class="custom-select select2"  name="ElectricianId" id="ElectricianId">
                                        <option value="NULL">-- Select --</option>
                                        @foreach ($electricians as $item)
                                            <option value="{{ $item->id }}" {{ $item->id==$serviceConnection->ElectricianId ? 'selected' : '' }}>{{ $item->Name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-lg-6" style="border-left: 1px solid #cdcdcd; padding-left: 20px;">
                        <table class="table table-hover table-sm table-borderless">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" name="ElectricianName" id="ElectricianName" disabled value="{{ $serviceConnection->ElectricianName }}">
                                    </th>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" name="ElectricianAddress" id="ElectricianAddress" disabled value="{{ $serviceConnection->ElectricianAddress }}">
                                    </th>
                                </tr>
                                <tr>
                                    <td>Contact No</td>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" name="ElectricianContactNo" id="ElectricianContactNo" disabled value="{{ $serviceConnection->ElectricianContactNo }}">
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- LABOR FEES --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span><strong>Electrical Wiring Installation Labor Charge</strong></span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm table-borderless" id="labor-charge-table">
                    <thead>
                        <th>Particular</th>
                        <th class='text-center'>Quantity</th>
                        <th class='text-center'>Charge per Unit</th>
                        <th class='text-center'>Elec. Share</th>
                        <th class='text-center'>BOHECO I<br>Share (P20)</th>
                        <th class='text-center' colspan="2">VAT</th>
                        <th class='text-center'>Total</th>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($laborPayables as $item)
                            <tr id="{{ $item->id }}">
                                <td>{{ $item->Material }}</td>
                                <td>
                                    <input type="number" onchange="computeLaborCharge('{{ $item->id }}')" onkeyup="computeLaborCharge('{{ $item->id }}')" step="any" class="form-control form-control-sm text-right" value="{{ $item->Qty != null ? $item->Qty : ($item->Material=='Service Entrance' | $item->Material=='Street Light' ? 0 : $item->Qty) }}" name="{{ $item->id }}Quantity" id="{{ $item->id }}Quantity">
                                </td>
                                <td style="width: 120px;">
                                    <input type="number" step="any" class="form-control form-control-sm text-right" id="{{ $item->id }}Charge" value="{{ $item->Rate }}" disabled>
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control form-control-sm text-right" name="{{ $item->id }}ElecShare" value="" id="{{ $item->id }}ElecShare" disabled>
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control form-control-sm text-right" name="{{ $item->id }}BOHECOIShare" value="{{ number_format($item->BOHECOIShare, 2) }}" id="{{ $item->id }}BOHECOIShare" disabled>
                                </td>
                                <td style="width: 80px;">
                                    <input type="number" step="any" class="form-control form-control-sm text-right" id="{{ $item->id }}VAT" value="{{ $item->VatPercentage }}" disabled>
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control form-control-sm text-right" name="{{ $item->id }}VATAmount" value="{{ $item->Vat }}" id="{{ $item->id }}VATAmount" disabled>
                                </td>
                                <td>
                                    <input type="number" step="any" style="font-weight: bold;" class="form-control form-control-sm text-right" name="{{ $item->id }}Total" value="{{ $item->Total }}" id="{{ $item->id }}Total" disabled>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <table class="table table-sm table-hover table-borderless">
                    <tbody>
                        <tr>
                            <td>BOHECO Share Only</td>
                            <td>
                                <input type="checkbox" name="BOHECOShareOnly" id="BOHECOShareOnly" {{ $totalPayments==null ? '' : ($totalPayments->BOHECOShareOnly=='Yes' ? 'checked' : '') }} data-bootstrap-switch data-off-color="danger" data-on-color="success">
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- OTHER PAYMENTS --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span><strong>Other Payables</strong></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Item</label>
                            <select id="particulars" class="form-control form-control-sm">
                                <option value="">-- Select --</option>
                               @foreach ($particulars as $particular)
                                   <option default-amount="{{ $particular->DefaultAmount }}" vat="{{ $particular->VatPercentage }}" value="{{ $particular->id }}">{{ $particular->Particular }}</option>
                               @endforeach
                            </select>
                        </div>                                    
                    </div>

                    <input type="hidden" name="_token" id="csrfParticulars" value="{{Session::token()}}">

                    <div class="col-lg-4">
                        <div class="form-group-sm">
                            <label>Amount</label>
                            <input id="particular_amt" class="form-control form-control-sm" type="number" step="any" placeholder="Amount">
                        </div>                                    
                    </div>

                    <div class="col-lg-4">
                        <label style="opacity: 0; display: block;">Action</label>
                        <button id="add_particular" class="btn btn-sm btn-primary">Add</button>                                   
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <table id="particulars_table" class="table table-sm table-hover">
                            <thead>
                                <th>Item</th>
                                <th class="text-right">Amnt</th>
                                <th class="text-right">VAT</th>
                                <th class="text-right">Total</th>
                                <th width=10></th>
                            </thead>
                            <tbody>
                                @if ($particularPayments != null)
                                    @foreach ($particularPayments as $item)
                                        <tr id="{{ $item->id }}">
                                            <td>{{ $item->Particular }}</td>  
                                            <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                            <td class="text-right">{{ number_format($item->Vat, 2) }}</td>  
                                            <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                                            <td>
                                                <button class='btn btn-xs btn-danger' onClick='deleteParticulars("{{ $item->id }}")'><i class='fas fa-trash'></i></button>
                                            </td>  
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
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
                        <th></th>
                        <th colspan="2" class="text-center">Load (kVA)</th>
                        <th colspan="2" class="text-center" title="85% Power Factor">85% PF</th>
                        <th colspan="2" class="text-center" title="Dynamic Demand Factor depends on the consumer type">Dynamic DF %</th>
                        <th colspan="2" class="text-center">Hours</th>
                        <th class="text-center">Average Rate (12 Mo.)</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th></th>
                            <td>
                                <input id="Load" name="Load" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $serviceConnection->LoadCategory }}" disabled>
                            </td>
                            <td>x</td>
                            <td>
                                <input id="PowerFactor" name="PowerFactor" type="number" step="any" class="form-control form-control-sm text-right" value=".85" disabled>
                            </td>
                            <td>x</td>
                            <td>
                                <input id="DemandFactor" name="DemandFactor" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null ? $billDeposit->DemandFactor : ServiceConnections::getDemandFactor($serviceConnection->Alias) }}">
                            </td>
                            <td>x</td>
                            <td>
                                <input id="Hours" name="Hours" type="number" step="any" class="form-control form-control-sm text-right" value="720" disabled>
                            </td>
                            <td>x</td><td>
                                <input id="AverageRate" name="AverageRate" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null ? $billDeposit->AverageRate : UnbundledRates::getOneYearAverageRate($serviceConnection->Alias) }}" disabled>
                            </td>
                        </tr>
                        @if ($serviceConnection->Alias == 'I')
                            <tr>
                                <th>+</th>
                                <td>
                                    <input id="LoadI" name="LoadI" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $serviceConnection->LoadCategory }}" disabled>
                                </td>
                                <td>x</td>
                                <td>
                                    <input id="PowerFactorI" name="PowerFactorI" type="number" step="any" class="form-control form-control-sm text-right" value=".85" disabled>
                                </td>
                                <td></td>
                                <td> </td>
                                <td></td>
                                <td> </td>
                                <td>x</td><td>
                                    <input id="AverageTransmission" name="AverageTransmission" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null && $billDeposit->AverageTransmission != null ? $billDeposit->AverageTransmission : UnbundledRates::getOneYearAverageTransAndDist($serviceConnection->Alias) }}" disabled>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TOTAL --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0 bg-success">
                <span><strong>Payment Summary</strong></span>
            </div>

            <div class="card-body">
                <table class="table table-hover table-sm table-borderless">                    
                    <tbody>
                        <tr>
                            <td>Consumer Name</td>
                            <th class="text-right">{{ $serviceConnection->ServiceAccountName }}</th>
                        </tr>
                        <tr>
                            <td>Consumer Address</td>
                            <th class="text-right">{{ ServiceConnections::getAddress($serviceConnection) }}</th>
                        </tr>
                        <tr>
                            <td>Account Type</td>
                            <th class="text-right">{{ $serviceConnection->AccountTypeName }} ({{ $serviceConnection->Alias }})</th>
                        </tr>
                        <tr>
                            <td>Building Profile</td>
                            <th class="text-right">{{ $serviceConnection->BuildingType }}</th>
                        </tr>
                    </tbody>
                </table>

                <div class="divider"></div>

                <table class="table table-hover table-sm table-borderless">
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td>Service Connection Fee</td>
                            {{-- <th class="text-right text-primary" onclick="showServiceConnectionFeeComputation()">₱ <span id="service-connection-fee-display">{{ $totalPayments != null ? number_format($totalPayments->ServiceConnectionFee, 2) : number_format(ServiceConnections::getServiceConnectionFees($serviceConnection), 2) }}</span></th> --}}
                            <th class="text-right text-primary">₱ <span id="service-connection-fee-display">0.0</span></th>
                            <span id="service-connection-fee" style="display: none;">0</span>
                        </tr>
                        <tr>
                            <td>Elec. Wiring Labor Fees</td>
                            <th class="text-right text-primary">₱ <span id="wiring-labor-charge-display" data-toggle="tooltip" data-placement="left">{{ $totalPayments != null ? number_format($totalPayments->LaborCharge, 2) : '0.00' }}</span></th>
                        </tr>
                        <tr>
                            <td>BOHECO I Share</td>
                            <th class="text-right text-primary">₱ <span id="boheco-share" data-toggle="tooltip" data-placement="left">{{ $totalPayments != null ? number_format($totalPayments->BOHECOShare, 2) : '0.00' }}</span></th>
                        </tr>
                        <tr title="Bill deposits are being rounded down to the nearest hundredth">
                            <td>{{ ServiceConnections::isResidentials($serviceConnection->AccountType) ? 'Bill Deposit' : 'Energy Deposit' }}</td>
                            <th class="text-right text-primary">₱ <span id="bill-deposit-display" data-toggle="tooltip" data-placement="left">{{ $totalPayments != null ? $totalPayments->BillDeposit : '0.00' }}</span></th>
                        </tr>
                        <tr>
                            <td>Other Payables</td>
                            <th class="text-right text-primary">₱ <span id="particulars-display" data-toggle="tooltip" data-placement="left">{{ $totalPayments != null ? number_format($totalPayments->Particulars, 2) : '0.00' }}</span></th>
                        </tr>
                        <tr>
                            <td>Total VAT</td>
                            <th class="text-right text-primary">₱ <span id="total-vat-display" data-toggle="tooltip" data-placement="left">{{ $totalPayments != null ? $totalPayments->TotalVat : '0.00' }}</span></th>
                        </tr>
                        <tr>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" {{ $totalPayments != null ? ($totalPayments->Form2307TwoPercent==null || $totalPayments->Form2307TwoPercent=='0' ? '' : 'checked') : '' }} id="two-percent">
                                    <label class="custom-control-label" for="two-percent" style="font-weight: normal">2% WT</label>
                                </div>
                            </td>
                            <th class="text-right text-danger">- ₱ <span id="two-percent-display">{{ $totalPayments != null ? ($totalPayments->Form2307TwoPercent != null ? number_format($totalPayments->Form2307TwoPercent, 2) : '') : '' }}</span></th>
                        </tr>
                        <tr>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" {{ $totalPayments != null ? ($totalPayments->Form2307FivePercent==null || $totalPayments->Form2307FivePercent=='0' ? '' : 'checked') : '' }} id="five-percent">
                                    <label class="custom-control-label" for="five-percent" style="font-weight: normal">5% WT</label>
                                </div>
                            </td>
                            <th class="text-right text-danger">- ₱ <span id="five-percent-display">{{ $totalPayments != null ? ($totalPayments->Form2307FivePercent != null ? number_format($totalPayments->Form2307FivePercent, 2) : '') : '' }}</span></th>
                        </tr>
                        <tr>
                            <th style="border-top: 1px solid #cdcdcd">Over All Total</th>
                            <th class="text-right text-primary" style="font-size: 2em; border-top: 1px solid #cdcdcd">₱ <span id="overall-total-display">0</span></th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <button id="save-payment" class="btn btn-primary btn-sm"><i class="fas fa-check-circle ico-tab-mini"></i>Submit</button>

                <div id="loader" class="spinner-border gone text-success float-right" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script type="text/javascript">
        var vatPercentage = .12
        var serviceConnectionFees = 0
        var overAllSubTotal = 0
        var witholdableVat = 0
        var twoPercentVat = 0
        var fivePercentVat = 0
        var overAllVat = 0
        var billDeposit = 0
        var overAllTotal = 0

        var isAccredited = false
        var is2Percent = false
        var is5Percent = false

        var bohecoShareOnly = false

        var accountTypeAlias = ''

        $(document).ready(function() {
            serviceConnectionFees = parseFloat($('#service-connection-fee').text())
            accountTypeAlias = $('#account-type-alias').text()

            // VALIDATE 2% & 5%
            if ($('#two-percent').prop('checked')) {
                is2Percent = true
            } else {
                is2Percent = false
            }

            if ($('#five-percent').prop('checked')) {
                is5Percent = true
            } else {
                is5Percent = false
            }

            // VALIDATE IF ELECTRiCIAN IS CHECKED
            if ($('#ElectricianAcredited').prop('checked')) {
                isAccredited = true
                $('#ElectricianId').removeAttr('disabled')
                $('#ElectricianName').attr('disabled', 'true')
                $('#ElectricianAddress').attr('disabled', 'true')
                $('#ElectricianContactNo').attr('disabled', 'true')
            } else {
                isAccredited = false
                $('#ElectricianId').val('NULL').change()
                $('#ElectricianId').attr('disabled', 'true')
                $('#ElectricianName').removeAttr('disabled')
                $('#ElectricianAddress').removeAttr('disabled')
                $('#ElectricianContactNo').removeAttr('disabled')
            }

            // ELECTRICIAN ACCREDITED TOGGLE SWITCH
            $('#ElectricianAcredited').on('switchChange.bootstrapSwitch', function() {
                if ($(this).prop('checked')) {
                    isAccredited = true
                    $('#ElectricianId').removeAttr('disabled')
                    $('#ElectricianName').attr('disabled', 'true')
                    $('#ElectricianAddress').attr('disabled', 'true')
                    $('#ElectricianContactNo').attr('disabled', 'true')
                } else {
                    isAccredited = false
                    $('#ElectricianId').val('NULL').change()
                    $('#ElectricianId').attr('disabled', 'true')
                    $('#ElectricianName').removeAttr('disabled')
                    $('#ElectricianAddress').removeAttr('disabled')
                    $('#ElectricianContactNo').removeAttr('disabled')
                }
                getOverAllTotal()
            })

            // VALIDATE IF ELECTRiCIAN IS CHECKED
            if ($('#BOHECOShareOnly').prop('checked')) {
                bohecoShareOnly = true
            } else {
                bohecoShareOnly = false
            }

            // TOGGLE IF BOHECO SHARE ONLY IS CHECKED
            $('#BOHECOShareOnly').on('switchChange.bootstrapSwitch', function() {
                if ($(this).prop('checked')) {
                    bohecoShareOnly = true
                    // validateLaborCharge()
                } else {
                    bohecoShareOnly = false
                    // validateLaborCharge()
                }
                getOverAllTotal()
            })

            $('#two-percent').on('change', function(e) {
                let cond = e.target.checked;
                is2Percent = cond
                getOverAllTotal()
            })

            $('#five-percent').on('change', function(e) {
                let cond = e.target.checked;
                is5Percent = cond
                getOverAllTotal()
            })

            // ELECTRICIAN DROPDOWN
            $('#ElectricianId').on('change', function() {
                if (this.value == 'NULL') {
                    $('#ElectricianName').val("")
                    $('#ElectricianAddress').val("")
                    $('#ElectricianContactNo').val("")
                } else {
                    $.ajax({
                        url : "{{ route('electricians.get-electricians-ajax') }}",
                        type : 'GET',
                        data : {
                            id : this.value
                        },
                        success : function(res) {
                            $('#ElectricianName').val(res['Name'])
                            $('#ElectricianAddress').val(res['Address'])
                            $('#ElectricianContactNo').val(res['ContactNumber'])
                        },
                        error : function(err) {
                            Swal.fire({
                                title : 'Error getting electrician details',
                                icon : 'error'
                            })
                        }
                    })
                }
            })

            $('#DemandFactor').on('change', function(){
                getOverAllTotal()
            }) 

            $('#DemandFactor').on('keyup', function(){
                getOverAllTotal()
            }) 

            $('#save-payment').on('click', function() {   
                $(this).attr('disabled', 'true')           
                validateElectricianInfo()
            })

            /** 
             * PARTICULARS
             */
             $('#particulars').on('change', function() {
                $('#particular_amt').val($('#particulars option:selected').attr('default-amount'));
                $('#particular_amt').focus()
            });

            $('#add_particular').on('click', function() {
                var particularId = $('#particulars').val();
                var amnt = $('#particular_amt').val();
                var particular = $('#particulars option:selected').text();
                var vat = $('#particulars option:selected').attr('vat');

                if (jQuery.isEmptyObject(amnt)) {
                    Swal.fire({
                        text : 'Please provide an amount!',
                        icon : 'warning'
                    })
                } else {
                    var vatValue = parseFloat(amnt * vat)
                    var d = new Date()
                    var particularPaymentIdValue = d.getTime()
                    var total = parseFloat(amnt)  + vatValue
                    var scId = "{{ $serviceConnection->id }}"

                    $.ajax({
                        url : '/serviceConnectionPayTransactions',
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id : particularPaymentIdValue,
                            ServiceConnectionId: scId,
                            Particular: particularId,
                            Amount: parseFloat(amnt).toFixed(2),
                            Vat: vatValue.toFixed(2),
                            Total: total.toFixed(2),
                        },
                        success : function(data) {
                            $('#particulars_table tbody').append(addRowToParticulars(particularPaymentIdValue, particular, parseFloat(amnt).toFixed(2), vatValue.toFixed(2), total.toFixed(2)));
                            getOverAllTotal()
                        },
                        error : function(error) {
                            console.log(error);
                            Swal.fire({
                                text : 'Error adding item!',
                                icon : 'error'
                            })
                        }
                    });    
                }
            });
            validateElecShare()
            getOverAllTotal()
        })

        function addRowToParticulars(id, particular, amnt, vat, total) {
            return "<tr id='" + id + "'>" +
                        "<td>" + particular + "</td>" +
                        "<td class='text-right'>" + amnt + "</td>" +
                        "<td class='text-right'>" + vat + "</td>" +
                        "<td class='text-right'>" + total + "</td>" +
                        "<td><button class='btn btn-xs btn-danger' onClick=deleteParticulars('" + id + "')><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";
        }

        function deleteParticulars(id) {
            if (confirm('Are you sure you want to delete this particular?')) {
                $.ajax({
                    url : '/serviceConnectionPayTransactions/' + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    success : function(data) {
                        $('#' + id).remove();
                        getOverAllTotal()
                    },
                    error : function(error) {
                        console.log(error);
                        Swal.fire({
                            text : 'Error deleting item!',
                            icon : 'error'
                        })
                    }
                });  
            } else {

            }            
        }

        function calculateTableColumn(table, index, display) {
            var total = 0;
            $('#' + table + ' tr').each(function() {
                var value = parseFloat($('td', this).eq(index).text().replace(',', ''));
                console.log(value);
                if (!isNaN(value)) {
                    total += parseFloat(value);
                }
            });
            $('#' + display).text(total.toLocaleString('en-US', {maximumFractionDigits:2}));
        }

        function calculateTableColumnRaw(table, index) {
            var total = 0;
            $('#' + table + ' tr').each(function() {
                var value = parseFloat($('td', this).eq(index).text().replace(',', ''));
                console.log(value);
                if (!isNaN(value)) {
                    total += parseFloat(value);
                }
            });
            return total
        }

        function calculateVatTableColumnRaw(table, index) {
            var total = 0;
            $('#' + table + ' tr').each(function() {
                var value = parseFloat($('td', this).eq(index).text().replace(',', ''));
                console.log(value);
                if (!isNaN(value)) {
                    total += parseFloat(value);
                }
            });
            return total
        }

        function computeLaborCharge(id) {
            var qty = parseFloat($('#' + id + 'Quantity').val())
            var charge = parseFloat($('#' + id + 'Charge').val())
            var vat = parseFloat($('#' + id + 'VAT').val())
            
            var subTotal = qty * charge
            var vatAmnt = subTotal * vat
            var bohecoShare = qty * 20
            var total = subTotal + vatAmnt
            
            $('#' + id + 'VATAmount').val(vatAmnt.toFixed(2))
            $('#' + id + 'BOHECOIShare').val(bohecoShare.toFixed(2))
            $('#' + id + 'ElecShare').val((subTotal - bohecoShare).toFixed(2))
            $('#' + id + 'Total').val(total.toFixed(2))

            getOverAllTotal()
        }

        function validateElecShare() {
            $('#labor-charge-table > tbody  > tr').each(function(index, tr) { 
                var id = $(tr).attr('id')

                  
                var qty = parseFloat($('#' + id + 'Quantity').val())
                var charge = parseFloat($('#' + id + 'Charge').val())
                var subTotal = qty * charge
                var bohecoShare = qty * 20
                var elecShare = subTotal - bohecoShare
                $('#' + id + 'ElecShare').val(elecShare.toFixed(2))           
            });

        }

        // VALIDATE LABOR CHARGE
        function validateLaborCharge() {
            if (isAccredited) {
                if (bohecoShareOnly) {
                    $('#wiring-labor-charge-display').text('0')
                    $('#boheco-share').text(Number((getBOHECOIShare()).toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
                } else {
                    $('#wiring-labor-charge-display').text(Number((getTotalLaborCharge()).toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
                    $('#boheco-share').text(Number((getBOHECOIShare()).toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
                }
            } else {
                $('#wiring-labor-charge-display').text(0.00)
                $('#boheco-share').text(0.00)
            }  
        }

        // VALIDATE PARTICULARS PAYMENTS
        function validateParticulars() {
            $('#particulars-display').text(Number((calculateTableColumnRaw('particulars_table', 1)).toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
        }

        // GET TOTAL LABOR CHARGE LESS VAT
        function getTotalLaborCharge() {
            var totalAmnt = 0
            $('#labor-charge-table > tbody  > tr').each(function(index, tr) { 
                var id = $(tr).attr('id')

                // var totalVal = $('#' + id + 'Total').val()
                // var vatVal = $('#' + id + 'VATAmount').val()
                // if (!jQuery.isEmptyObject(totalVal) && !jQuery.isEmptyObject(vatVal)) {
                //     totalAmnt += parseFloat(totalVal - 20) - parseFloat(vatVal) // -20 FOR BOHECO SHARE
                // }   
                var elecShare = $('#' + id + 'ElecShare').val()
                if (!jQuery.isEmptyObject(elecShare)) {
                    totalAmnt += parseFloat(elecShare)
                }             
            });
            // return totalAmnt
            if (isAccredited) {
                return totalAmnt
            } else {
                return 0
            } 
        }

        // GET TOTAL BOHECO I SHARE
        function getBOHECOIShare() {
            var totalAmnt = 0
            $('#labor-charge-table > tbody  > tr').each(function(index, tr) { 
                var id = $(tr).attr('id')

                var bohecoshare = $('#' + id + 'BOHECOIShare').val()
                if (!jQuery.isEmptyObject(bohecoshare)) {
                    totalAmnt += parseFloat(bohecoshare)
                }                
            })
            if (isAccredited) {
                return totalAmnt
            } else {
                return 0
            }
            
        }
        // GET TOTAL ELECTICIAN I SHARE
        function getElectricianShare() {
            var totalAmnt = 0
            $('#labor-charge-table > tbody  > tr').each(function(index, tr) { 
                var id = $(tr).attr('id')

                var bohecoshare = $('#' + id + 'ElecShare').val()
                if (!jQuery.isEmptyObject(bohecoshare)) {
                    totalAmnt += parseFloat(bohecoshare)
                }                
            })
            if (isAccredited) {
                return totalAmnt
            } else {
                return 0
            }            
        }

        // GET TOTAL LABOR VAT
        function getTotalLaborVat() {
            var totalVat = 0
            $('#labor-charge-table > tbody  > tr').each(function(index, tr) { 
                var id = $(tr).attr('id')

                var vatVal = $('#' + id + 'VATAmount').val()
                if (!jQuery.isEmptyObject(vatVal)) {
                    totalVat +=  parseFloat(vatVal)
                }                
            });
            return totalVat
        }

        // GET BILL DEPOSIT
        function getBillDepositNormal() {
            if (accountTypeAlias == 'I') {
                billDeposit = (parseFloat($('#Load').val()) *
                                parseFloat($('#PowerFactor').val()) *
                                parseFloat($('#DemandFactor').val()) *
                                parseFloat($('#Hours').val()) *
                                parseFloat($('#AverageRate').val())) +
                                (
                                    parseFloat($('#Load').val()) *
                                    parseFloat($('#PowerFactor').val()) *
                                    parseFloat($('#AverageTransmission').val())
                                )
            } else {
                billDeposit = parseFloat($('#Load').val()) *
                                parseFloat($('#PowerFactor').val()) *
                                parseFloat($('#DemandFactor').val()) *
                                parseFloat($('#Hours').val()) *
                                parseFloat($('#AverageRate').val())                                
            }

            // return Math.floor(billDeposit / 100) * 100
            return billDeposit
        }

        // GET OVER ALL TOTAL
        function getOverAllTotal() {
            if (isAccredited) {
                if (bohecoShareOnly) {
                    overAllSubTotal = getBOHECOIShare() + serviceConnectionFees + getBillDepositNormal() + calculateTableColumnRaw('particulars_table', 1)
                    witholdableVat = (getBOHECOIShare() * .12) + (serviceConnectionFees * .12) + calculateVatTableColumnRaw('particulars_table', 2)
                    overAllVat = (getBOHECOIShare() * .12) + (getBillDepositNormal() * .12) + (serviceConnectionFees * .12) + calculateVatTableColumnRaw('particulars_table', 2)
                } else {
                    overAllSubTotal = getTotalLaborCharge() + getBOHECOIShare() + serviceConnectionFees + getBillDepositNormal() + calculateTableColumnRaw('particulars_table', 1)
                    witholdableVat = getTotalLaborVat() + (serviceConnectionFees * .12) + calculateVatTableColumnRaw('particulars_table', 2)
                    overAllVat = getTotalLaborVat() + (getBillDepositNormal() * .12) + (serviceConnectionFees * .12) + calculateVatTableColumnRaw('particulars_table', 2)
                }                
            } else {
                overAllSubTotal = serviceConnectionFees + getBillDepositNormal() + calculateTableColumnRaw('particulars_table', 1)
                witholdableVat = (serviceConnectionFees * .12) + calculateVatTableColumnRaw('particulars_table', 2)
                overAllVat = (getBillDepositNormal() * .12) + (serviceConnectionFees * .12) + calculateVatTableColumnRaw('particulars_table', 2)
            }

            // 2%
            if (is2Percent) {
                twoPercentVat = witholdableVat * (2/12)
            } else {
                twoPercentVat = 0
            }

            // 5%
            if (is5Percent) {
                fivePercentVat = overAllVat * (5/12)
            } else {
                fivePercentVat = 0
            }
            
            overAllTotal = (overAllSubTotal + overAllVat) - (twoPercentVat + fivePercentVat)

            validateLaborCharge()
            validateParticulars()

            // TOOLTIPS
            $('#service-connection-fee-display').attr('title', '12% VAT: ' + Number((serviceConnectionFees * .12).toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))

            $('#two-percent-display').text(Number(twoPercentVat.toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
            $('#five-percent-display').text(Number(fivePercentVat.toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
            $('#bill-deposit-display').text(Number(getBillDepositNormal().toFixed(0)).toLocaleString(undefined, {minimumFractionDigits: 2}))
            $('#total-vat-display').text(Number(overAllVat.toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
            $('#overall-total-display').text(Number(overAllTotal.toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2}))
        }
        
        /**
         * SHOW INFO
         **/
        function showServiceConnectionFeeComputation() {
            Swal.fire({
                text : 'Service Connection Fees Computation',
                html:
                    '<img src={{ URL::asset("imgs/svc-con-fees.jpg"); }} width="100%">',
            })
        }

        function validateElectricianInfo() {
            if (isAccredited) {
                if ($('#ElectricianId').val()!='NULL') {
                    saveElectricianInfo()
                } else {
                    $('#save-payment').removeAttr('disabled')
                    Swal.fire({
                        title : 'Select Electrician First!',
                        icon : 'error'
                    })
                }                
            } else {
                if (jQuery.isEmptyObject($('#ElectricianName').val())) {
                    $('#save-payment').removeAttr('disabled')
                    Swal.fire({
                        title : 'Provide Electrician First!',
                        icon : 'error'
                    })
                } else {
                    saveElectricianInfo()
                }
            }
        }

        function saveElectricianInfo() {
            // $(this).attr('disabled', 'true')
            $('#loader').removeClass('gone')
            $.ajax({
                url : "{{ route('serviceConnections.save-electrician-info') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceConnection->id }}",
                    ElectricianId : $('#ElectricianId').val(),
                    ElectricianName : $('#ElectricianName').val(),
                    ElectricianAddress : $('#ElectricianAddress').val(),
                    ElectricianContactNo : $('#ElectricianContactNo').val(),
                    ElectricianAcredited : isAccredited ? 'Yes' : null,
                },
                success : function(res) {
                    saveWiringLabor()
                    saveBillDeposits()
                },
                error : function(err) {
                    $('#save-payment').removeAttr('disabled')
                    Swal.fire({
                        title : 'Error updating Electrician info',
                        icon : 'error'
                    })
                }
            })           
        }

        function saveWiringLabor() {
            $('#labor-charge-table > tbody  > tr').each(function(index, tr) { 
                var id = $(tr).attr('id')

                var totalVal = $('#' + id + 'Total').val()
                var vatVal = $('#' + id + 'VATAmount').val()
                var bohecoShare = $('#' + id + 'BOHECOIShare').val()
                var qtyVal = $('#' + id + 'Quantity').val()
                
                $.ajax({
                    url : "{{ route('serviceConnectionPayTransactions.save-wiring-labor') }}",
                    type : 'GET',
                    data : {
                        id : "{{ $serviceConnection->id }}",
                        MaterialId : id,
                        Quantity : qtyVal,
                        VAT : vatVal,
                        BOHECOIShare : bohecoShare,
                        Total : totalVal
                    },
                    success : function(res) {
                        
                    },
                    error : function(err) {
                        $('#save-payment').removeAttr('disabled')
                        Swal.fire({
                            title : 'Error saving wiring labor',
                            icon : 'error'
                        })
                    }
                })
            });
        }

        function saveBillDeposits() {
            $.ajax({
                url : "{{ route('serviceConnectionPayTransactions.save-bill-deposits') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceConnection->id }}",
                    Load : $('#Load').val(),
                    PowerFactor : $('#PowerFactor').val(),
                    DemandFactor : $('#DemandFactor').val(),
                    Hours : $('#Hours').val(),
                    AverageRate : $('#AverageRate').val(),
                    AverageTransmission : $('#AverageTransmission').val(),
                    AverageDemand : null,
                    BillDepositAmount : (getBillDepositNormal() * .12) + getBillDepositNormal()
                },
                success : function(res) {
                    saveTransaction()
                },
                error : function(err) {
                    $('#save-payment').removeAttr('disabled')
                    Swal.fire({
                        title : 'Error saving bill deposit labor',
                        icon : 'error'
                    })
                }
            })
        }

        function saveTransaction() {
            $.ajax({
                url : "{{ route('serviceConnectionPayTransactions.save-service-connection-transaction') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceConnection->id }}",
                    SubTotal : overAllSubTotal,
                    Form2307TwoPercent : twoPercentVat,
                    Form2307FivePercent : fivePercentVat,
                    TotalVat : overAllVat,
                    Total : overAllTotal,
                    ServiceConnectionFee : serviceConnectionFees,
                    BillDeposit : getBillDepositNormal(),
                    WitholdableVat : witholdableVat,
                    BOHECOShare : getBOHECOIShare(),
                    LaborCharge : isAccredited ? (bohecoShareOnly ? 0 : getTotalLaborCharge()) : 0,
                    Particulars : calculateTableColumnRaw('particulars_table', 1),
                    BOHECOShareOnly : bohecoShareOnly ? 'Yes' : null,
                },
                success : function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Payment Saved! Redirecting...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#loader').addClass('gone')

                    window.location.href = "{{ url(route('serviceConnections.show', [$serviceConnection->id])) }}"
                },
                error : function(err) {
                    $('#save-payment').removeAttr('disabled')
                    Swal.fire({
                        title : 'Error saving payables',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush
