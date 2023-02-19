@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
    use App\Models\Users;
@endphp

<style>
    html, body {
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
        overflow: visible;
    }
    
    th, td {
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .68em;
    }

    @media print {
        @page {
            /* size: landscape !important; */
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
            margin-left: 30px;
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
    
        .u-bottom {
            border-bottom: 1px solid #444555;
            padding-bottom: 2px;
            padding-left: 10px;
            padding-right: 10px;
        }
    
        .half {
            display: inline-table; 
            width: 49%;
        }
    
        table, th, tr {
            border-collapse: collapse;
            border: 1px solid #444555;
        }
    
        p {
            margin: 0;
            padding: 0;
        }
    }  

    .float-left {
        float: left;
    }
    
    .left-indent {
        padding-left: 15px;
    }
    
    .left-indent-more {
        padding-left: 40px;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-left {
        text-align: left;
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
    
    .u-bottom {
        border-bottom: 1px solid #444555;
        padding-bottom: 2px;
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .half {
        display: inline-table; 
        width: 46%;
    }

    .third {
        display: inline-table; 
        width: 31%;
    }
    
    table, th, tr, td {
        border-collapse: collapse;
        border-top: 1px solid #cdcdcd;
        border-bottom: 1px solid #cdcdcd;
        border-left: 0px;
        border-right: 0px;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    
    .no-border-top-bottom {
        border-bottom: 0px !important;
        border-top: 0px !important;
    }
    
    .border-bottom-only {
        border-bottom: 1px solid #cdcdcd !important;
        border-top: 0px !important;
    }

    .invoice-card-header {
        padding: 12px;
        border-collapse: collapse;
        border: 1px #cdcdcd solid;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .invoice-card-body {
        padding: 12px;
        border-collapse: collapse;
        border: 1px #cdcdcd solid;
    }

    .invoice-card-footer {      
        padding: 12px;  
        border: 1px #cdcdcd solid;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    .table-borderless {
        border: 1px solid #ffffffff;
    }
    
    p {
        margin: 0;
        padding: 0;
    }
</style>

{{-- <link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}"> --}}

@for ($i = 0; $i < 2; $i++)
<div style=" position: relative; display: block; clear: both; width: 100%; margin-bottom: 20px !important;">
    <div style="width: 100%;">
        @if ($i==1) 
            <div style="width: 100%; position: relative; height: 20px;"></div>
        @endif
        <img src="{{ URL::asset('imgs/company_logo.png'); }}" class="float-left" style="height: 50px; margin-top: -5px;" alt="Image"> 
        <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
        <p class="text-center">{{ env('APP_ADDRESS') }}</p>
        <p class="text-center">{{ env('APP_COMPANY_TIN') }}</p>

        <h4 class="text-center">SERVICE CONNECTION PAYMENT STUB</h4>

        <div style="width: 100%; position: relative;">
            <div class="half" >
                <table class="table" style="width: 100%;">
                    <tr>
                        <td>APPLICANT</td>
                        <th class="text-left" colspan="2">{{ $serviceConnections->ServiceAccountName }}</th>
                    </tr>
                    <tr>
                        <td>ADDRESS</td>
                        <th class="text-left" colspan="2">{{ ServiceConnections::getAddress($serviceConnections) }}</th>
                    </tr>
                    <tr>
                        <td>CONTACT NO</td>
                        <th class="text-left" colspan="2">{{ $serviceConnections->ContactNumber }}</th>
                    </tr>
                    <tr>
                        <td>INSTALLATION TYPE</td>
                        <th class="text-left" colspan="2">{{ strtoupper($serviceConnections->ConnectionApplicationType) }}</th>
                    </tr>
                    <tr>
                        <td>BUILDING PROFILE</td>
                        <th class="text-left" colspan="2">{{ strtoupper($serviceConnections->BuildingType) }}</th>
                    </tr>

                    @foreach ($laborWiringCharges as $item)
                        <tr>
                            <td>{{ strtoupper($item->Material) }}</td>
                            <td class="text-left"> {{ $item->Quantity }} x {{ $item->Rate }} (w/ 12% tax) </td>
                            <th class="text-right">{{ number_format($item->Total, 2) }}</th>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="half" style="margin-left: 30px;">
                <p><i>Payment Summary</i></p>

                <table class="table" style="width: 100%;">
                    <tr>
                        <td>Svc. Con. Charge</td>
                        <th  class="text-right">P {{ number_format($totalTransactions->LaborCharge + $totalTransactions->BOHECOShare, 2) }}</th>
                    </tr>
                    <tr>
                        <td>Bill Deposit</td>
                        <th  class="text-right">P {{ number_format($totalTransactions->BillDeposit, 2) }}</th>
                    </tr>
                    @foreach ($particularPayments as $item)
                        <tr>
                            <td>{{ $item->Particular }}</td>
                            <th  class="text-right">P {{ number_format($item->Amount, 2) }}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Total VAT</td>
                        <th  class="text-right">P {{ number_format($totalTransactions->TotalVat, 2) }}</th>
                    </tr>
                    <tr>
                        <td>2% WItholding Tax</td>
                        <th  class="text-right">- P {{ number_format($totalTransactions->Form2307TwoPercent, 2) }}</th>
                    </tr>
                    <tr>
                        <td>5% WItholding Tax</td>
                        <th  class="text-right">- P {{ number_format($totalTransactions->Form2307FivePercent, 2) }}</th>
                    </tr>
                    <tr>
                        <th class="text-left">Total Payables</th>
                        <th  class="text-right" style="font-size: 1em;">P {{ number_format($totalTransactions->Total, 2) }}</th>
                    </tr>
                </table>       
            </div>
        </div>   

        <div style="width: 100%; position: relative; margin-top: 15px;">
            <div class="third">
                <p>Prepared By:</p>
                <br>
                <br>
                <p class="text-center" style="padding-left: 20px; padding-right: 20px; border-bottom: 1px solid #898989;"><strong>{{ env('ISD_HWRS_ASSESSOR') }}</strong></p>
                <p class="text-center"><i>Housewiring Assessor</i></p>
            </div>

            <div class="third" style="margin-left: 20px;">
                <p>Checked By:</p>
                <br>
                <br>
                <p class="text-center" style="padding-left: 20px; padding-right: 20px; border-bottom: 1px solid #898989;"><strong>{{ env('ISD_CHIEF_POWER_USE_DIVISION') }}</strong></p>
                <p class="text-center"><i>Chief, Housewiring ...</i></p>
            </div>

            <div class="third" style="margin-left: 20px;">
                <p>Approved By:</p>
                <br>
                <br>
                <p class="text-center" style="padding-left: 20px; padding-right: 20px; border-bottom: 1px solid #898989;"><strong>{{ env('ISD_MANAGER') }}</strong></p>
                <p class="text-center"><i>ISD Manager</i></p>
            </div>
        </div>

        @if ($i==0) 
            <div style="width: 100%; position: relative; height: 40px; border-bottom: 1px dotted #898989"></div>
        @endif
    </div>  
</div>

@endfor


<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.location.href="{{ url(route('serviceConnections.show', [$serviceConnections->id])) }}"
    }, 800);
</script>