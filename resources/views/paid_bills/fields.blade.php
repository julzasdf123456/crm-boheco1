<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Billnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    {!! Form::text('BillNumber', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Serviceperiodend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriodEnd', 'Serviceperiodend:') !!}
    {!! Form::text('ServicePeriodEnd', null, ['class' => 'form-control','id'=>'ServicePeriodEnd']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriodEnd').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Power Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Power', 'Power:') !!}
    {!! Form::number('Power', null, ['class' => 'form-control']) !!}
</div>

<!-- Meter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Meter', 'Meter:') !!}
    {!! Form::number('Meter', null, ['class' => 'form-control']) !!}
</div>

<!-- Pr Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PR', 'Pr:') !!}
    {!! Form::number('PR', null, ['class' => 'form-control']) !!}
</div>

<!-- Others Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Others', 'Others:') !!}
    {!! Form::number('Others', null, ['class' => 'form-control']) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::number('NetAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Paymenttype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaymentType', 'Paymenttype:') !!}
    {!! Form::text('PaymentType', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Teller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Teller', 'Teller:') !!}
    {!! Form::text('Teller', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Dcrnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DCRNumber', 'Dcrnumber:') !!}
    {!! Form::text('DCRNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Postingdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostingDate', 'Postingdate:') !!}
    {!! Form::text('PostingDate', null, ['class' => 'form-control','id'=>'PostingDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#PostingDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Postingsequence Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostingSequence', 'Postingsequence:') !!}
    {!! Form::number('PostingSequence', null, ['class' => 'form-control']) !!}
</div>

<!-- Promptpayment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PromptPayment', 'Promptpayment:') !!}
    {!! Form::number('PromptPayment', null, ['class' => 'form-control']) !!}
</div>

<!-- Surcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Surcharge', 'Surcharge:') !!}
    {!! Form::number('Surcharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Sladjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SLAdjustment', 'Sladjustment:') !!}
    {!! Form::number('SLAdjustment', null, ['class' => 'form-control']) !!}
</div>

<!-- Otherdeduction Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherDeduction', 'Otherdeduction:') !!}
    {!! Form::number('OtherDeduction', null, ['class' => 'form-control']) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Mdrefund Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MDRefund', 'Mdrefund:') !!}
    {!! Form::number('MDRefund', null, ['class' => 'form-control']) !!}
</div>

<!-- Form2306 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Form2306', 'Form2306:') !!}
    {!! Form::text('Form2306', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Form2307 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Form2307', 'Form2307:') !!}
    {!! Form::text('Form2307', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Amount2306 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount2306', 'Amount2306:') !!}
    {!! Form::number('Amount2306', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount2307 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount2307', 'Amount2307:') !!}
    {!! Form::number('Amount2307', null, ['class' => 'form-control']) !!}
</div>

<!-- Servicefee Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceFee', 'Servicefee:') !!}
    {!! Form::number('ServiceFee', null, ['class' => 'form-control']) !!}
</div>

<!-- Others1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Others1', 'Others1:') !!}
    {!! Form::number('Others1', null, ['class' => 'form-control']) !!}
</div>

<!-- Others2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Others2', 'Others2:') !!}
    {!! Form::number('Others2', null, ['class' => 'form-control']) !!}
</div>