<!-- Serverid Field -->
<div class="col-sm-12">
    {!! Form::label('ServerId', 'Serverid:') !!}
    <p>{{ $serverStats->ServerId }}</p>
</div>

<!-- Cpupercentage Field -->
<div class="col-sm-12">
    {!! Form::label('CpuPercentage', 'Cpupercentage:') !!}
    <p>{{ $serverStats->CpuPercentage }}</p>
</div>

<!-- Memorypercentage Field -->
<div class="col-sm-12">
    {!! Form::label('MemoryPercentage', 'Memorypercentage:') !!}
    <p>{{ $serverStats->MemoryPercentage }}</p>
</div>

<!-- Diskpercentage Field -->
<div class="col-sm-12">
    {!! Form::label('DiskPercentage', 'Diskpercentage:') !!}
    <p>{{ $serverStats->DiskPercentage }}</p>
</div>

<!-- Totalmemory Field -->
<div class="col-sm-12">
    {!! Form::label('TotalMemory', 'Totalmemory:') !!}
    <p>{{ $serverStats->TotalMemory }}</p>
</div>

