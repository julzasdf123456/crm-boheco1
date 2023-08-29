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

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Route Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Route', 'Route:') !!}
    {!! Form::text('Route', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Sequencenumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SequenceNumber', 'Sequencenumber:') !!}
    {!! Form::number('SequenceNumber', null, ['class' => 'form-control']) !!}
</div>

<!-- Consumername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    {!! Form::text('ConsumerName', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Consumeraddress Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerAddress', 'Consumeraddress:') !!}
    {!! Form::text('ConsumerAddress', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Meternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    {!! Form::text('MeterNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Previousreading2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PreviousReading2', 'Previousreading2:') !!}
    {!! Form::number('PreviousReading2', null, ['class' => 'form-control']) !!}
</div>

<!-- Previousreading1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PreviousReading1', 'Previousreading1:') !!}
    {!! Form::number('PreviousReading1', null, ['class' => 'form-control']) !!}
</div>

<!-- Previousreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PreviousReading', 'Previousreading:') !!}
    {!! Form::number('PreviousReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Readingdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadingDate', 'Readingdate:') !!}
    {!! Form::text('ReadingDate', null, ['class' => 'form-control','id'=>'ReadingDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ReadingDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Readby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadBy', 'Readby:') !!}
    {!! Form::text('ReadBy', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Powerreadings Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PowerReadings', 'Powerreadings:') !!}
    {!! Form::number('PowerReadings', null, ['class' => 'form-control']) !!}
</div>

<!-- Demandreadings Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandReadings', 'Demandreadings:') !!}
    {!! Form::number('DemandReadings', null, ['class' => 'form-control']) !!}
</div>

<!-- Fieldfindings Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FieldFindings', 'Fieldfindings:') !!}
    {!! Form::text('FieldFindings', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Misscodes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissCodes', 'Misscodes:') !!}
    {!! Form::text('MissCodes', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Remarks', 'Remarks:') !!}
    {!! Form::text('Remarks', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
</div>

<!-- Updatestatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UpdateStatus', 'Updatestatus:') !!}
    {!! Form::text('UpdateStatus', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Consumertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    {!! Form::text('ConsumerType', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Accountstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountStatus', 'Accountstatus:') !!}
    {!! Form::text('AccountStatus', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Shortaccountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ShortAccountNumber', 'Shortaccountnumber:') !!}
    {!! Form::text('ShortAccountNumber', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Multiplier Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    {!! Form::number('Multiplier', null, ['class' => 'form-control']) !!}
</div>

<!-- Meterdigits Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterDigits', 'Meterdigits:') !!}
    {!! Form::number('MeterDigits', null, ['class' => 'form-control']) !!}
</div>

<!-- Coreloss Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Coreloss', 'Coreloss:') !!}
    {!! Form::number('Coreloss', null, ['class' => 'form-control']) !!}
</div>

<!-- Corelosskwhlimit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CorelossKWHLimit', 'Corelosskwhlimit:') !!}
    {!! Form::number('CorelossKWHLimit', null, ['class' => 'form-control']) !!}
</div>

<!-- Additionalkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalKWH', 'Additionalkwh:') !!}
    {!! Form::number('AdditionalKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Tsfrental Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TSFRental', 'Tsfrental:') !!}
    {!! Form::number('TSFRental', null, ['class' => 'form-control']) !!}
</div>

<!-- Schooltag Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SchoolTag', 'Schooltag:') !!}
    {!! Form::text('SchoolTag', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>