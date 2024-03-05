
@php
    use App\Models\ServiceConnections;
    use App\Models\Users;

    if ($serviceConnectionInspections != null) {
        $inspector = Users::find($serviceConnectionInspections->Inspector);
    } else {
        $inspector = null;
    }
@endphp

<style>
@media print {
    p {
        margin: 0px !important;
    }

    html, body {
        margin: 0px !important;
        padding: 0px !important;
        font-family: sans-serif;
    }

    .no-line-spacing {
		padding-top: 0px;
		padding-bottom: 0px;
		margin: 0;
	}

	.checkbox {
		display: inline-block;
		margin-left: 15px;
		margin-right: 5px;
	}

	.clabel {
		margin-left: 22px !important;
		padding-top: 2px;
	}

	.cbox {
		position: absolute;
		border: 1px solid;
		border-color: #232323;
		height: 16px;
		width: 16px;
	}

	.cbox-fill {
		border: 6px solid;
		border-color: #232323;
		position: absolute;
		height: 8px;
		width: 8px;
	}

    .underlined {
		width: 100%;
		border-bottom: 1px solid;
		border-color: #232323;
		margin-top: -1px;
	}

	.underlined-dotted {
		width: 100%;
		border-bottom: 1px dotted;
		border-color: #232323;
		margin-top: -1px;
	}

    .row {
        width: 100%;
        margin: 0px;
        padding: 0px;
        display: table;
    }

    .col-md-2 {
        width: 16.3%;
        display: inline-table;
    }

    .col-md-10 {
        width: 83.2%;
        display: inline-table;
    }

    .col-md-6 {
        width: 49.98%;
        display: inline-table;
    }

    .col-md-4 {
        width: 33.30%;
        display: inline-table;
    }

    .col-md-8 {
        width: 66.62%;
        display: inline-table;
    }

    .col-md-12 {
        width: 99.98%;
        display: inline-table;
    }

    .col-md-5 {
        width: 41.63%;
        display: inline-table;
    }

    .col-md-7 {
        width: 58.29%;
        display: inline-table;
    }

    .center-text {
        text-align: center;
    }
}
</style>

<div>
	<div class="header-print">
		<div class="row">
			<div class="col-md-2">
                <img src="{{ URL::asset('imgs/company_logo.png'); }}" style="height: 80px; float: left;" alt="Image"> 
			</div>
			<div class="col-md-10" style="float: right">
				<h4 class="no-line-spacing"><strong>{{ env('APP_COMPANY') }}</strong></h4>
				<p class="no-line-spacing">{{ env('APP_ADDRESS') }}</p>
				<p class="no-line-spacing"><strong>INSTITUTIONAL SERVICES DEPARTMENT</strong></p>
				<p class="no-line-spacing">CONSUMER SERVICES AND POWER USE DIVISION</p>
			</div>
		</div>

		<br>

		<h3 class="center-text" style="margin-bottom: 15px;"><u><strong>TURN ON ORDER</strong></u></h3>

        <br>

        <div class="row">
            <!-- DATE TO OR NUMBER -->
            <div class="col-md-6">
                <div class="col-md-4">
                    <p class="no-line-spacing">DATE</p>	
                    <p class="no-line-spacing">ACCOUNT NO.</p>					
                </div>
                <div class="col-md-8">
                    <p class="underlined no-line-spacing">: {{ date('F d, Y') }}</p>
                    <p class="underlined no-line-spacing">: </p>	
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <p class="no-line-spacing"><strong>TURN ON NO.</strong></p>	
                    <p class="no-line-spacing"><strong>OR NUMBER</strong></p>					
                </div>
                <div class="col-md-8">
                    <p class="underlined no-line-spacing">: <strong>{{ $serviceConnection->id }}</strong></p>
                    <p class="underlined no-line-spacing">: <strong>{{ $serviceConnection->ORNumber }}</strong></p>	
                </div>
            </div>
            <!-- NAME AND ADDRESS -->
            <div class="col-md-12">
                <div class="col-md-2">
                    <p class="no-line-spacing"><strong>NAME</strong></p>
                    <p class="no-line-spacing">ADDRESS</p>
                </div>
                <div class="col-md-10">
                    <p class="underlined no-line-spacing">: <strong>{{ $serviceConnection->ServiceAccountName }}</strong></p>
                    <p class="underlined no-line-spacing">: {{ ServiceConnections::getAddress($serviceConnection) }}</p>
                </div>
            </div>
            <!-- REQUEST TO METER SERIAL NO -->
            <div class="col-md-6">
                <div class="col-md-4">
                    <p class="no-line-spacing">REQUEST</p>	
                    <p class="no-line-spacing">L-NEIGHBOR</p>					
                    <p class="no-line-spacing">R-NEIGHBOR</p>					
                </div>
                <div class="col-md-8">
                    <p class="underlined no-line-spacing">: {{ $serviceConnection->ConnectionApplicationType }}</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnectionInspections != null ? $serviceConnectionInspections->FirstNeighborName : '' }}</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnectionInspections != null ? $serviceConnectionInspections->SecondNeighborName : '' }}</p>	
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-5">
                    <p class="no-line-spacing" style="opacity: 0;">:</p>
                    <p class="no-line-spacing">METER SERIAL NO.</p>	
                    <p class="no-line-spacing">METER SERAIL NO.</p>					
                </div>
                <div class="col-md-7">
                    <p class="no-line-spacing" style="opacity: 0;">:</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnectionInspections != null ? $serviceConnectionInspections->FirstNeighborMeterSerial : '' }}</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnectionInspections != null ? $serviceConnectionInspections->SecondNeighborMeterSerial : '' }}</p>	
                </div>
            </div>
            <!-- POLE TO CONSUMER'S TYPE -->
            <div class="col-md-12 row">
                <div class="col-md-2">
                    <p class="no-line-spacing">POLE NO.</p>
                    <p class="no-line-spacing">SDW LENGTH</p>
                    <p class="no-line-spacing">CONS. TYPE</p>
                    <p class="no-line-spacing"><strong>ELECTRICIAN</strong></p>
                    <p class="no-line-spacing"><strong>VERIFIER</strong></p>
                </div>
                <div class="col-md-10">
                    <p class="underlined no-line-spacing">: -</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnectionInspections != null ? $serviceConnectionInspections->SDWLengthAsInstalled . 'meters (Size: ' . $serviceConnectionInspections->SDWSizeAsInstalled . ')' : '' }}</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnection->AccountType }}</p>
                    <p class="underlined no-line-spacing">: {{ $serviceConnection->ElectricianName }}</p>
                    <p class="underlined no-line-spacing">: {{ $inspector != null ? $inspector->name : '-' }}</p>
                    <p class="no-line-spacing" style="opacity: 0; margin-bottom: -10px;">.</p>
                    <div>
                        <div class="checkbox">
                            <p class="{{ $serviceConnection->AccountOrganization=='Individual' ? 'cbox-fill' : 'cbox' }}"></p>
                            <p class="clabel">INDIVIDUAL</p>
                        </div>
                        
                        <div class="checkbox">
                            <p class="{{ $serviceConnection->AccountOrganization=='BAPA' | $serviceConnection->AccountOrganization=='ECA' ? 'cbox-fill' : 'cbox' }}"></p>
                            <p class="clabel">BAPA/ECA MEMBER</p>
                        </div>

                        <div class="checkbox">
                            <p class="cbox"></p>
                            <p class="clabel">CLUSTER</p>
                        </div>
                    </div>
                </div>
            </div>

            <p style="opacity: 0;">.</p>

            <!-- WAREHOUSE -->
            <div class="col-md-12">
                <div class="col-md-6">
                    <p><strong>Warehouse</strong></p>
                    <div class="col-md-5">
                        <p class="no-line-spacing">TYPE OF METER</p>
                        <p class="no-line-spacing">BRAND</p>
                        <p class="no-line-spacing">SERIAL NO.</p>
                        <p class="no-line-spacing">INITIAL READING</p>
                        <p class="no-line-spacing">AMPERE RATING</p>
                    </div>
                    <div class="col-md-7">
                        <p class="underlined no-line-spacing">: </p>
                        <p class="underlined no-line-spacing">: {{ $serviceConnectionMeter->MeterBrand }}</p>
                        <p class="underlined no-line-spacing">: {{ $serviceConnectionMeter->MeterSerialNumber }}</p>
                        <p class="underlined no-line-spacing">: {{ $serviceConnectionMeter->MeterKwhStart }}</p>
                        <p class="underlined no-line-spacing">: </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <p><strong>O & M (Station Crew)</strong></p>
                    <div class="col-md-5">
                        <p class="no-line-spacing">EXECUTED BY</p>
                        <p class="no-line-spacing">DATE EXECUTED</p>
                        <p class="no-line-spacing">METER SEAL NO.</p>
                        <p class="no-line-spacing">ENCODER</p>
                        <p class="no-line-spacing">DATE ENCODED</p>
                    </div>
                    <div class="col-md-7">
                        <p class="underlined no-line-spacing">: </p>
                        <p class="underlined no-line-spacing">: </p>
                        <p class="underlined no-line-spacing">: {{ $serviceConnectionMeter->MeterSealNumber }}</p>
                        <p class="underlined no-line-spacing">: {{ $received != null ? $received->name : '-' }}</p>
                        <p class="underlined no-line-spacing">: {{ date('M d, Y', strtotime($serviceConnection->DateOfApplication)) }}</p>
                    </div>
                </div>
            </div>
            
            <p style="opacity: 0;">.</p>

            <!-- REQUIREMENTS -->
            <div class="col-md-12">
                <div class="col-md-12">
                    <p><strong>Requirements</strong></p>
                    @foreach ($checklists as $item)
                        <div class="checkbox">
                            <p class="cbox"></p>
                            <p class="clabel">{{ $item->Checklist }}</p>
                        </div>
                    @endforeach
                    <!-- BELOW CHECKLIST -->
                    <div class="col-md-4" style="margin-top: 10px;">
                        <p class="no-line-spacing"><strong>EVALUATOR'S NAME</strong></p>
                        <p class="no-line-spacing">REMARKS</p>
                    </div>
                    <div class="col-md-8">
                        <p class="underlined no-line-spacing">:</p>
                        <p class="underlined no-line-spacing">:</p>
                    </div>
                </div>
            </div>

            <!-- APPROVED BY -->
            <div class="col-md-12" style="margin-top: 30px;">
                <div class="col-md-8">
                    <br><br><br>
                    <p>Applicant's Signature: ________________</p>
                </div>
                <div class="col-md-4">
                    <p class="center-text">Approved By:</p>
                    <br>
                    <br>
                    <p class="center-text underlined"><strong>ELMER SALUS B. POZON</strong></p>
                    <p class="no-line-spacing center-text"><i>Manager, ISD</i></p>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="col-md-12">
                <br>
                <br>
                <p><i>This is to certify that the KWHr-meter was sealed when installed.</i></p>

                <!-- RECEIVING -->
                <div class="col-md-2">
                    <p class="no-line-spacing"><i>Warehouse</i></p>
                    <p class="no-line-spacing"><i>O & M</i></p>
                    <p class="no-line-spacing"><i>Planning GPS</i></p>
                    <p class="no-line-spacing"><i>Billing</i></p>
                </div>
                <div class="col-md-2">
                    <p class="no-line-spacing underlined">: </p>
                    <p class="no-line-spacing underlined">: </p>
                    <p class="no-line-spacing underlined">: </p>
                    <p class="no-line-spacing underlined">: </p>
                </div>
            </div>
        </div>
			
	</div>

</div>

<script type="text/javascript">   
window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 1000);   
</script>
