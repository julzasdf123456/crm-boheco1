<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $billsReadings->AccountNumber }}</p>
</div>

<!-- Readingdate Field -->
<div class="col-sm-12">
    {!! Form::label('ReadingDate', 'Readingdate:') !!}
    <p>{{ $billsReadings->ReadingDate }}</p>
</div>

<!-- Readby Field -->
<div class="col-sm-12">
    {!! Form::label('ReadBy', 'Readby:') !!}
    <p>{{ $billsReadings->ReadBy }}</p>
</div>

<!-- Powerreadings Field -->
<div class="col-sm-12">
    {!! Form::label('PowerReadings', 'Powerreadings:') !!}
    <p>{{ $billsReadings->PowerReadings }}</p>
</div>

<!-- Demandreadings Field -->
<div class="col-sm-12">
    {!! Form::label('DemandReadings', 'Demandreadings:') !!}
    <p>{{ $billsReadings->DemandReadings }}</p>
</div>

<!-- Fieldfindings Field -->
<div class="col-sm-12">
    {!! Form::label('FieldFindings', 'Fieldfindings:') !!}
    <p>{{ $billsReadings->FieldFindings }}</p>
</div>

<!-- Misscodes Field -->
<div class="col-sm-12">
    {!! Form::label('MissCodes', 'Misscodes:') !!}
    <p>{{ $billsReadings->MissCodes }}</p>
</div>

<!-- Remarks Field -->
<div class="col-sm-12">
    {!! Form::label('Remarks', 'Remarks:') !!}
    <p>{{ $billsReadings->Remarks }}</p>
</div>

