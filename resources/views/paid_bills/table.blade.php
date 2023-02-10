<div class="table-responsive">
    <table class="table" id="paidBills-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Billnumber</th>
        <th>Serviceperiodend</th>
        <th>Power</th>
        <th>Meter</th>
        <th>Pr</th>
        <th>Others</th>
        <th>Netamount</th>
        <th>Paymenttype</th>
        <th>Ornumber</th>
        <th>Teller</th>
        <th>Dcrnumber</th>
        <th>Postingdate</th>
        <th>Postingsequence</th>
        <th>Promptpayment</th>
        <th>Surcharge</th>
        <th>Sladjustment</th>
        <th>Otherdeduction</th>
        <th>Ordate</th>
        <th>Mdrefund</th>
        <th>Form2306</th>
        <th>Form2307</th>
        <th>Amount2306</th>
        <th>Amount2307</th>
        <th>Servicefee</th>
        <th>Others1</th>
        <th>Others2</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paidBills as $paidBills)
            <tr>
                <td>{{ $paidBills->AccountNumber }}</td>
            <td>{{ $paidBills->BillNumber }}</td>
            <td>{{ $paidBills->ServicePeriodEnd }}</td>
            <td>{{ $paidBills->Power }}</td>
            <td>{{ $paidBills->Meter }}</td>
            <td>{{ $paidBills->PR }}</td>
            <td>{{ $paidBills->Others }}</td>
            <td>{{ $paidBills->NetAmount }}</td>
            <td>{{ $paidBills->PaymentType }}</td>
            <td>{{ $paidBills->ORNumber }}</td>
            <td>{{ $paidBills->Teller }}</td>
            <td>{{ $paidBills->DCRNumber }}</td>
            <td>{{ $paidBills->PostingDate }}</td>
            <td>{{ $paidBills->PostingSequence }}</td>
            <td>{{ $paidBills->PromptPayment }}</td>
            <td>{{ $paidBills->Surcharge }}</td>
            <td>{{ $paidBills->SLAdjustment }}</td>
            <td>{{ $paidBills->OtherDeduction }}</td>
            <td>{{ $paidBills->ORDate }}</td>
            <td>{{ $paidBills->MDRefund }}</td>
            <td>{{ $paidBills->Form2306 }}</td>
            <td>{{ $paidBills->Form2307 }}</td>
            <td>{{ $paidBills->Amount2306 }}</td>
            <td>{{ $paidBills->Amount2307 }}</td>
            <td>{{ $paidBills->ServiceFee }}</td>
            <td>{{ $paidBills->Others1 }}</td>
            <td>{{ $paidBills->Others2 }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['paidBills.destroy', $paidBills->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('paidBills.show', [$paidBills->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('paidBills.edit', [$paidBills->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
