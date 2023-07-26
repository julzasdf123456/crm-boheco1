<!-- Servername Field -->
<div class="col-sm-12">
    {!! Form::label('ServerName', 'Servername:') !!}
    <p>{{ $servers->ServerName }}</p>
</div>

<!-- Serverip Field -->
<div class="col-sm-12">
    {!! Form::label('ServerIp', 'Serverip:') !!}
    <p>{{ $servers->ServerIp }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $servers->Status }}</p>
</div>

