<!-- Accountnumber Field -->
<div class="col-sm-4">
    {!! Form::label('AccountNumber', 'Account Number:') !!}
    <p>{{ $preRegEntries->AccountNumber }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-4">
    {!! Form::label('Name', 'Name:') !!}
    <p>{{ $preRegEntries->Name }}</p>
</div>

<!-- Registeredvenue Field -->
<div class="col-sm-4">
    {!! Form::label('RegisteredVenue', 'Registered Venue:') !!}
    <p>{{ $preRegEntries->RegisteredVenue }}</p>
</div>

<!-- Dateregistered Field -->
<div class="col-sm-3">
    {!! Form::label('DateRegistered', 'Date Registered:') !!}
    <p>{{ date('F d, Y h:i:s A', strtotime($preRegEntries->DateRegistered)) }}</p>
</div>

<!-- Registrationmedium Field -->
<div class="col-sm-3">
    {!! Form::label('RegistrationMedium', 'Registration Medium:') !!}
    <p>{{ $preRegEntries->RegistrationMedium }}</p>
</div>

<!-- Contactnumber Field -->
<div class="col-sm-3">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    <p>{{ $preRegEntries->ContactNumber }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-3">
    {!! Form::label('Email', 'Email:') !!}
    <p>{{ $preRegEntries->Email }}</p>
</div>

<!-- Signature Field -->
<div class="col-sm-12">
    <img src="data:image/png;base64,{{ $preRegEntries->Signature }}" alt="" width="100%">
</div>

