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

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Lifelinelevel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LifelineLevel', 'Lifelinelevel:') !!}
    {!! Form::number('LifelineLevel', null, ['class' => 'form-control']) !!}
</div>

<!-- Generationsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationSystemCharge', 'Generationsystemcharge:') !!}
    {!! Form::number('GenerationSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Fbhccharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FBHCCharge', 'Fbhccharge:') !!}
    {!! Form::number('FBHCCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Tafppcacharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM_TAFPPCACharge', 'Acrm Tafppcacharge:') !!}
    {!! Form::number('ACRM_TAFPPCACharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Tafxacharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM_TAFxACharge', 'Acrm Tafxacharge:') !!}
    {!! Form::number('ACRM_TAFxACharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Uploadedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UploadedBy', 'Uploadedby:') !!}
    {!! Form::text('UploadedBy', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
</div>

<!-- Dateuploaded Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateUploaded', 'Dateuploaded:') !!}
    {!! Form::text('DateUploaded', null, ['class' => 'form-control','id'=>'DateUploaded']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateUploaded').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Pparefund Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPARefund', 'Pparefund:') !!}
    {!! Form::number('PPARefund', null, ['class' => 'form-control']) !!}
</div>

<!-- Seniorcitizensubsidycharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenSubsidyCharge', 'Seniorcitizensubsidycharge:') !!}
    {!! Form::number('SeniorCitizenSubsidyCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM_VAT', 'Acrm Vat:') !!}
    {!! Form::number('ACRM_VAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Daa Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DAA_VAT', 'Daa Vat:') !!}
    {!! Form::number('DAA_VAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Daa Gramcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DAA_GRAMCharge', 'Daa Gramcharge:') !!}
    {!! Form::number('DAA_GRAMCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Daa Iceracharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DAA_ICERACharge', 'Daa Iceracharge:') !!}
    {!! Form::number('DAA_ICERACharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Missionaryelectrificationcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationCharge', 'Missionaryelectrificationcharge:') !!}
    {!! Form::number('MissionaryElectrificationCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Environmentalcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EnvironmentalCharge', 'Environmentalcharge:') !!}
    {!! Form::number('EnvironmentalCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Lifelinesubsidycharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LifelineSubsidyCharge', 'Lifelinesubsidycharge:') !!}
    {!! Form::number('LifelineSubsidyCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Loancondonationcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanCondonationCharge', 'Loancondonationcharge:') !!}
    {!! Form::number('LoanCondonationCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Mandatoryratereductioncharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MandatoryRateReductionCharge', 'Mandatoryratereductioncharge:') !!}
    {!! Form::number('MandatoryRateReductionCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Mcc Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MCC', 'Mcc:') !!}
    {!! Form::number('MCC', null, ['class' => 'form-control']) !!}
</div>

<!-- Supplyretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplyRetailCustomerCharge', 'Supplyretailcustomercharge:') !!}
    {!! Form::number('SupplyRetailCustomerCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Supplysystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplySystemCharge', 'Supplysystemcharge:') !!}
    {!! Form::number('SupplySystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Meteringretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringRetailCustomerCharge', 'Meteringretailcustomercharge:') !!}
    {!! Form::number('MeteringRetailCustomerCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Meteringsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringSystemCharge', 'Meteringsystemcharge:') !!}
    {!! Form::number('MeteringSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Systemlosscharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SystemLossCharge', 'Systemlosscharge:') !!}
    {!! Form::number('SystemLossCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Crosssubsidycreditcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CrossSubsidyCreditCharge', 'Crosssubsidycreditcharge:') !!}
    {!! Form::number('CrossSubsidyCreditCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Fpcaadjustmentcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FPCAAdjustmentCharge', 'Fpcaadjustmentcharge:') !!}
    {!! Form::number('FPCAAdjustmentCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Forexadjustmentcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ForexAdjustmentCharge', 'Forexadjustmentcharge:') !!}
    {!! Form::number('ForexAdjustmentCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissiondemandcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionDemandCharge', 'Transmissiondemandcharge:') !!}
    {!! Form::number('TransmissionDemandCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissionsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionSystemCharge', 'Transmissionsystemcharge:') !!}
    {!! Form::number('TransmissionSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributiondemandcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionDemandCharge', 'Distributiondemandcharge:') !!}
    {!! Form::number('DistributionDemandCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributionsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionSystemCharge', 'Distributionsystemcharge:') !!}
    {!! Form::number('DistributionSystemCharge', null, ['class' => 'form-control']) !!}
</div>