<!-- Scheduleid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ScheduleId', 'Scheduleid:') !!}
    {!! Form::text('ScheduleId', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60]) !!}
</div>

<!-- Route Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Route', 'Route:') !!}
    {!! Form::text('Route', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Sequencefrom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SequenceFrom', 'Sequencefrom:') !!}
    {!! Form::text('SequenceFrom', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Sequenceto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SequenceTo', 'Sequenceto:') !!}
    {!! Form::text('SequenceTo', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>