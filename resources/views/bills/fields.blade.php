<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Powerpreviousreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PowerPreviousReading', 'Powerpreviousreading:') !!}
    {!! Form::number('PowerPreviousReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Powerpresentreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PowerPresentReading', 'Powerpresentreading:') !!}
    {!! Form::number('PowerPresentReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Demandpreviousreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandPreviousReading', 'Demandpreviousreading:') !!}
    {!! Form::number('DemandPreviousReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Demandpresentreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandPresentReading', 'Demandpresentreading:') !!}
    {!! Form::number('DemandPresentReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Additionalkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalKWH', 'Additionalkwh:') !!}
    {!! Form::number('AdditionalKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Additionalkwdemand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalKWDemand', 'Additionalkwdemand:') !!}
    {!! Form::number('AdditionalKWDemand', null, ['class' => 'form-control']) !!}
</div>

<!-- Powerkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PowerKWH', 'Powerkwh:') !!}
    {!! Form::number('PowerKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Kwhamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KWHAmount', 'Kwhamount:') !!}
    {!! Form::number('KWHAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Demandkw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandKW', 'Demandkw:') !!}
    {!! Form::number('DemandKW', null, ['class' => 'form-control']) !!}
</div>

<!-- Kwamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KWAmount', 'Kwamount:') !!}
    {!! Form::number('KWAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Charges Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Charges', 'Charges:') !!}
    {!! Form::number('Charges', null, ['class' => 'form-control']) !!}
</div>

<!-- Deductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Deductions', 'Deductions:') !!}
    {!! Form::number('Deductions', null, ['class' => 'form-control']) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::number('NetAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Powerrate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PowerRate', 'Powerrate:') !!}
    {!! Form::number('PowerRate', null, ['class' => 'form-control']) !!}
</div>

<!-- Demandrate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandRate', 'Demandrate:') !!}
    {!! Form::number('DemandRate', null, ['class' => 'form-control']) !!}
</div>

<!-- Billingdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillingDate', 'Billingdate:') !!}
    {!! Form::text('BillingDate', null, ['class' => 'form-control','id'=>'BillingDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BillingDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Servicedatefrom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDateFrom', 'Servicedatefrom:') !!}
    {!! Form::text('ServiceDateFrom', null, ['class' => 'form-control','id'=>'ServiceDateFrom']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDateFrom').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Servicedateto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDateTo', 'Servicedateto:') !!}
    {!! Form::text('ServiceDateTo', null, ['class' => 'form-control','id'=>'ServiceDateTo']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDateTo').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Duedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DueDate', 'Duedate:') !!}
    {!! Form::text('DueDate', null, ['class' => 'form-control','id'=>'DueDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DueDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Billnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    {!! Form::text('BillNumber', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Remarks', 'Remarks:') !!}
    {!! Form::text('Remarks', null, ['class' => 'form-control','maxlength' => 128,'maxlength' => 128]) !!}
</div>

<!-- Averagekwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AverageKWH', 'Averagekwh:') !!}
    {!! Form::number('AverageKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Averagekwdemand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AverageKWDemand', 'Averagekwdemand:') !!}
    {!! Form::number('AverageKWDemand', null, ['class' => 'form-control']) !!}
</div>

<!-- Coreloss Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CoreLoss', 'Coreloss:') !!}
    {!! Form::number('CoreLoss', null, ['class' => 'form-control']) !!}
</div>

<!-- Meter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Meter', 'Meter:') !!}
    {!! Form::number('Meter', null, ['class' => 'form-control']) !!}
</div>

<!-- Pr Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PR', 'Pr:') !!}
    {!! Form::number('PR', null, ['class' => 'form-control']) !!}
</div>

<!-- Sdw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SDW', 'Sdw:') !!}
    {!! Form::number('SDW', null, ['class' => 'form-control']) !!}
</div>

<!-- Others Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Others', 'Others:') !!}
    {!! Form::number('Others', null, ['class' => 'form-control']) !!}
</div>

<!-- Ppa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPA', 'Ppa:') !!}
    {!! Form::number('PPA', null, ['class' => 'form-control']) !!}
</div>

<!-- Ppaamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPAAmount', 'Ppaamount:') !!}
    {!! Form::number('PPAAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Basicamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BasicAmount', 'Basicamount:') !!}
    {!! Form::number('BasicAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Pradiscount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PRADiscount', 'Pradiscount:') !!}
    {!! Form::number('PRADiscount', null, ['class' => 'form-control']) !!}
</div>

<!-- Praamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PRAAmount', 'Praamount:') !!}
    {!! Form::number('PRAAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Ppcadiscount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPCADiscount', 'Ppcadiscount:') !!}
    {!! Form::number('PPCADiscount', null, ['class' => 'form-control']) !!}
</div>

<!-- Ppcaamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPCAAmount', 'Ppcaamount:') !!}
    {!! Form::number('PPCAAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Ucamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UCAmount', 'Ucamount:') !!}
    {!! Form::number('UCAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Meternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    {!! Form::text('MeterNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Consumertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    {!! Form::text('ConsumerType', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Billtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillType', 'Billtype:') !!}
    {!! Form::text('BillType', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Qcamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('QCAmount', 'Qcamount:') !!}
    {!! Form::number('QCAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Epamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EPAmount', 'Epamount:') !!}
    {!! Form::number('EPAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Pcamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PCAmount', 'Pcamount:') !!}
    {!! Form::number('PCAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Loancondonation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanCondonation', 'Loancondonation:') !!}
    {!! Form::number('LoanCondonation', null, ['class' => 'form-control']) !!}
</div>

<!-- Billingperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillingPeriod', 'Billingperiod:') !!}
    {!! Form::text('BillingPeriod', null, ['class' => 'form-control','id'=>'BillingPeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BillingPeriod').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Unbundledtag Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('UnbundledTag', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('UnbundledTag', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('UnbundledTag', 'Unbundledtag', ['class' => 'form-check-label']) !!}
    </div>
</div>


<!-- Generationsystemamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationSystemAmt', 'Generationsystemamt:') !!}
    {!! Form::number('GenerationSystemAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Fbhcamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FBHCAmt', 'Fbhcamt:') !!}
    {!! Form::number('FBHCAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Fpcaadjustmentamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FPCAAdjustmentAmt', 'Fpcaadjustmentamt:') !!}
    {!! Form::number('FPCAAdjustmentAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Forexadjustmentamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ForexAdjustmentAmt', 'Forexadjustmentamt:') !!}
    {!! Form::number('ForexAdjustmentAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissiondemandamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionDemandAmt', 'Transmissiondemandamt:') !!}
    {!! Form::number('TransmissionDemandAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissionsystemamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionSystemAmt', 'Transmissionsystemamt:') !!}
    {!! Form::number('TransmissionSystemAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributiondemandamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionDemandAmt', 'Distributiondemandamt:') !!}
    {!! Form::number('DistributionDemandAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributionsystemamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionSystemAmt', 'Distributionsystemamt:') !!}
    {!! Form::number('DistributionSystemAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Supplyretailcustomeramt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplyRetailCustomerAmt', 'Supplyretailcustomeramt:') !!}
    {!! Form::number('SupplyRetailCustomerAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Supplysystemamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplySystemAmt', 'Supplysystemamt:') !!}
    {!! Form::number('SupplySystemAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Meteringretailcustomeramt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringRetailCustomerAmt', 'Meteringretailcustomeramt:') !!}
    {!! Form::number('MeteringRetailCustomerAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Meteringsystemamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringSystemAmt', 'Meteringsystemamt:') !!}
    {!! Form::number('MeteringSystemAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Systemlossamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SystemLossAmt', 'Systemlossamt:') !!}
    {!! Form::number('SystemLossAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Crosssubsidycreditamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CrossSubsidyCreditAmt', 'Crosssubsidycreditamt:') !!}
    {!! Form::number('CrossSubsidyCreditAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Missionaryelectrificationamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationAmt', 'Missionaryelectrificationamt:') !!}
    {!! Form::number('MissionaryElectrificationAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Environmentalamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EnvironmentalAmt', 'Environmentalamt:') !!}
    {!! Form::number('EnvironmentalAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Lifelinesubsidyamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LifelineSubsidyAmt', 'Lifelinesubsidyamt:') !!}
    {!! Form::number('LifelineSubsidyAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Item1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item1', 'Item1:') !!}
    {!! Form::number('Item1', null, ['class' => 'form-control']) !!}
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

<!-- Seniorcitizendiscount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenDiscount', 'Seniorcitizendiscount:') !!}
    {!! Form::number('SeniorCitizenDiscount', null, ['class' => 'form-control']) !!}
</div>

<!-- Seniorcitizensubsidy Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenSubsidy', 'Seniorcitizensubsidy:') !!}
    {!! Form::number('SeniorCitizenSubsidy', null, ['class' => 'form-control']) !!}
</div>

<!-- Ucmerefund Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UCMERefund', 'Ucmerefund:') !!}
    {!! Form::number('UCMERefund', null, ['class' => 'form-control']) !!}
</div>

<!-- Netprevreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetPrevReading', 'Netprevreading:') !!}
    {!! Form::number('NetPrevReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Netpresreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetPresReading', 'Netpresreading:') !!}
    {!! Form::number('NetPresReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Netpowerkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetPowerKWH', 'Netpowerkwh:') !!}
    {!! Form::number('NetPowerKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Netgenerationamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetGenerationAmount', 'Netgenerationamount:') !!}
    {!! Form::number('NetGenerationAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Creditkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CreditKWH', 'Creditkwh:') !!}
    {!! Form::number('CreditKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Creditamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CreditAmount', 'Creditamount:') !!}
    {!! Form::number('CreditAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Netmeteringsystemamt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetMeteringSystemAmt', 'Netmeteringsystemamt:') !!}
    {!! Form::number('NetMeteringSystemAmt', null, ['class' => 'form-control']) !!}
</div>

<!-- Daa Gram Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DAA_GRAM', 'Daa Gram:') !!}
    {!! Form::number('DAA_GRAM', null, ['class' => 'form-control']) !!}
</div>

<!-- Daa Icera Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DAA_ICERA', 'Daa Icera:') !!}
    {!! Form::number('DAA_ICERA', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Tafppca Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM_TAFPPCA', 'Acrm Tafppca:') !!}
    {!! Form::number('ACRM_TAFPPCA', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Tafxa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM_TAFxA', 'Acrm Tafxa:') !!}
    {!! Form::number('ACRM_TAFxA', null, ['class' => 'form-control']) !!}
</div>

<!-- Daa Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DAA_VAT', 'Daa Vat:') !!}
    {!! Form::number('DAA_VAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM_VAT', 'Acrm Vat:') !!}
    {!! Form::number('ACRM_VAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Netmeteringnetamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetMeteringNetAmount', 'Netmeteringnetamount:') !!}
    {!! Form::number('NetMeteringNetAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Referenceno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReferenceNo', 'Referenceno:') !!}
    {!! Form::text('ReferenceNo', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
</div>