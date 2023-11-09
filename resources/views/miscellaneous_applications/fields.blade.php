<!-- Consumername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    {!! Form::text('ConsumerName', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
</div>

<!-- Town Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Town', 'Town:') !!}
    {!! Form::text('Town', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Barangay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Barangay', 'Barangay:') !!}
    {!! Form::text('Barangay', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Sitio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Sitio', 'Sitio:') !!}
    {!! Form::text('Sitio', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Application Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Application', 'Application:') !!}
    {!! Form::text('Application', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 3000,'maxlength' => 3000]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Servicedroplength Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDropLength', 'Servicedroplength:') !!}
    {!! Form::number('ServiceDropLength', null, ['class' => 'form-control']) !!}
</div>

<!-- Transformerload Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransformerLoad', 'Transformerload:') !!}
    {!! Form::number('TransformerLoad', null, ['class' => 'form-control']) !!}
</div>

<!-- Ticketid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TicketId', 'Ticketid:') !!}
    {!! Form::text('TicketId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Totalamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalAmount', 'Totalamount:') !!}
    {!! Form::number('TotalAmount', null, ['class' => 'form-control']) !!}
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