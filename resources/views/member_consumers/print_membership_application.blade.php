@php
    use App\Models\MemberConsumers;
    use App\Models\MemberConsumerSpouse;
    use Illuminate\Support\Facades\Auth;    

@endphp

<style>
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
        padding: 1px !important;
        margin: 1px !important;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .72em;
    }

    @media print {
    @page {
        /* size: landscape !important; */
        padding: 13px !important;
        margin: 13px !important;
    }

    header {
        display: none;
    }

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    }

    .left-indent {
        margin-left: 30px !important;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .print-area {        
        page-break-after: always;
    }

    .print-area:last-child {        
        page-break-after: auto;
    }

    .left-indent-more {
        margin-left: 90px !important;
    }
}  


.left-indent {
    margin-left: 50px !important;
}

.left-indent-more {
    margin-left: 90px !important;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.divider {
    width: 100%;
    margin: 10px auto;
    height: 1px;
    background-color: #dedede;
} 

p {
    padding: 3px !important;
    margin: 3px !important;
}

.float-left {
    float: left;
}

.text-underline {
    border-bottom: 1px solid #878787;
}
</style>

{{-- <link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}"> --}}

<div class="container form-margin wrapper" style="font-size: 1.15em !important;">
	
	<div class="content">
        <img src="{{ URL::asset('imgs/company_logo.png'); }}" class="float-left" style="height: 40px;" alt="Image"> 
		<p class="text-center">BOHOL I ELECTRIC COOPERATIVE, INC.</p>
		<p class="text-center">Cabulijan, Tubigon. Bohol</p>

		<p class="text-center" style="margin-top: 15px;"><strong>APPLICATION FOR MEMBERSHIP and FOR ELECTRIC SERVICE</strong></p>

		<p style="text-indent: 50px;" style="margin-top: 15px;">
			The undersigned (hereinafter called the ”APPLICANT”) hereby applies for membership in and agrees to purchase electric energy from the BOHOL I ELECTRIC COOPERATIVE,INC. (hereinafter called the “BOHECO I”) upon the following terms and conditions:
		</p>

		<p>1.	The APPLICANT shall pay to the BOHECO I the sum of P5.00 which, if this application is accepted by the BOHECO I, will constitute the APPLICANT’s membership fee.</p>

		<p>2.	The APPLICANT shall, when electric energy becomes available, purchase from the BOHECO I all electric energy used in his premises described in the sketch/diagram/plan attached/on file, and shall pay therefore monthly rates to be determined from time to time, in accordance with the By-Laws of the BOHECO I.</p>
		<p>3.	The APPLICANT shall cause his premises to be wired in accordance with the wiring specifications approved by the BOHECO I.</p>
		<p>4.	The APPLICANT shall comply with & be bound by the provisions of the Charter and By-Laws of the BOHECO I, and such rules and regulations as may from time to time be adopted by the BOHECO I.</p>
		<p>5.	The APPLICANT shall attend a pre-membership seminar to include among others, safety policy and other related policies regarding the distribution system of the cooperative.</p>
		<p>6.	The APPLICANT, by paying a membership fee and becoming a member, shall assume no liability or responsibility for any debt or liability of the BOHECO I, and it is expressly understood that under the law his private property is exempt from execution of debt or liability of the BOHECO I.</p>
		<p>7.	The APPLICANT shall notify the cooperative of any change of his existing load.</p>
		<p>8.	The APPLICANT shall grant to the BOHECO I, at its request, the necessary rights and easements to construct, operate, replace, repair and perpetually maintain the property owned by the APPLICANT anywhere within the BOHECO I area, and in or upon all road streets or highways abutting the said property, its lines for the transmission or distribution of electric energy and shall execute and deliver to the BOHECO I any conveyance, grant or instrument which the BOHECO I shall deem necessary or convenient for said purpose or any of them.  All lines of the BOHECO I and switches, meter and other appliances and equipment constructed or installed by the BOHECO I on said property shall at all times be the sole property of the BOHECO I, and the BOHECO I shall have the rights or access to; and ingress to and agrees from the said property to operate, maintain or relocate its facilities.</p>
		<p>9.	The APPLICANT’s service connection shall be enjoyed only after complying all requirements of the LGU’s and the BOHECO I.</p>
		<p>10.	The APPLICANT shall be held responsible and liable for tampering, interfering with or breaking of seals of meter or other equipment of the BOHECO I installed in the consumer premises and/or for any of the unlawful acts defined and enumerated under Sections 2 and 3 of Republic Act No. 7832.</p>
		<p>11.	The APPLICANT shall be responsible and liable for any existence/presence of the circumstances defined and enumerated under Section 4 of Republic Act No. 7832.</p>
		<p>12.	The APPLICANT shall agree that this contract shall be subject to the other provisions of Republic Act No. 7832 otherwise known as the Anti-Pilferage of Electricity and Electric Transmission Lines/Materials Act of 1995.</p>
		<p>13.	The BOHECO I is expressly authorized and empowered to remove its property and discontinue furnishing electric service with written notice should the APPLICANT fails to comply with any of the rules and regulations of the BOHECO I or become delinquent in the payment of accounts due to the BOHECO I for electric consumptions and/or housewiring materials, and for this purpose the BOHECO I is hereby granted all necessary rights to ingress to, egress from and access and over said premises.</p>
		<p>14.	 The BOHECO I shall not be liable for damages to the APPLICANT for failure to supply electricity to said premises under any condition.</p>
		<p>15.	The acceptance of this application by the BOHECO I shall constitute an agreement between the APPLICANT and the BOHECO I and the contract for electric service shall continue in force for ONE (1) YEAR from the date the service is made available by the BOHECO I to the APPLICANT, and thereafter as long as the APPLICANT has need for any electric service on the subject premises.</p>
        <p><strong><i>By affixing your signature below, you willingly grant permission to BOHECO I to collect personal data needed to address your concerns. You understand the nature and purpose of this survey, and your consent is given freely.</i></strong></p>
        <p><strong><i>Rest assured, your personal data will be kept in our records and treated with the utmost confidentiality. We may retain your personal data only as long as necessary for legitimate business purposes, to comply with laws and regulations, or to establish or defend a legal action. BOHECO I only requests information that is relevant and necessary for us to perform our services for you.</i></strong></p>
        <p><strong><i>BOHECO I respects your right to privacy and is committed to safeguarding your personal data. If you have any questions or concerns, please contact us at dpo.boheco1@boheco1.com.</i></strong></p>
		<br>
		<p class="indent-text">SIGNED IN THE PRESENCE OF:</p>
		<br>

		<div class="col-md-12">
			<div style="display: inline-table; width: 48%;">
				<p class="text-underline text-center text-allcaps"><strong>{{ strtoupper(Auth::user()->name) }}</strong></p>		
				<p class="text-center"><i>Facilitating Personnel</i></p>	

				<br>

				<p>Date Signed: <strong class="text-underline">{{ date('F d, Y') }}</strong></p>	
				<p>Membership OR Fee Paid On: <strong class="text-underline">{{  $memberConsumers->ORDate==null ? '' : date('F d, Y', strtotime($memberConsumers->ORDate)) }}</strong></p>	
				<p>Membership Official Receipt No: <strong class="text-underline">{{ $memberConsumers->ORNumber==null ? '' : $memberConsumers->ORNumber }}</strong></p>	
			</div>

			<div style="display: inline-table; width: 48%;">
				<p class="text-underline text-center text-allcaps"><strong>{{ MemberConsumers::serializeMemberName($memberConsumers) }} </strong></p>
				<p class="text-center"><i>Applicant's Printed Name & Signature</i></p>	

				<br>

				<?php if($memberConsumerSpouse != null) : ?>
					<p class="text-underline text-center text-allcaps"><strong>{{ strtoupper(MemberConsumerSpouse::serializeMemberSpouseName($memberConsumerSpouse)) }}</strong></p>
					<p class="text-center"><i>Printed Name & Signature of Spouse</i></p>
				<?php endif ?>					

				<br>

				<p>Address: <strong class="text-underline">{{ strtoupper($memberConsumers->Sitio . ', ' . $memberConsumers->Barangay . ', ' . $memberConsumers->Town) }}</strong></p>	
				<p>Contact Nos: <strong class="{{ $memberConsumers->ContactNumbers == null ? '' : 'text-underline' }}">{{ $memberConsumers->ContactNumbers == null ? '_________________________' : $memberConsumers->ContactNumbers }}</strong></p>	
			</div>
		</div>
	</div>

	<div class="thumbnail">
		<img id="profile-preview" src="">
	</div>

</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 3800);
</script>