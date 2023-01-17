<!-- MeterNumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meter Number:') !!}
    {!! Form::text('MeterNumber', $meter != null ? $meter->MeterSerialNumber : '', ['class' => 'form-control form-control-sm','maxlength' => 20,'maxlength' => 20, 'required' => true]) !!}
</div>

<!-- Multiplier Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    {!! Form::number('Multiplier', 1, ['class' => 'form-control form-control-sm', 'required' => true]) !!}
</div>

<!-- Make Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Make', 'Make/Brand:') !!}
    {!! Form::text('Make', $meter != null ? $meter->MeterBrand : '', ['class' => 'form-control form-control-sm','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Initialreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InitialReading', 'Initialreading:') !!}
    {!! Form::number('InitialReading', 0, ['class' => 'form-control form-control-sm']) !!}
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Remarks', 'Remarks:') !!}
    {!! Form::text('Remarks', null, ['class' => 'form-control form-control-sm','maxlength' => 255,'maxlength' => 255]) !!}
</div>