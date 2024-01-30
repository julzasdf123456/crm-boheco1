
@php
   use App\Models\ServiceConnections;
   use App\Models\Users;
   use Illuminate\Support\Facades\Auth;
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
        font-size: 1.08em;
    }

    td, th {
      font-size: 1.05em;
    }
    
    table {
      border-collapse: collapse;
    }

    .border {
      border: 1px solid #232323;
      padding: 5px;
    }

    .border-side {
      border-left: 1px solid #232323;
      border-right: 1px solid #232323;
      padding: 5px;
    }

    .border-top {
      border-left: 1px solid #232323;
      border-right: 1px solid #232323;
      border-top: 1px solid #232323;
      padding: 5px;
    }

    .border-bottom {
      border-left: 1px solid #232323;
      border-right: 1px solid #232323;
      border-bottom: 1px solid #232323;
      padding: 5px;
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
        width: 49.5%;
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

    .right-text {
        text-align: right;
    }

    .left-text {
        text-align: left;
    }
}
</style>

<div>
	<div class="header-print">
		<div class="row">
			<div class="col-md-2">
                <img src="{{ URL::asset('imgs/company_logo.png'); }}" style="height: 50px; left: 140px; position: fixed;" alt="Image"> 
			</div>
			<div class="col-md-12">
				<h4 class="no-line-spacing center-text"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></h4>
				<p class="no-line-spacing center-text">{{ env('APP_ADDRESS') }}</p>
                
                <span style="position: fixed; top: 10px; right: 10px;">{!! QrCode::size(56)->generate($serviceConnections->id) !!}</span>
			</div>
		</div>

		<br>

		<h3 class="center-text no-line-spacing " style="margin-bottom: 15px;"><strong>Q U O T A T I O N</strong></h3>

      <div class="col-md-12">
         <p class="no-line-spacing right-text">{{ $serviceConnections->id }}</p>
         <p class="no-line-spacing right-text">{{ date('F d, Y') }}</p>
      </div>

      <div class="col-md-12">
         <p class="no-line-spacing left-text">FOR: <strong style="margin-left: 50px;">{{ $serviceConnections->ServiceAccountName }}</strong></p>
         <p class="no-line-spacing left-text">ADDRESS : <strong style="margin-left: 5px;">{{ ServiceConnections::getAddress($serviceConnections) }}</strong></p>
      </div>

      <div class="col-md-12">
         <table style="width: 100%; margin-top: 2px;">
            <tr>
               <th class="border">ITEM</th>
               <th class="border">DESCRIPTION</th>
               <th class="border">AMOUNT</th>
               <th class="border">12%<br>EVAT</th>
               <th class="border">TOTAL<br>AMOUNT</th>
            </tr>
            <tr>
               <td class="border center-text">I</td>
               <td class="border">ENERGY DEPOSIT (AR Only) *</td>
               <td class="border right-text">{{is_numeric($totalTransactions->BillDeposit) ? number_format($totalTransactions->BillDeposit, 2) : '0.00' }}</td>
               <td class="border right-text">0</td>
               <td class="border right-text">{{is_numeric($totalTransactions->BillDeposit) ? number_format(floatval($totalTransactions->BillDeposit), 2) : '0.00' }}</td>
            </tr>
            @php
               $deposit = is_numeric($totalTransactions->BillDeposit) ? floatval($totalTransactions->BillDeposit) : 0;
               $depositVat = 0;
               $depositTotal = $deposit + $depositVat;

               $remittance = (floatval($totalTransactions->LaborCharge) + floatval($totalTransactions->BOHECOShare));
               $vat = (floatval($totalTransactions->LaborCharge) + floatval($totalTransactions->BOHECOShare)) * .12;
            @endphp
            <tr>
               <td class="border-side center-text">II</td>
               <td class="border-side">WIRING REMITTANCE **</td>
               <td class="border-side right-text">{{ number_format($remittance, 2) }}</td>
               <td class="border-side right-text">{{ number_format($vat, 2) }}</td>
               <td class="border-side right-text">{{ number_format($remittance + $vat, 2) }}</td>
            </tr>
            @php
                $others = 0;
                $othersVat = 0;
                $othersTotal = 0;
                $remittanceTotal = $remittance + $vat;

                $i = 3;
            @endphp
            @foreach ($particularPayments as $item)
               <tr>
                  <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i) }}</td>
                  <td class="border-side">{{ strtoupper($item->Particular) }}</td>
                  <td class="border-side right-text">{{ number_format($item->Amount, 2) }}</td>
                  <td class="border-side right-text">{{ number_format(floatval($item->Amount) * .12, 2) }}</td>
                  <td class="border-side right-text">{{ number_format(floatval($item->Amount) + (floatval($item->Amount) * .12), 2) }}</td>
               </tr>
               @php
                  $i++;
                  $others += floatval($item->Amount);
                  $othersVat += floatval($item->Amount) * .12;
                  $othersTotal += floatval($item->Amount) + (floatval($item->Amount) * .12);
               @endphp
            @endforeach

            {{-- INSTALLATION FEE --}}
            @php
                $materialsVat = $totalTransactions != null ? ($totalTransactions->MaterialsVAT) : 0; 
                $instBalanceTotal = 0;
            @endphp
            {{-- IF HAS INSTALLATION FEE PARTIAL/DOWNPAYMENT --}}
            @if ($totalTransactions != null && $totalTransactions->InstallationPartial != null && $totalTransactions->InstallationPartial > 0) 
                <tr>
                    <td class="border-top center-text">{{ ServiceConnections::numberToRomanRepresentation($i) }}</td>
                    <td class="border-top">* MATERIALS & INST. FEES</td>
                    <td class="border-top right-text">* {{ number_format($totalTransactions->MaterialCost, 2) }}</td>
                    <td class="border-top right-text">{{ 0 }}</td>
                    <td class="border-top right-text">{{ 0 }}</td>
                </tr>
                <tr>
                    @php
                        $contCost = $totalTransactions->LaborCost + $totalTransactions->ContingencyCost;
                    @endphp
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+1) }}</td>
                    <td class="border-side">* LABOR & CONTINGENCIES</td>
                    <td class="border-side right-text">* {{ number_format($contCost, 2) }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                </tr>
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+2) }}</td>
                    <td class="border-side">* MATERIALS VAT</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">* {{ number_format($totalTransactions->MaterialsVAT, 2) }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                </tr>
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+3) }}</td>
                    <td class="border-side">INST. FEE DOWNPAYMENT ({{ round($totalTransactions->InstallationFeeDownPaymentPercentage * 100) }}%)</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->InstallationPartial - $totalTransactions->MaterialsVAT, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->MaterialsVAT, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->InstallationPartial, 2) }}</td>
                </tr>
                <tr>
                    <td class="border-bottom center-text">{{ ServiceConnections::numberToRomanRepresentation($i+4) }}</td>
                    <td class="border-bottom">INST. FEE BALANCE ({{ $totalTransactions->InstallationFeeTerms }} MO. RECEIVABLE)</td>
                    <td class="border-bottom right-text">* {{ number_format($totalTransactions->InstallationFeeTermAmountPerMonth, 2) }} x{{ $totalTransactions->InstallationFeeTerms }}</td>
                    <td class="border-bottom right-text">0</td>
                    <td class="border-bottom right-text">* -{{ number_format($totalTransactions->InstallationFeeBalance, 2) }}</td>
                </tr>

                @php
                    $instBalanceTotal = floatval($totalTransactions->InstallationFeeBalance);
                    $materials = $totalTransactions != null ? ($totalTransactions->InstallationPartial - $totalTransactions->MaterialsVAT) : 0;
                    $materialsTotal = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost + $totalTransactions->MaterialsVAT) : 0;
                    $i = $i+4;
                @endphp
            @else
                @php    
                    $materials = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost) : 0;           
                    $materialsTotal = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost + $totalTransactions->MaterialsVAT) : 0;
                @endphp
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i) }}</td>
                    <td class="border-side">INSTALLATION FEE</td>
                    <td class="border-side right-text">{{ number_format($materials, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($materialsVat, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($materialsTotal, 2) }}</td>
                </tr>
            @endif

            {{-- TRANSFOrmer --}}
            @php
                $transformerVat = $totalTransactions != null ? ($totalTransactions->TransformerVAT) : 0;            
            @endphp
            @if ($totalTransactions != null && $totalTransactions->TransformerReceivablesTotal != null && $totalTransactions->TransformerReceivablesTotal > 0)
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+1) }}</td>
                    <td class="border-side">* TRANSFORMER</td>
                    <td class="border-side right-text">* {{ number_format($totalTransactions->TransformerCost, 2) }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                </tr>
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+2) }}</td>
                    <td class="border-side">* TRANSFORMER VAT</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">* {{ number_format($totalTransactions->TransformerVAT, 2) }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                </tr>
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+3) }}</td>
                    <td class="border-side">TRANSFORMER DOWNPAYMENT ({{ round($totalTransactions->TransformerDownpaymentPercentage * 100) }}%)</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->TransformerDownPayment, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->TransformerVAT, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->TransformerVAT + $totalTransactions->TransformerDownPayment, 2) }}</td>
                </tr>
                <tr>
                    <td class="border-bottom center-text">{{ ServiceConnections::numberToRomanRepresentation($i+4) }}</td>
                    <td class="border-bottom">TRANSFORMER BAL. ({{ $totalTransactions->TransformerAmmortizationTerms }} MO. RECEIVABLE)</td>
                    <td class="border-bottom right-text">{{ 0 }}</td>
                    <td class="border-bottom right-text">{{ 0 }}</td>
                    <td class="border-bottom right-text">* -{{ number_format($totalTransactions->TransformerReceivablesTotal, 2) }}</td>
                </tr>
                @php    
                    $transformer = $totalTransactions != null ? ($totalTransactions->TransformerDownPayment) : 0;                    
                    $transformerTotal = $totalTransactions != null ? ($totalTransactions->TransformerDownPayment + $totalTransactions->TransformerVAT) : 0;
                    $i = $i+4;
                @endphp
            @else
                @php
                    
                    $transformer = $totalTransactions != null ? ($totalTransactions->TransformerCost) : 0;    
                    $transformerTotal = $totalTransactions != null ? ($totalTransactions->TransformerCost + $totalTransactions->TransformerVAT) : 0;
                @endphp
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i) }}</td>
                    <td class="border-side">TRANSFORMER</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->TransformerCost, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($totalTransactions->TransformerVAT, 2) }}</td>
                    <td class="border-side right-text">{{ number_format($transformerTotal, 2) }}</td>
                </tr>
                @php
                    $i = $i+1;
                @endphp
            @endif
            
            {{-- WITHOLDING 1% TOTAL --}}
            @php
                $wtOnePercent = $totalTransactions != null ? ($totalTransactions->WithholdingTwoPercent + $totalTransactions->TransformerTwoPercentWT) : 0;
            @endphp
            @if ($wtOnePercent != null && $wtOnePercent != 0)
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+1) }}</td>
                    <td class="border-side">1% (MATERIALS & TRANSFORMER)</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">- {{ number_format($wtOnePercent, 2) }}</td>
                </tr>
                @php
                    $i = $i+1;
                @endphp
            @endif

            {{-- WITHOLDING 2% TOTAL --}}
            @php
                $wtTwoPercent = $totalTransactions != null ? ($totalTransactions->Form2307TwoPercent + $totalTransactions->Item1) : 0;
                $wtFivePercent = $totalTransactions != null ? ($totalTransactions->Form2307FivePercent + $totalTransactions->WithholdingFivePercent + $totalTransactions->TransformerFivePercentWT) : 0;
            @endphp
            @if ($wtTwoPercent != null && $wtTwoPercent != 0)
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+1) }}</td>
                    <td class="border-side">2% WT</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">- {{ number_format($wtTwoPercent, 2) }}</td>
                </tr>
                @php
                    $i = $i+1;
                @endphp
            @endif
            
            {{-- WITHOLDING 5% TOTAL --}}
            @if ($wtFivePercent != null && $wtFivePercent != 0)
                <tr>
                    <td class="border-side center-text">{{ ServiceConnections::numberToRomanRepresentation($i+1) }}</td>
                    <td class="border-side">5% WT</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">{{ 0 }}</td>
                    <td class="border-side right-text">- {{ number_format($wtFivePercent, 2) }}</td>
                </tr>
                @php
                    $i = $i+1;
                @endphp
            @endif

            <tr>
                <th class="border"></th>
                <td class="border center-text">TOTAL WITHOUT DEPOSITS</td>
                <td class="border right-text">{{ number_format(($others + $remittance + $transformer + $materials), 2) }}</td>
                <td class="border right-text">{{ number_format($othersVat + $vat + $depositVat + $transformerVat + $materialsVat, 2) }}</td>
                <td class="border right-text">{{ number_format(($othersTotal + $remittanceTotal + $transformerTotal + $materialsTotal) - ($instBalanceTotal + $wtTwoPercent + $wtFivePercent + $wtOnePercent), 2) }}</td>
             </tr>
            <tr>
               <th class="border"></th>
               <th class="border center-text">OVERALL TOTAL</th>
               <th class="border right-text">{{ number_format(($others + $remittance + $deposit + $transformer + $materials), 2) }}</th>
               <th class="border right-text">{{ number_format($othersVat + $vat + $depositVat + $transformerVat + $materialsVat, 2) }}</th>
               <th class="border right-text">{{ number_format(($othersTotal + $remittanceTotal + $depositTotal + $transformerTotal + $materialsTotal) - ($instBalanceTotal + $wtTwoPercent + $wtFivePercent + $wtOnePercent), 2) }}</th>
            </tr>
         </table>
      </div>
			
      <div class="col-md-12">
         <p class="no-line-spacing left-text">OTHER REQUIREMENTS:</p>
         <p class="no-line-spacing left-text" style="padding-left: 20px;">A. SUPPLY OF SERVICE DROP, SERVICE ENTRANCE AND ITS ACCESSORIES.</p>
         <p class="no-line-spacing left-text" style="padding-left: 20px;">B. SUPPLY OF METER BOX.</p>
         <p class="no-line-spacing left-text" style="padding-left: 20px;">C. AS BUILT ELECTRICAL PLAN DULY SIGNED BY A PROFESSIONAL ELCTRICAL ENGINEER, CERTIFICATE OF FINAL ELECTRICAL INSPECTION (CFEI) / PERMIT FOR TEMPORARY CONNECTION AND FIE SAFETY INSPECTION CERTIFICATE.</p>
         <p class="no-line-spacing left-text" style="padding-left: 20px;">D. CUSTOMER SHALL BE RESPONSIBLE IN THE RIGHT OF WAY NEGOTIATION.</p>
      </div>

      <div class="col-md-12">
         <br>
         <p class="no-line-spacing left-text"><i>Energy Deposit Computation</i></p>
         <table>
            <tr>
               <td colspan="2">Load (kVA)</td>
               <td colspan="2" title="85% Power Factor">85% PF</td>
               <td colspan="2" title="Dynamic Demand Factor depends on the consumer type">Dynamic DF %</td>
               <td colspan="2">Hours</td>
               <td>Average Rate (12 Mo.)</td>
           </tr>
            <tr>
               <td>
                   <input id="Load" name="Load" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $serviceConnections->LoadCategory }}" disabled>
               </td>
               <td>x</td>
               <td>
                   <input id="PowerFactor" name="PowerFactor" type="number" step="any" class="form-control form-control-sm text-right" value=".85" disabled>
               </td>
               <td>x</td><td>
                   <input id="DemandFactor" name="DemandFactor" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null ? $billDeposit->DemandFactor : '' }}" disabled>
               </td>
               <td>x</td>
               <td>
                   <input id="Hours" name="Hours" type="number" step="any" class="form-control form-control-sm text-right" value="720" disabled>
               </td>
               <td>x</td><td>
                   <input id="AverageRate" name="AverageRate" type="number" step="any" class="form-control form-control-sm text-right" value="{{ $billDeposit != null ? $billDeposit->AverageRate : '' }}" disabled>
               </td>
            </tr>
        </table>
        <br><br><br>
      </div>

      <div class="col-md-6">
         <p>PREPARED BY:</p>
         <br>
         <br>  
         <h4 class="no-line-spacing center-text">{{ strtoupper(Auth::user()->name) }}</h4>
         @if (Auth::id() === '1700017433598')
            <p class="no-line-spacing center-text">Chief, CS & PU Division</p>
         @else
            <p class="no-line-spacing center-text">Service Connection Clerk</p>
         @endif
         
      </div>

      <div class="col-md-6">
         <p>NOTED BY:</p>
         <br>
         <br>  
         <h4 class="no-line-spacing center-text">ATTY. ELMER SALUS B. POZON</h4>
         <p class="no-line-spacing center-text">Manager, Institutional Services Department</p>
      </div>

      <div class="col-md-6">
         
      </div>

      <div class="col-md-6">
         <br>
         <br>
         <p>APPROVED BY:</p>
         <br>
         <br>  
         <h4 class="no-line-spacing center-text">DINO NICOLAS T. ROXAS</h4>
         <p class="no-line-spacing center-text">General Manager</p>
      </div>
	</div>

</div>

<script type="text/javascript">   
window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 1000);   
</script>
