@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Tickets;
@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
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
            /* margin: 10px; */
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

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-right {
            text-align: left;
        }

        table tbody tr td.text-right,
        table thead th.text-right {
            text-align: right;
        }

        table tbody tr td.text-left,
        table thead th.text-left {
            text-align: left;
        }
    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-right {
        text-align: left;
    }

    table tbody tr td.text-right,
    table thead th.text-right {
        text-align: right;
    }

    table tbody tr td.text-left,
    table thead th.text-left {
        text-align: left;
    }

    table tbody tr td,
    table thead th {
        border: 1px solid #a9a9a9;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    table {
        border-collapse: collapse;
    }

</style>

<div id="print-area" class="content">
    <span class="text-left">Generated on {{ date('M d, Y h:i A') }}</span>
    <br>
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <br>
    <p class="text-center"><strong>UNEXECUTED TICKETS FROM {{ strtoupper(date('F d, Y', strtotime($from))) }} TO {{ strtoupper(date('F d, Y', strtotime($to))) }}</strong></p>
    <p class="text-center"><strong>CREW: {{ strtoupper($area) }}</strong></p>
    <br>    
    <table style="width: 100%;">
        <thead>
            <th>Ticket No</th>
            <th>Account No.</th>
            <th>Consumer Name</th>
            <th>Address</th>
            <th>Complaint/Request</th>
            <th>Status</th>
            <th>Date Complained</th>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->AccountNumber }}</td>
                    <td>{{ $item->ConsumerName }}</td>
                    <td>{{ Tickets::getAddress($item) }}</td>
                    <td>{{ $item->ParentTicket }} - {{ $item->Ticket }}</td>
                    <td>{{ $item->Status }}</td>
                    <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <br>
    <br>
    <p>Prepared By:</p>
    <br>
    <br>
    <p><strong>{{ strtoupper(Auth::user()->name) }}</strong></p>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>