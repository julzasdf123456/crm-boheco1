
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
      {{-- INSTALLATION FEE --}}
      <div class="col-md-12">
        <span>A. DISTRIBUTION LINE (To be paid after the right-of-way documents are completed)</span>
         <table style="width: 100%; margin-top: 2px; margin-bottom: 15px;">
            <tr>
               <th class="border">ITEM</th>
               <th class="border">DESCRIPTION</th>
               <th class="border">AMOUNT</th>
               <th class="border">12%<br>EVAT</th>
               <th class="border">TOTAL<br>AMOUNT</th>
            </tr>
            @php
                $i = 1;
            @endphp
            @foreach ($particularPayments as $item)
                @if ($item->Particular=='Installation Fee')
                    <tr>
                        <td class="border center-text">{{ ServiceConnections::numberToRomanRepresentation($i) }}</td>
                        <td class="border">{{ strtoupper($item->Particular) }}</td>
                        <td class="border right-text">{{ number_format($item->Amount, 2) }}</td>
                        <td class="border right-text">{{ number_format(floatval($item->Amount) * .12, 2) }}</td>
                        <td class="border right-text">{{ number_format(floatval($item->Amount) + (floatval($item->Amount) * .12), 2) }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @else
                    
                @endif                
            @endforeach
         </table>
      </div>

      {{-- OTHER CHARGES --}}
      <div class="col-md-12">
        <span>B. OTHER CHARGES (To be paid after distribution line construction is completed)</span>
         <table style="width: 100%; margin-top: 2px;">
            <tr>
               <th class="border">ITEM</th>
               <th class="border">DESCRIPTION</th>
               <th class="border">AMOUNT</th>
               <th class="border">12%<br>EVAT</th>
               <th class="border">TOTAL<br>AMOUNT</th>
            </tr>
            <tr>
               <td class="border-side center-text">I</td>
               <td class="border-side">ENERGY DEPOSIT *</td>
               <td class="border-side right-text">{{is_numeric($totalTransactions->BillDeposit) ? number_format($totalTransactions->BillDeposit, 2) : '0.00' }}</td>
               <td class="border-side right-text">{{is_numeric($totalTransactions->BillDeposit) ? number_format(floatval($totalTransactions->BillDeposit) * .12, 2) : '0.00' }}</td>
               <td class="border-side right-text">{{is_numeric($totalTransactions->BillDeposit) ? number_format(floatval($totalTransactions->BillDeposit) + (floatval($totalTransactions->BillDeposit) * .12), 2) : '0.00' }}</td>
            </tr>
            @php
               $deposit = is_numeric($totalTransactions->BillDeposit) ? floatval($totalTransactions->BillDeposit) : 0;
               $depositVat = is_numeric($totalTransactions->BillDeposit) ? (floatval($totalTransactions->BillDeposit) * .12) : 0;
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
                @if ($item->Particular=='Installation Fee')
                    
                @else
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
                @endif                
            @endforeach

            <tr>
               <th class="border"></th>
               <th class="border center-text">TOTAL</th>
               <th class="border right-text">{{ number_format($others + $remittance + $deposit, 2) }}</th>
               <th class="border right-text">{{ number_format($othersVat + $vat + $depositVat, 2) }}</th>
               <th class="border right-text">{{ number_format($othersTotal + $remittanceTotal + $depositTotal, 2) }}</th>
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
         <p class="no-line-spacing center-text">Service Connection Clerk</p>
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
