<!-- Checklist Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Checklist', 'Checklist Item Name') !!}
    {!! Form::text('Checklist', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Notes', 'Description/Notes') !!}
    {!! Form::select('Notes', [
            'RESIDENTIAL' => 'RESIDENTIAL', 
            'NON-RESIDENTIAL BELOW 5kVA' => 'NON-RESIDENTIAL BELOW 5kVA', 
            'NON-RESIDENTIAL ABOVE 5kVA' => 'NON-RESIDENTIAL ABOVE 5kVA',
            'CHANGE NAME' => 'CHANGE NAME',
        ], null, ['class' => 'form-control form-control-sm']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('Minimum', 'Minimum Requirement') !!}
    {!! Form::select('Minimum', ['' => 'No', 'Yes' => 'Yes'], null, ['class' => 'form-control form-control-sm']) !!}
</div>