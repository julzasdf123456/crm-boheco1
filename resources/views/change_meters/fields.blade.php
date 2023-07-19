<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Changedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ChangeDate', 'Changedate:') !!}
    {!! Form::text('ChangeDate', null, ['class' => 'form-control','id'=>'ChangeDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ChangeDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Oldmeter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeter', 'Oldmeter:') !!}
    {!! Form::text('OldMeter', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Newmeter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeter', 'Newmeter:') !!}
    {!! Form::text('NewMeter', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Pulloutreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PullOutReading', 'Pulloutreading:') !!}
    {!! Form::number('PullOutReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Replaceby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReplaceBy', 'Replaceby:') !!}
    {!! Form::text('ReplaceBy', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Remarks', 'Remarks:') !!}
    {!! Form::text('Remarks', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>