<!-- Disconnectorname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DisconnectorName', 'Disconnectorname:') !!}
    {!! Form::text('DisconnectorName', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Disconnectorid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DisconnectorId', 'Disconnectorid:') !!}
    {!! Form::text('DisconnectorId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Day Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Day', 'Day:') !!}
    {!! Form::text('Day', null, ['class' => 'form-control','id'=>'Day']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Day').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Serviceperiodend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriodEnd', 'Serviceperiodend:') !!}
    {!! Form::text('ServicePeriodEnd', null, ['class' => 'form-control','id'=>'ServicePeriodEnd']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriodEnd').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Routes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Routes', 'Routes:') !!}
    {!! Form::text('Routes', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
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

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Datetimedownloaded Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DatetimeDownloaded', 'Datetimedownloaded:') !!}
    {!! Form::text('DatetimeDownloaded', null, ['class' => 'form-control','id'=>'DatetimeDownloaded']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DatetimeDownloaded').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Phonemodel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PhoneModel', 'Phonemodel:') !!}
    {!! Form::text('PhoneModel', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>