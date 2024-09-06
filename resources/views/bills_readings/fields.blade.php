<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
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
    {!! Form::text('ReadBy', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
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
    {!! Form::text('Remarks', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>