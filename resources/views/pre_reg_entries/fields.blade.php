<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50, 'readonly' => true]) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'readonly' => true]) !!}
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
