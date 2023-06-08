<!-- Consumername Field -->
<div class="col-sm-12">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    <p>{{ $cRMQueue->ConsumerName }}</p>
</div>

<!-- Consumeraddress Field -->
<div class="col-sm-12">
    {!! Form::label('ConsumerAddress', 'Consumeraddress:') !!}
    <p>{{ $cRMQueue->ConsumerAddress }}</p>
</div>

<!-- Transactionpurpose Field -->
<div class="col-sm-12">
    {!! Form::label('TransactionPurpose', 'Transactionpurpose:') !!}
    <p>{{ $cRMQueue->TransactionPurpose }}</p>
</div>

<!-- Source Field -->
<div class="col-sm-12">
    {!! Form::label('Source', 'Source:') !!}
    <p>{{ $cRMQueue->Source }}</p>
</div>

<!-- Sourceid Field -->
<div class="col-sm-12">
    {!! Form::label('SourceId', 'Sourceid:') !!}
    <p>{{ $cRMQueue->SourceId }}</p>
</div>

<!-- Subtotal Field -->
<div class="col-sm-12">
    {!! Form::label('SubTotal', 'Subtotal:') !!}
    <p>{{ $cRMQueue->SubTotal }}</p>
</div>

<!-- Vat Field -->
<div class="col-sm-12">
    {!! Form::label('VAT', 'Vat:') !!}
    <p>{{ $cRMQueue->VAT }}</p>
</div>

<!-- Total Field -->
<div class="col-sm-12">
    {!! Form::label('Total', 'Total:') !!}
    <p>{{ $cRMQueue->Total }}</p>
</div>

