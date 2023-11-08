<table class="table table-hover table-sm">
    <tbody>
        <tr>
            <td class="text-muted">Total Transformer Cost</td>
            <td class="text-right"><strong>{{ $totalTransactions != null ? number_format($totalTransactions->TransformerCost, 2) : 0 }}</strong></td>
        </tr>
        <tr>
            <td class="text-muted">Down Payment ({{ $totalTransactions != null ? number_format($totalTransactions->TransformerDownpaymentPercentage * 100) : 0 }} % + Total VAT)</td>
            <td class="text-right"><strong>{{ $totalTransactions != null ? number_format($totalTransactions->TransformerDownPayment + $totalTransactions->TransformerVAT, 2) : 0 }}</strong></td>
        </tr>
        <tr>
            <td class="text-muted">Payment Terms</td>
            <td class="text-right"><strong>{{ $totalTransactions != null ? $totalTransactions->TransformerAmmortizationTerms : 0 }}</strong> months</td>
        </tr>
        <tr>
            <td class="text-muted">Interest Rate (Per Anum)</td>
            <td class="text-right"><strong>{{ $totalTransactions != null ? number_format($totalTransactions->TransformerInterestPercentage * 100) : 0 }} %</strong></td>
        </tr>
        <tr>
            <td class="text-muted">Total Receivable</td>
            <td class="text-right text-danger"><strong>{{ $totalTransactions != null ? number_format($totalTransactions->TransformerReceivablesTotal, 2) : 0 }}</strong></td>
        </tr>
    </tbody>
</table>

<p class="text-muted">Ammortization Schedule</p>

<table class="table table-sm table-hover table-bordered">
    <thead>
        <th>#</th>
        <th>Month</th>
        <th>Payment Total</th>
        <th>Interest</th>
        <th>Principal</th>
        <th>Balance</th>
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