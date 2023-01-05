<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>

<!-- Parentticket Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ParentTicket', 'Parent Ticket:') !!}
    {!! Form::select('ParentTicket', $parentReps, $ticketsRepository->ParentTicket, ['class' => 'form-control', 'placeholder' => 'This is a parent ticket']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Type', 'Type:') !!}
    {!! Form::select('Type', ['Request' => 'Request', 'Complain' => 'Complain'], $ticketsRepository->Type, ['class' => 'form-control', 'placeholder' => 'This is a parent ticket']) !!}
</div>

<!-- Kpscategory Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KPSCategory', 'NEA Reporting Category:') !!}
    {!! Form::text('KPSCategory', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- KPSHourlyCategory Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KPSHourlyCategory', 'KPS Category:') !!}
    {!! Form::text('KPSHourlyCategory', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<input type="hidden" value="2021" name="KPSIssue">