<!-- Serverid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServerId', 'Serverid:') !!}
    {!! Form::text('ServerId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Cpupercentage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CpuPercentage', 'Cpupercentage:') !!}
    {!! Form::number('CpuPercentage', null, ['class' => 'form-control']) !!}
</div>

<!-- Memorypercentage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MemoryPercentage', 'Memorypercentage:') !!}
    {!! Form::number('MemoryPercentage', null, ['class' => 'form-control']) !!}
</div>

<!-- Diskpercentage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DiskPercentage', 'Diskpercentage:') !!}
    {!! Form::number('DiskPercentage', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalmemory Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalMemory', 'Totalmemory:') !!}
    {!! Form::number('TotalMemory', null, ['class' => 'form-control']) !!}
</div>