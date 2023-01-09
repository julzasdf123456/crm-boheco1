<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Designation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Designation', 'Designation:') !!}
    {!! Form::text('Designation', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Office Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Office', 'Office:') !!}
    {!! Form::text('Office', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Signature Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('Signature', 'Signature:') !!}
    {!! Form::textarea('Signature', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>