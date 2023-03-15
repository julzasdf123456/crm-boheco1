@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp
<style>
     html, body {
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .95em;
        overflow: visible;
    }
    
    th, td {
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .78em;
    }

    @media print {
        html, body {
            width: 100%;
        }

        @page {
            margin: 10px;
        }

        header {
            display: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #cdcdcd;
        }

        .center-text {
            text-align: center;
        }

        p {
            padding: 0 !important;
            margin: 0 !important;
        }

        .row {
            width: 100%;
            display: inline-flex;
        }

        .col-3 {
            width: 25%;
        }

        .col-7 {
            width: 75%;
        }

        .col-6 {
            width: 48%;
        }

        .col-12 {
            width: 98%;
        }

        table {
            width: 100%;
        }

        .check-box {
            padding: 2px 12px;
            margin-right: 5px;
            border: 1px solid #878787;
        }

        .divider-dotted {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            border-bottom: 1px dotted #878787;
        } 
    }  

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    .divider-dotted {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        border-bottom: 1px dotted #878787;
    } 

    .center-text {
        text-align: center;
    }

    p {
        padding: 0 !important;
        margin: 0 !important;
    }

    .row {
        width: 100%;
        display: inline-flex;
    }

    .col-3 {
        width: 25%;
    }

    .col-4 {
        width: 31%;
        padding-right: 10px;
    }

    .col-7 {
        width: 75%;
    }

    .col-6 {
        width: 48%;
    }

    .col-12 {
        width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border-top: 1px solid #cdcdcd;
        border-bottom: 1px solid #cdcdcd;
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: left;
    }

    .check-box {
        padding: 2px 12px;
        margin-right: 5px;
        border: 1px solid #878787;
    }

</style>
<!-- AdminLTE -->
{{-- <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css"/> --}}

<div class="content px-3">
    <p class="center-text"><strong>{{ env('APP_COMPANY') }}</strong></p>
    <p class="center-text">{{ env('APP_ADDRESS') }}</p>
    <p class="center-text" style="margin-top: 8px !important; font-size: 1em;"><strong>TICKET WORK ORDER</strong></p>

    <div class="row">
        @php
            $parent = TicketsRepository::where('id', $tickets->ParentTicket)->first();
        @endphp
        <table style="width: 100%;">
            <tr>
                <td>REQUEST/COMPLAINT</td>
                <th>{{ $parent != null ? strtoupper($parent->Name) . ' - ' : '' }}{{ strtoupper($tickets->Ticket) }}</th>
                <td>TICKET NO</td>
                <th>{{ $tickets->id }}</th>
            </tr>
            <tr>
                <td>CONSUMER NAME</td>
                <th>{{ $tickets->ConsumerName }}</th>
                <td>DATE FILED</td>
                <th>{{ date('F d, Y h:i:s A', strtotime($tickets->created_at)) }}</th>
            </tr>
            <tr>
                <td>ACCOUNT NO.</td>
                <th>{{ $tickets->AccountNumber }}</th>
                <td>D/T FIELD ARRIVAL</td>
                <th></th>
            </tr>
            <tr>
                <td>ADDRESS</td>
                <th>{{ Tickets::getAddress($tickets) }}</th>
                <td>D/T EXECUTED</td>
                <th></th>
            </tr>
            <tr>
                <td>CONTACT NO</td>
                <th>{{ $tickets->ContactNumber }}</th>
                <td>ENCODER</td>
                <th>{{ $tickets->name }}</th>
            </tr>
            <tr>
                <td>POLE NO.</td>
                <th>{{ $tickets->PoleNumber }}</th>
                <td>OR NUMBER</td>
                <th>{{ $tickets->ORNumber }}</th>
            </tr>
            <tr>
                <td>REPORTED BY</td>
                <th>{{ $tickets->ReportedBy }}</th>
                <td>OR DATE</td>
                <th>{{ $tickets->ORDate != null ? date('F d, Y', strtotime($tickets->ORDate)) : '' }}</th>
            </tr>
            <tr>
                <td>NEIGHBOR 1</td>
                <th>{{ $tickets->Neighbor1 }}</th>
                <td>NEIGHBOR 2</td>
                <th>{{ $tickets->Neighbor2 }}</th>
            </tr>
        </table>

    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-6">
            <p class="center-text"><strong>CURRENT METER DETAILS</strong></p>
            <div class="row">
                <div class="col-6">
                    <p>Serial No</p>
                    <p>Brand</p>
                    <p>Pullout Reading</p>
                    <p>Seal No.</p>
                </div>
                <div class="col-6">
                    <p style="border-bottom: 1px solid #989898;">:  <strong>{{ $tickets->CurrentMeterNo }}</strong></p>
                    <p style="border-bottom: 1px solid #989898;">:  <strong>{{ $tickets->CurrentMeterBrand }}</strong></p>
                    <p style="border-bottom: 1px solid #989898;">:  <strong>{{ $tickets->CurrentMeterReading }}</strong></p>
                    <p style="border-bottom: 1px solid #989898;">:  <strong></strong></p>
                </div>
            </div>

            <i style="margin-top: 5px !important; padding-top: 5px !important;">Reason</i>
            <p style="border: 1px solid #cdcdcd; border-radius: 4px; padding: 10px !important; margin-right: 10px !important; height: 20px;">{{ $tickets->Reason }}</p>
        </div>

        <div class="col-6">
            <p class="center-text"><strong>NEW METER DETAILS</strong></p>
            <div class="row">
                <div class="col-6">
                    <p>Serial No</p>
                    <p>Brand</p>
                    <p>Start Reading</p>
                    <p>Seal No.</p>
                </div>
                <div class="col-6">
                    <p style="border-bottom: 1px solid #989898;">:  <strong>{{ $tickets->NewMeterNo }}</strong></p>
                    <p style="border-bottom: 1px solid #989898;">:  <strong>{{ $tickets->NewMeterBrand }}</strong></p>
                    <p style="border-bottom: 1px solid #989898;">:  <strong>{{ $tickets->NewMeterReading }}</strong></p>
                    <p style="border-bottom: 1px solid #989898;">:  <strong></strong></p>
                </div>
            </div>

            <i style="margin-top: 5px !important; padding-top: 5px !important;">Notes/Remarks</i>
            <p style="border: 1px solid #cdcdcd; border-radius: 4px; padding: 10px !important; height: 20px;">{{ $tickets->Notes }}</p>
        </div>
    </div>

    <div class="row" style="margin-top: 10px; border-bottom: 1px solid #cdcdcd; padding-bottom: 10px; margin-bottom: 10px;">
        <div class="col-6">
            <p><span class="check-box"></span>Executed</p>
        </div>

        <div class="col-6">
            <p><span class="check-box"></span>Not Executed</p>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-4">
            <p style="border-bottom: 1px solid #858585" class="center-text">{{ $tickets->ConsumerName }}</p>
            <p class="center-text"><i>CONSUMER NAME</i></p>
        </div>

        <div class="col-4">
            <p style="border-bottom: 1px solid #858585" class="center-text">{{ $tickets->StationName != null ? strtoupper($tickets->StationName) : '`' }}</p>
            <p class="center-text"><i>SERVICEMAN</i></p>
        </div>

        <div class="col-4">
            <p style="border-bottom: 1px solid #858585;" class="center-text"><span style="opacity: 0;">0</span></p>
            <p class="center-text"><i>HEAD/SUPERVISOR/MANAGER</i></p>
        </div>
    </div>
</div>

<script>
window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 1000); 
</script>