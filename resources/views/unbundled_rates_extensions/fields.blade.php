<!-- Rowguid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rowguid', 'Rowguid:') !!}
    {!! Form::text('rowguid', null, ['class' => 'form-control']) !!}
</div>

<!-- Serviceperiodend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriodEnd', 'Serviceperiodend:') !!}
    {!! Form::text('ServicePeriodEnd', null, ['class' => 'form-control','id'=>'ServicePeriodEnd']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriodEnd').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Lcpercustomer Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LCPerCustomer', 'Lcpercustomer:') !!}
    {!! Form::number('LCPerCustomer', null, ['class' => 'form-control']) !!}
</div>

<!-- Item2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item2', 'Item2:') !!}
    {!! Form::number('Item2', null, ['class' => 'form-control']) !!}
</div>

<!-- Item3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item3', 'Item3:') !!}
    {!! Form::number('Item3', null, ['class' => 'form-control']) !!}
</div>

<!-- Item4 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item4', 'Item4:') !!}
    {!! Form::number('Item4', null, ['class' => 'form-control']) !!}
</div>

<!-- Item11 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item11', 'Item11:') !!}
    {!! Form::number('Item11', null, ['class' => 'form-control']) !!}
</div>

<!-- Item12 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item12', 'Item12:') !!}
    {!! Form::number('Item12', null, ['class' => 'form-control']) !!}
</div>

<!-- Item13 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item13', 'Item13:') !!}
    {!! Form::number('Item13', null, ['class' => 'form-control']) !!}
</div>

<!-- Item5 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item5', 'Item5:') !!}
    {!! Form::number('Item5', null, ['class' => 'form-control']) !!}
</div>

<!-- Item6 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item6', 'Item6:') !!}
    {!! Form::number('Item6', null, ['class' => 'form-control']) !!}
</div>

<!-- Item7 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item7', 'Item7:') !!}
    {!! Form::number('Item7', null, ['class' => 'form-control']) !!}
</div>

<!-- Item8 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item8', 'Item8:') !!}
    {!! Form::number('Item8', null, ['class' => 'form-control']) !!}
</div>

<!-- Item9 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item9', 'Item9:') !!}
    {!! Form::number('Item9', null, ['class' => 'form-control']) !!}
</div>

<!-- Item10 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item10', 'Item10:') !!}
    {!! Form::number('Item10', null, ['class' => 'form-control']) !!}
</div>