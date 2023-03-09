<!-- Town Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Town', 'Town:') !!}
    {!! Form::text('Town', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- District Field -->
<div class="form-group col-sm-4">
    {!! Form::label('District', 'District:') !!}
    {!! Form::text('District', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Station Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Station', 'Area Code (CEBCOS):') !!}
    {!! Form::text('Station', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>