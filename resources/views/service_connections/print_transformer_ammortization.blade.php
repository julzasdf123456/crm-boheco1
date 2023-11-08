@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
    use App\Models\Users;
@endphp

<style>
    @media print {
    @page {
        /* size: landscape !important; */
    }

    header {
        display: none;
    }

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    }

    .left-indent {
        margin-left: 30px !important;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }
    .left-indent-more {
        margin-left: 90px !important;
    }
}  

html {
    margin: 10px !important;
}

.left-indent {
    margin-left: 50px !important;
}

.left-indent-p {
    text-indent: 80px;
    text-align: justify;
    text-justify: inter-word;
}

.left-indent-more {
    margin-left: 90px !important;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.divider {
    width: 100%;
    margin: 10px auto;
    height: 1px;
    background-color: #dedede;
} 

p {
    padding: 0px !important;
    margin: 0px !important;
}

.table {
    width: 100%;
}

.table-borderless th,
.table-borderless td,
.table-borderless tr {
    border-collapse: collapse;
    border: 0;
}

.table-bordered th,
.table-bordered td,
.table-bordered tr {
    border-collapse: collapse;
    border: 1px solid #454545;
}

.table-sm th,
.table-sm td,
.table-sm tr {
    padding-top: 0px !important;
    padding-bottom: 1px !important;
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}
</style>

<link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}">

<div  class="content" style="margin: 15px;">
    <div class="row">
        <div class="col-sm-12">
            <p class="text-center" style="font-size: 1em;"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
            <p class="text-center">{{ env('APP_ADDRESS') }}</p>
            <br>
            <p class="text-center"><strong>TRANSFORMER AMMORTIZATION SCHEDULE</strong></p>
            <br>
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Name: </td>
                    <td><strong>{{ $serviceConnection->ServiceAccountName }}</strong></td>
                    <td>Amount: </td>
                    <td><strong>{{ number_format($totalTransactions->TransformerReceivablesTotal, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Type: </td>
                    <td><strong>{{ $serviceConnection->LoadCategory }} kVA</strong> Transformer</td>
                    <td>Interest Per Anum: </td>
                    <td><strong>{{ number_format($totalTransactions->TransformerInterestPercentage * 100) }}</strong> %</td>
                </tr>
                <tr>
                    <td>Date: </td>
                    <td><strong>{{ date('F d, Y') }}</strong></td>
                    <td>Term of Loan (in Years): </td>
                    <td><strong>{{ number_format($totalTransactions->TransformerAmmortizationTerms / 12, 1) }}</strong> Years</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>No. of Installment (in Months): </td>
                    <td><strong>{{ number_format($totalTransactions->TransformerAmmortizationTerms) }}</strong> Months</td>
                </tr>
            </table>

            <table class="table table-bordered table-sm">
                <thead>
                    <tr>                        
                        <th rowspan="2" class="text-center">Count</th>
                        <th rowspan="2" class="text-center">Month</th>
                        <th colspan="3" class="text-center">Monthly</th>
                        <th rowspan="2" class="text-center">Outstanding Balance</th>
                    </tr>
                    <tr>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Interest</th>
                        <th class="text-center">Principal</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($totalTransactions != null)
                        @php
                            $totalReceivable = $totalTransactions->TransformerReceivablesTotal;
                            $interestRate = $totalTransactions->TransformerInterestPercentage / 12;

                            $exponentialCoefficient = $interestRate / (1-1/pow(($interestRate+1), $totalTransactions->TransformerAmmortizationTerms));
                            $monthlyBase = $totalTransactions->TransformerReceivablesTotal * $exponentialCoefficient;
                        @endphp
                        @for ($i = 0; $i < $totalTransactions->TransformerAmmortizationTerms; $i++)
                            @php                    
                                $interest = $totalReceivable * $interestRate;
                                $principal = $monthlyBase - $interest;
                                $balance = $totalReceivable - $principal;
                            @endphp
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ date('F d, Y', strtotime($totalTransactions->TransformerAmmortizationStart . ' +' . $i . ' months')) }}</td>
                                <td class="text-right"><strong>{{ number_format($monthlyBase, 2) }}</strong></td>
                                <td class="text-right">{{ number_format($interest, 2) }}</td>
                                <td class="text-right">{{ number_format($principal, 2) }}</td>
                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                            </tr>
                            @php
                                $totalReceivable = $totalReceivable - $principal;
                            @endphp
                        @endfor
                    @endif    
                </tbody>
            </table>
        </div>

        <div class="col-sm-4">
            <br>
            <p>Prepared By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ env('OSD_ACCOUNTANT') }}</strong></p>
            <p class="text-center">Accountant</p>
        </div>

        <div class="col-sm-4">
            <br>
            <p>Verified By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ env('OSD_CHIEF') }}</strong></p>
            <p class="text-center">Chief, Finance Division</p>
        </div>

        <div class="col-sm-4">
            <br>
            <p>Conforme/Received By:</p>
            <br>
            <br>
            <p class="text-center"><u><strong>{{ strtoupper($serviceConnection->ServiceAccountName) }}</strong></u></p>
            <p class="text-center">Name over Signature</p>
        </div>

        <div class="col-sm-4">
            <br>
            <br>
            <p>Noted By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ env('OSD_MANAGER') }}</strong></p>
            <p class="text-center">OSD Manager</p>
        </div>

        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            {{-- <p class="text-right">( 3 copies, for MCO, Billing and Accounting)</p> --}}
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.location.href = "{{ url('/serviceConnections') }}/{{ $serviceConnection->id }}"
    }, 800);
</script>