<!-- Scheduleid Field -->
<div class="col-sm-12">
    {!! Form::label('ScheduleId', 'Scheduleid:') !!}
    <p>{{ $disconnectionRoutes->ScheduleId }}</p>
</div>

<!-- Route Field -->
<div class="col-sm-12">
    {!! Form::label('Route', 'Route:') !!}
    <p>{{ $disconnectionRoutes->Route }}</p>
</div>

<!-- Sequencefrom Field -->
<div class="col-sm-12">
    {!! Form::label('SequenceFrom', 'Sequencefrom:') !!}
    <p>{{ $disconnectionRoutes->SequenceFrom }}</p>
</div>

<!-- Sequenceto Field -->
<div class="col-sm-12">
    {!! Form::label('SequenceTo', 'Sequenceto:') !!}
    <p>{{ $disconnectionRoutes->SequenceTo }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $disconnectionRoutes->Notes }}</p>
</div>

