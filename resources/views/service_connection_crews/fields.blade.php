<!-- Stationname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StationName', 'Station/Group Name') !!}
    {!! Form::text('StationName', null, ['class' => 'form-control','maxlength' => 140,'maxlength' => 140]) !!}
</div>

<!-- Crewleader Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CrewLeader', 'Crew/Team Leader') !!}
    {!! Form::text('CrewLeader', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Members Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Members', 'Members:') !!}
    {!! Form::textarea('Members', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500, 'rows' => 3, 'placeholder' => 'Separate by comma per name']) !!}
</div>

<!-- OFFICE -->
<div class="form-group col-sm-6">
    {!! Form::label('Office', 'Department:') !!}
    <select name="Office" class="form-control form-control-sm">
        <option value="ISD">ISD</option>
        <option value="ESD">ESD</option>
        <option value="OGM">OGM</option>
        <option value="OSD">OSD</option>
        <option value="PGD">PGD</option>
        <option value="SEEAD">SEEAD</option>
    </select>
</div>

<!-- Grouping -->
<div class="form-group col-sm-6">
    {!! Form::label('Grouping', 'Grouping:') !!}
    {!! Form::select('Grouping', ['Group' => 'Group', 'Individual' => 'Individual'], null, ['class' => 'form-control',]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes/Remarks') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>