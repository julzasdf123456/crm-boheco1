@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Transformer Ammortization Form</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('serviceConnections.transformer-ammortizations') }}">Transformer Ammortization Applications</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('serviceConnections.transformer-ammortizations-view', [$serviceConnection->id]) }}">{{ $serviceConnection->id }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6">
            {{-- CUSTOMER DETAILS --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Application Details</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tbody>
                            <tr>
                                <td class="text-muted">Applicant</td>
                                <td><a href="{{ route('serviceConnections.show', [$serviceConnection->id]) }}"><strong>{{ $serviceConnection->ServiceAccountName }}</strong></a></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Address</td>
                                <td>{{ ServiceConnections::getAddress($serviceConnection )}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Type</td>
                                <td>{{ $serviceConnection ->AccountType }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Load Applied</td>
                                <td>{{ $serviceConnection ->LoadCategory }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- BOM Details --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Bill of Materials Summary</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted">Material Cost</td>
                                <td class="text-right">{{ $totalTransactions->MaterialCost != null ? number_format($totalTransactions->MaterialCost, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Labor Cost</td>
                                <td class="text-right">{{ $totalTransactions->LaborCost != null ? number_format($totalTransactions->LaborCost, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Contngcy. & Handling</td>
                                <td class="text-right">{{ $totalTransactions->ContingencyCost != null ? number_format($totalTransactions->ContingencyCost, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Materials VAT (12%)</td>
                                <td class="text-right">{{ $totalTransactions->MaterialsVAT != null ? number_format($totalTransactions->MaterialsVAT, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Transformer Cost</strong></td>
                                <td class="text-right text-danger"><strong>{{ $totalTransactions->TransformerCost != null ? number_format($totalTransactions->TransformerCost, 2) : 0 }}</strong></td>
                            </tr>
                            <tr>
                                <td>Transformer VAT (12%)</td>
                                <td class="text-right text-info">{{ $totalTransactions->TransformerVAT != null ? number_format($totalTransactions->TransformerVAT, 2) : 0 }}</td>
                            </tr>
                            <tr style="border-top: 1px solid #dbdbdb;">
                                <td class="text-muted"><strong>TOTAL</strong></td>
                                <td class="text-right text-success"><strong>{{ $totalTransactions->BillOfMaterialsTotal != null ? number_format($totalTransactions->BillOfMaterialsTotal, 2) : 0 }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-6">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Ammortization Configuration and Schedule</span>

                    <div class="card-tools">
                        <button id="save-btn" class="btn btn-sm btn-primary">Save <i class="fas fa-check-circle"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label for="TransformerCost">Transformer Cost</label>
                            <input id="TransformerCost" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $totalTransactions->TransformerCost }}">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="DownPaymentRate">DP %</label>
                            <input id="DownPaymentRate" type="number" step="any" type="text" class="form-control form-control-sm text-right" value="{{ env("TRANSFORMER_DP_PERCENTAGE") }}">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="InterestRate">Interest %</label>
                            <input id="InterestRate" type="number" step="any" type="text" class="form-control form-control-sm text-right" value="{{ env("TRANSFORMER_INTEREST_PERCENTAGE") }}">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="Terms">Terms (In Months)</label>
                            <input id="Terms" type="number" type="text" class="form-control form-control-sm text-right" autofocus>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="StartingDate">Starting Date</label>
                            <input id="StartingDate" type="date" type="text" class="form-control form-control-sm text-right">

                            <button id="generate-btn" style="margin-top: 5px;" class="btn btn-sm btn-default float-right">Generate <i class="fas fa-sync"></i></button>
                        </div>

                        <div class="col-lg-12">
                            <table class="table table-borderless table-hover">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Down Payment: </td>
                                        <td class="text-right text-info"><strong id="dp-display"></strong></td>
                                        <td class="text-muted">Balance: </td>
                                        <td class="text-right text-danger"><strong id="balance-display"></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="no-pads text-muted">Ammortization Schedule</p>

                            <table class="table table-hover table-sm" id="calendar-table">
                                <thead>
                                    <th>#</th>
                                    <th>Month</th>
                                    <th class="text-right">Monthly Payment</th>
                                    <th class="text-right">Interest</th>
                                    <th class="text-right">Principal</th>
                                    <th class="text-right">Balance</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script>
        var principalTotal = 0
        var interestTotal = 0
        var overallTotal = 0
        var downPayment = 0
        var receivableBaseAmount = 0
        var interestRate = '{{ env("TRANSFORMER_INTEREST_PERCENTAGE") }}'
        interestRate = parseFloat(interestRate) / 12

        $(document).ready(function() {
            $('#generate-btn').on('click', function() {
                generateView()
            })

            $('#save-btn').on('click', function() {
                saveAmmortization()
            })
        })

        function saveAmmortization() {
            Swal.fire({
                title: 'Save Ammortization Schedule?',
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('serviceConnections.save-transformer-ammortization') }}",
                        type : 'GET',
                        data : {
                            id : "{{ $serviceConnection->id }}",
                            TransformerDownPayment : downPayment,
                            TransformerDownPaymentPercentage : $('#DownPaymentRate').val(),
                            TransformerInterestPercentage : $('#InterestRate').val(),
                            TransformerReceivablesTotal : receivableBaseAmount,
                            TransformerAmmortizationTerms : $('#Terms').val(),
                            TransformerAmmortizationStart : moment($('#StartingDate').val()).format('YYYY-MM-DD')
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Ammortization schedule set!'
                            })
                            window.location.href = "{{ url('/service_connections/print-transformer-ammortization') }}/{{ $serviceConnection->id }}"
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error setting ammortization schedule!'
                            })
                        }
                    })
                } 
            })
        }

        function generateView() {
            if (jQuery.isEmptyObject($('#Terms').val())) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Please input terms'
                })
            } else {
                if (jQuery.isEmptyObject($('#StartingDate').val())) {
                    Toast.fire({
                        icon : 'warning',
                        text : 'Please input starting date'
                    })
                } else {
                    $('#calendar-table tbody tr').remove()
                    var terms = parseInt($('#Terms').val())
                    var transformerCost = parseFloat($('#TransformerCost').val())
                    var downpaymentRate = parseFloat($('#DownPaymentRate').val())
                    // var interestRate = parseFloat($('#InterestRate').val())
                    var startingDate = moment($('#StartingDate').val()).format('YYYY-MM-DD')

                    principalTotal = 0
                    interestTotal = 0
                    overallTotal = 0
                    downPayment = transformerCost * downpaymentRate
                    receivableBaseAmount = transformerCost - downPayment
                    var receivableBaseAmountDisplay = receivableBaseAmount
                    var transformerVat = "{{ $totalTransactions->TransformerVAT }}"
                    transformerVat = parseFloat(transformerVat)

                    var baseExponentialCoefficient = interestRate / (1-1/(interestRate+1)**terms)
                    var monthlyPaymentBase = receivableBaseAmount * baseExponentialCoefficient
                    console.log(baseExponentialCoefficient + " - " + monthlyPaymentBase)
                    for (var i=0; i<terms; i++) {
                        var interest = receivableBaseAmountDisplay * interestRate
                        var principal = monthlyPaymentBase - interest
                        var balance = receivableBaseAmountDisplay - principal
                        $('#calendar-table tbody').append(addRow(
                            (i+1),
                            moment(startingDate).add(i, 'M').format('YYYY-MM-DD'),                            
                            Number(parseFloat(monthlyPaymentBase)).toLocaleString(2),
                            Number(parseFloat(principal)).toLocaleString(2),
                            Number(parseFloat(interest)).toLocaleString(2),
                            Number(parseFloat(balance)).toLocaleString(2)
                        ))

                        principalTotal += principal
                        interestTotal += interest
                        overallTotal += balance

                        receivableBaseAmountDisplay = receivableBaseAmountDisplay - principal
                    }

                    addDP(
                        Number(parseFloat(downPayment + transformerVat)).toLocaleString(2),
                        Number(parseFloat(receivableBaseAmount)).toLocaleString(2)
                    )

                    $('#calendar-table tbody').append(addTotalAmmortization(
                        Number(parseFloat(principalTotal)).toLocaleString(2),
                        Number(parseFloat(interestTotal)).toLocaleString(2),
                        Number(parseFloat(monthlyPaymentBase * terms)).toLocaleString(2)
                    ))
                }                
            }            
        }

        function addRow(i, month, monthlyPayment, principal, interest, balance) {
            return '<tr>' +
                        '<td>' + i + '</td>' +
                        '<td>' + month + '</td>' +
                        '<td class="text-right">' + monthlyPayment + '</td>' +
                        '<td class="text-right">' + interest + '</td>' +
                        '<td class="text-right">' + principal + '</td>' +
                        '<td class="text-right">' + balance + '</td>' +
                    '</tr>'
        }

        function addDP(dpAmount, balanceAmount) {
            $('#dp-display').text(dpAmount)
            $('#balance-display').text(balanceAmount)
        }

        function addTotalAmmortization(principal, interest, total) {
            return '<tr>' +
                        '<td colspan="2"><strong>Total Ammortization</strong></td>' +
                        '<td class="text-right"><strong>' + total + '</strong></td>' +
                        '<td class="text-right"><strong>' + interest + '</strong></td>' +
                        '<td class="text-right"><strong>' + principal + '</strong></td>' +
                        '<td class="text-right"></td>' +
                    '</tr>'
        }
    </script>
@endpush