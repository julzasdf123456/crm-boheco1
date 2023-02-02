<div class="table-responsive">
    <table class="table" id="bills-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Powerpreviousreading</th>
        <th>Powerpresentreading</th>
        <th>Demandpreviousreading</th>
        <th>Demandpresentreading</th>
        <th>Additionalkwh</th>
        <th>Additionalkwdemand</th>
        <th>Powerkwh</th>
        <th>Kwhamount</th>
        <th>Demandkw</th>
        <th>Kwamount</th>
        <th>Charges</th>
        <th>Deductions</th>
        <th>Netamount</th>
        <th>Powerrate</th>
        <th>Demandrate</th>
        <th>Billingdate</th>
        <th>Servicedatefrom</th>
        <th>Servicedateto</th>
        <th>Duedate</th>
        <th>Billnumber</th>
        <th>Remarks</th>
        <th>Averagekwh</th>
        <th>Averagekwdemand</th>
        <th>Coreloss</th>
        <th>Meter</th>
        <th>Pr</th>
        <th>Sdw</th>
        <th>Others</th>
        <th>Ppa</th>
        <th>Ppaamount</th>
        <th>Basicamount</th>
        <th>Pradiscount</th>
        <th>Praamount</th>
        <th>Ppcadiscount</th>
        <th>Ppcaamount</th>
        <th>Ucamount</th>
        <th>Meternumber</th>
        <th>Consumertype</th>
        <th>Billtype</th>
        <th>Qcamount</th>
        <th>Epamount</th>
        <th>Pcamount</th>
        <th>Loancondonation</th>
        <th>Billingperiod</th>
        <th>Unbundledtag</th>
        <th>Generationsystemamt</th>
        <th>Fbhcamt</th>
        <th>Fpcaadjustmentamt</th>
        <th>Forexadjustmentamt</th>
        <th>Transmissiondemandamt</th>
        <th>Transmissionsystemamt</th>
        <th>Distributiondemandamt</th>
        <th>Distributionsystemamt</th>
        <th>Supplyretailcustomeramt</th>
        <th>Supplysystemamt</th>
        <th>Meteringretailcustomeramt</th>
        <th>Meteringsystemamt</th>
        <th>Systemlossamt</th>
        <th>Crosssubsidycreditamt</th>
        <th>Missionaryelectrificationamt</th>
        <th>Environmentalamt</th>
        <th>Lifelinesubsidyamt</th>
        <th>Item1</th>
        <th>Item2</th>
        <th>Item3</th>
        <th>Item4</th>
        <th>Seniorcitizendiscount</th>
        <th>Seniorcitizensubsidy</th>
        <th>Ucmerefund</th>
        <th>Netprevreading</th>
        <th>Netpresreading</th>
        <th>Netpowerkwh</th>
        <th>Netgenerationamount</th>
        <th>Creditkwh</th>
        <th>Creditamount</th>
        <th>Netmeteringsystemamt</th>
        <th>Daa Gram</th>
        <th>Daa Icera</th>
        <th>Acrm Tafppca</th>
        <th>Acrm Tafxa</th>
        <th>Daa Vat</th>
        <th>Acrm Vat</th>
        <th>Netmeteringnetamount</th>
        <th>Referenceno</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bills as $bills)
            <tr>
                <td>{{ $bills->AccountNumber }}</td>
            <td>{{ $bills->PowerPreviousReading }}</td>
            <td>{{ $bills->PowerPresentReading }}</td>
            <td>{{ $bills->DemandPreviousReading }}</td>
            <td>{{ $bills->DemandPresentReading }}</td>
            <td>{{ $bills->AdditionalKWH }}</td>
            <td>{{ $bills->AdditionalKWDemand }}</td>
            <td>{{ $bills->PowerKWH }}</td>
            <td>{{ $bills->KWHAmount }}</td>
            <td>{{ $bills->DemandKW }}</td>
            <td>{{ $bills->KWAmount }}</td>
            <td>{{ $bills->Charges }}</td>
            <td>{{ $bills->Deductions }}</td>
            <td>{{ $bills->NetAmount }}</td>
            <td>{{ $bills->PowerRate }}</td>
            <td>{{ $bills->DemandRate }}</td>
            <td>{{ $bills->BillingDate }}</td>
            <td>{{ $bills->ServiceDateFrom }}</td>
            <td>{{ $bills->ServiceDateTo }}</td>
            <td>{{ $bills->DueDate }}</td>
            <td>{{ $bills->BillNumber }}</td>
            <td>{{ $bills->Remarks }}</td>
            <td>{{ $bills->AverageKWH }}</td>
            <td>{{ $bills->AverageKWDemand }}</td>
            <td>{{ $bills->CoreLoss }}</td>
            <td>{{ $bills->Meter }}</td>
            <td>{{ $bills->PR }}</td>
            <td>{{ $bills->SDW }}</td>
            <td>{{ $bills->Others }}</td>
            <td>{{ $bills->PPA }}</td>
            <td>{{ $bills->PPAAmount }}</td>
            <td>{{ $bills->BasicAmount }}</td>
            <td>{{ $bills->PRADiscount }}</td>
            <td>{{ $bills->PRAAmount }}</td>
            <td>{{ $bills->PPCADiscount }}</td>
            <td>{{ $bills->PPCAAmount }}</td>
            <td>{{ $bills->UCAmount }}</td>
            <td>{{ $bills->MeterNumber }}</td>
            <td>{{ $bills->ConsumerType }}</td>
            <td>{{ $bills->BillType }}</td>
            <td>{{ $bills->QCAmount }}</td>
            <td>{{ $bills->EPAmount }}</td>
            <td>{{ $bills->PCAmount }}</td>
            <td>{{ $bills->LoanCondonation }}</td>
            <td>{{ $bills->BillingPeriod }}</td>
            <td>{{ $bills->UnbundledTag }}</td>
            <td>{{ $bills->GenerationSystemAmt }}</td>
            <td>{{ $bills->FBHCAmt }}</td>
            <td>{{ $bills->FPCAAdjustmentAmt }}</td>
            <td>{{ $bills->ForexAdjustmentAmt }}</td>
            <td>{{ $bills->TransmissionDemandAmt }}</td>
            <td>{{ $bills->TransmissionSystemAmt }}</td>
            <td>{{ $bills->DistributionDemandAmt }}</td>
            <td>{{ $bills->DistributionSystemAmt }}</td>
            <td>{{ $bills->SupplyRetailCustomerAmt }}</td>
            <td>{{ $bills->SupplySystemAmt }}</td>
            <td>{{ $bills->MeteringRetailCustomerAmt }}</td>
            <td>{{ $bills->MeteringSystemAmt }}</td>
            <td>{{ $bills->SystemLossAmt }}</td>
            <td>{{ $bills->CrossSubsidyCreditAmt }}</td>
            <td>{{ $bills->MissionaryElectrificationAmt }}</td>
            <td>{{ $bills->EnvironmentalAmt }}</td>
            <td>{{ $bills->LifelineSubsidyAmt }}</td>
            <td>{{ $bills->Item1 }}</td>
            <td>{{ $bills->Item2 }}</td>
            <td>{{ $bills->Item3 }}</td>
            <td>{{ $bills->Item4 }}</td>
            <td>{{ $bills->SeniorCitizenDiscount }}</td>
            <td>{{ $bills->SeniorCitizenSubsidy }}</td>
            <td>{{ $bills->UCMERefund }}</td>
            <td>{{ $bills->NetPrevReading }}</td>
            <td>{{ $bills->NetPresReading }}</td>
            <td>{{ $bills->NetPowerKWH }}</td>
            <td>{{ $bills->NetGenerationAmount }}</td>
            <td>{{ $bills->CreditKWH }}</td>
            <td>{{ $bills->CreditAmount }}</td>
            <td>{{ $bills->NetMeteringSystemAmt }}</td>
            <td>{{ $bills->DAA_GRAM }}</td>
            <td>{{ $bills->DAA_ICERA }}</td>
            <td>{{ $bills->ACRM_TAFPPCA }}</td>
            <td>{{ $bills->ACRM_TAFxA }}</td>
            <td>{{ $bills->DAA_VAT }}</td>
            <td>{{ $bills->ACRM_VAT }}</td>
            <td>{{ $bills->NetMeteringNetAmount }}</td>
            <td>{{ $bills->ReferenceNo }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['bills.destroy', $bills->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bills.show', [$bills->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('bills.edit', [$bills->id]) }}"
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
