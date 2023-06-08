<!-- Consumername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    {!! Form::text('ConsumerName', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Consumeraddress Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerAddress', 'Consumeraddress:') !!}
    {!! Form::text('ConsumerAddress', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200]) !!}
</div>

<!-- Transactionpurpose Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransactionPurpose', 'Transactionpurpose:') !!}
    {!! Form::text('TransactionPurpose', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Source', 'Source:') !!}
    {!! Form::text('Source', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Sourceid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SourceId', 'Sourceid:') !!}
    {!! Form::text('SourceId', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
</div>

<!-- Subtotal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SubTotal', 'Subtotal:') !!}
    {!! Form::number('SubTotal', null, ['class' => 'form-control']) !!}
</div>

<!-- Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('VAT', 'Vat:') !!}
    {!! Form::number('VAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Total', 'Total:') !!}
    {!! Form::number('Total', null, ['class' => 'form-control']) !!}
</div>