<!-- Scheduleid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ScheduleId', 'Scheduleid:') !!}
    {!! Form::text('ScheduleId', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60]) !!}
</div>

<!-- Disconnectorname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DisconnectorName', 'Disconnectorname:') !!}
    {!! Form::text('DisconnectorName', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

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

<!-- Accountcoordinates Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountCoordinates', 'Accountcoordinates:') !!}
    {!! Form::text('AccountCoordinates', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60]) !!}
</div>

<!-- Latitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Latitude', 'Latitude:') !!}
    {!! Form::text('Latitude', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Longitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longitude', 'Longitude:') !!}
    {!! Form::text('Longitude', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500]) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::number('NetAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Surcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Surcharge', 'Surcharge:') !!}
    {!! Form::number('Surcharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Servicefee Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceFee', 'Servicefee:') !!}
    {!! Form::number('ServiceFee', null, ['class' => 'form-control']) !!}
</div>

<!-- Others Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Others', 'Others:') !!}
    {!! Form::number('Others', null, ['class' => 'form-control']) !!}
</div>

<!-- Paidamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaidAmount', 'Paidamount:') !!}
    {!! Form::number('PaidAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush