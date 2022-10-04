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
    {!! Form::label('Station', 'Station:') !!}
    <select name="Station" id="Station" class="form-control">
        @foreach ($crews as $item)
            <option value="{{ $item->id }}">{{ $item->StationName }}</option>
        @endforeach
    </select>
</div>