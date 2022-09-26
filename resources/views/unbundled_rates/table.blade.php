<div class="table-responsive">
    <table class="table" id="unbundledRates-table">
        <thead>
        <tr>
            <th>Rowguid</th>
        <th>Serviceperiodend</th>
        <th>Description</th>
        <th>Lifelinelevel</th>
        <th>Generationsystemcharge</th>
        <th>Fbhccharge</th>
        <th>Acrm Tafppcacharge</th>
        <th>Acrm Tafxacharge</th>
        <th>Uploadedby</th>
        <th>Dateuploaded</th>
        <th>Pparefund</th>
        <th>Seniorcitizensubsidycharge</th>
        <th>Acrm Vat</th>
        <th>Daa Vat</th>
        <th>Daa Gramcharge</th>
        <th>Daa Iceracharge</th>
        <th>Missionaryelectrificationcharge</th>
        <th>Environmentalcharge</th>
        <th>Lifelinesubsidycharge</th>
        <th>Loancondonationcharge</th>
        <th>Mandatoryratereductioncharge</th>
        <th>Mcc</th>
        <th>Supplyretailcustomercharge</th>
        <th>Supplysystemcharge</th>
        <th>Meteringretailcustomercharge</th>
        <th>Meteringsystemcharge</th>
        <th>Systemlosscharge</th>
        <th>Crosssubsidycreditcharge</th>
        <th>Fpcaadjustmentcharge</th>
        <th>Forexadjustmentcharge</th>
        <th>Transmissiondemandcharge</th>
        <th>Transmissionsystemcharge</th>
        <th>Distributiondemandcharge</th>
        <th>Distributionsystemcharge</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($unbundledRates as $unbundledRates)
            <tr>
                <td>{{ $unbundledRates->rowguid }}</td>
            <td>{{ $unbundledRates->ServicePeriodEnd }}</td>
            <td>{{ $unbundledRates->Description }}</td>
            <td>{{ $unbundledRates->LifelineLevel }}</td>
            <td>{{ $unbundledRates->GenerationSystemCharge }}</td>
            <td>{{ $unbundledRates->FBHCCharge }}</td>
            <td>{{ $unbundledRates->ACRM_TAFPPCACharge }}</td>
            <td>{{ $unbundledRates->ACRM_TAFxACharge }}</td>
            <td>{{ $unbundledRates->UploadedBy }}</td>
            <td>{{ $unbundledRates->DateUploaded }}</td>
            <td>{{ $unbundledRates->PPARefund }}</td>
            <td>{{ $unbundledRates->SeniorCitizenSubsidyCharge }}</td>
            <td>{{ $unbundledRates->ACRM_VAT }}</td>
            <td>{{ $unbundledRates->DAA_VAT }}</td>
            <td>{{ $unbundledRates->DAA_GRAMCharge }}</td>
            <td>{{ $unbundledRates->DAA_ICERACharge }}</td>
            <td>{{ $unbundledRates->MissionaryElectrificationCharge }}</td>
            <td>{{ $unbundledRates->EnvironmentalCharge }}</td>
            <td>{{ $unbundledRates->LifelineSubsidyCharge }}</td>
            <td>{{ $unbundledRates->LoanCondonationCharge }}</td>
            <td>{{ $unbundledRates->MandatoryRateReductionCharge }}</td>
            <td>{{ $unbundledRates->MCC }}</td>
            <td>{{ $unbundledRates->SupplyRetailCustomerCharge }}</td>
            <td>{{ $unbundledRates->SupplySystemCharge }}</td>
            <td>{{ $unbundledRates->MeteringRetailCustomerCharge }}</td>
            <td>{{ $unbundledRates->MeteringSystemCharge }}</td>
            <td>{{ $unbundledRates->SystemLossCharge }}</td>
            <td>{{ $unbundledRates->CrossSubsidyCreditCharge }}</td>
            <td>{{ $unbundledRates->FPCAAdjustmentCharge }}</td>
            <td>{{ $unbundledRates->ForexAdjustmentCharge }}</td>
            <td>{{ $unbundledRates->TransmissionDemandCharge }}</td>
            <td>{{ $unbundledRates->TransmissionSystemCharge }}</td>
            <td>{{ $unbundledRates->DistributionDemandCharge }}</td>
            <td>{{ $unbundledRates->DistributionSystemCharge }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['unbundledRates.destroy', $unbundledRates->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('unbundledRates.show', [$unbundledRates->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('unbundledRates.edit', [$unbundledRates->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
