<!-- Servername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServerName', 'Servername:') !!}
    {!! Form::text('ServerName', null, ['class' => 'form-control','maxlength' => 90,'maxlength' => 90]) !!}
</div>

<!-- Serverip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServerIp', 'Serverip:') !!}
    {!! Form::text('ServerIp', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>