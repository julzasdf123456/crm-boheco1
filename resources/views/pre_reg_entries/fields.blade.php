<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>

<!-- Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Year', 'Year:') !!}
    {!! Form::text('Year', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Registeredvenue Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RegisteredVenue', 'Registeredvenue:') !!}
    {!! Form::text('RegisteredVenue', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Dateregistered Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateRegistered', 'Dateregistered:') !!}
    {!! Form::text('DateRegistered', null, ['class' => 'form-control','id'=>'DateRegistered']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateRegistered').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 90,'maxlength' => 90]) !!}
</div>

<!-- Registrationmedium Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RegistrationMedium', 'Registrationmedium:') !!}
    {!! Form::text('RegistrationMedium', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Contactnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    {!! Form::text('ContactNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Email', 'Email:') !!}
    {!! Form::email('Email', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Signature Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('Signature', 'Signature:') !!}
    {!! Form::textarea('Signature', null, ['class' => 'form-control']) !!}
</div>