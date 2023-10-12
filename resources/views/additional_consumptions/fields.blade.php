<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Additionalkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalKWH', 'Additionalkwh:') !!}
    {!! Form::number('AdditionalKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Additionalkw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalKW', 'Additionalkw:') !!}
    {!! Form::number('AdditionalKW', null, ['class' => 'form-control']) !!}
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Remarks', 'Remarks:') !!}
    {!! Form::text('Remarks', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>