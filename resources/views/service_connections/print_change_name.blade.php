
@php
   use App\Models\ServiceConnections;
   use App\Models\Users;
   use Illuminate\Support\Facades\Auth;
@endphp

<style>
@media print {
   @page {
      margin: 20;
   }

    p {
        margin: 0px !important;
    }

    html, body {
        margin: 0px !important;
        padding: 0px !important;
        font-family: sans-serif;
        font-size: .9em;
    }

    td, th {
      font-size: .9em;
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

   .sm-line-spacing {
		padding-top: 3px;
		padding-bottom: 3px;
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
        width: 32%;
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

    .divider {
      height: 1px;
      background-color: #484848;
      width: 100%;
      margin: 10px auto;
      -webkit-print-color-adjust: exact;
    }
}
</style>

<div>
	<div class="header-print">
		<div class="row">
			<div class="col-md-2">
                <img src="{{ URL::asset('imgs/company_logo.png'); }}" style="height: 60px; left: 60px; position: fixed;" alt="Image"> 
			</div>
			<div class="col-md-12">
				<h4 class="no-line-spacing center-text"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></h4>
				<p class="no-line-spacing center-text">{{ env('APP_ADDRESS') }}</p>
            <p class="no-line-spacing center-text">09177147493 • 09199950240 • (038)508-9751 • www.boheco1.com</p>
            <p class="no-line-spacing center-text">FB Page: Bohol I Electric Cooperative, Inc • Email: boheco1_main@yahoo.com</p>

            <div class="divider"></div>

            <h2 class="center-text">CHANGE OF ACCOUNT NAME</h2>
            <p class="no-line-spacing center-text"><strong>IMPORTANT: <i>The change of account name is NOT change of ownership of the concerned property/ies.</i></strong></p>
            <br>
			</div>

         <div class="col-md-6">
            <p class="sm-line-spacing">Account No. : <span class="underlined" style="width: 90%;"><strong>{{ $serviceConnection->AccountNumber }}</strong></span></p>
            <p class="sm-line-spacing">Old Name : <span class="underlined" style="width: 90%;"><strong>{{ $serviceConnection->OrganizationAccountNumber }}</strong></span></p>
         </div>

         <div class="col-md-6">
            <p class="sm-line-spacing">Address : <span class="underlined" style="width: 90%;"><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></span></p>
            <p class="sm-line-spacing">New Name : <span class="underlined" style="width: 90%;"><strong>{{ $serviceConnection->ServiceAccountName }}</strong></span></p>
         </div>

         <div class="col-md-12">
            <p class="sm-line-spacing">Relationship to the Existing Account Holder : <span class="underlined" style="width: 90%;"><strong>{{ $serviceConnection->ResidenceNumber }}</strong></span></p>
            <p class="sm-line-spacing">Reason/s for changing the account name : <span class="underlined" style="width: 90%;"><strong>{{ $serviceConnection->Notes }}</strong></span></p>

            <div class="divider"></div>

            <p class="no-line-spacing center-text"><strong>I hereby signify that I understand the change in BOHECO I account name DOES NOT change ownership
               of any concerned property/ies. I also understand that any false information on my part, BOHECO I reserves the right to take appropriate
               action on the matter.</strong></p>

            <br><br>

            <p class="sm-line-spacing">Requested By (Signature over Printed Name)/Date : <span class="underlined">
               <strong style="margin-left: 50px;">{{ $serviceConnection->ServiceAccountName }}</strong>
               <span style="margin-left: 60px; margin-right: 30px;">{{ date('F d, Y') }}</span>
            </span></p>
         </div>

         <div class="col-md-6">
            <p class="sm-line-spacing">Contact Number : <span class="underlined" style="width: 90%;"><strong>{{ $serviceConnection->ContactNumber }}</strong></span></p>
         </div>

         <div class="col-md-6">
            <p class="sm-line-spacing">Email Address : ___________________________</p>
         </div>
		</div>
     
      <br><br>
      <div class="col-md-4">
         <p class="center-text">RECEIVED BY/Date:</p>
         <br>
         <br>  
         <h4 class="no-line-spacing center-text underlined">{{ strtoupper(Auth::user()->name) }}</h4>
         <p class="no-line-spacing center-text">CWD Clerk</p>
      </div>

      <div class="col-md-4">
         <p class="center-text">APPROVED BY/Date:</p>
         <br>
         <br>  
         <h4 class="no-line-spacing center-text underlined">{{ env('ISD_MANAGER')}}</h4>
         <p class="no-line-spacing center-text">ISD Manager</p>
      </div>

      <div class="col-md-4">
         <p class="center-text">EXECUTED BY/Date:</p>
         <br>
         <br>  
         <h4 class="no-line-spacing center-text">_________________________</h4>
         <p class="no-line-spacing center-text">Billing Section</p>
      </div>
	</div>

</div>

<script type="text/javascript">   
window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 1000);   
</script>
