<!-- Referenceno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReferenceNo', 'Referenceno:') !!}
    {!! Form::text('ReferenceNo', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
</div>

<!-- Particular Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Particular', 'Particular:') !!}
    {!! Form::text('Particular', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Glcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GLCode', 'Glcode:') !!}
    {!! Form::text('GLCode', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
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