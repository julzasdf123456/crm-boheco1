<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-family: sans-serif; */
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        /* font-family: sans-serif; */
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
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

        .text-left {
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

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

</style>

<div id="print-area" class="content">
    <p>Generated On: {{ date('F d, Y h:i:s A') }}</p>
    <br>
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <p class="text-center">NEW ADDED ACCOUNTS FROM {{ strtoupper(date('M d, Y', strtotime($from))) }} TO {{ strtoupper(date('M d, Y', strtotime($to))) }}</p>
    <br>  
    <table style="width: 100%;">
        <thead>
            <th class="text-left" style="border-bottom: 1px solid #454455">#</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Account No</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Consumer Name</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Address</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Meter Number</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Route</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Sequence</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Consumer Type</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Account ID</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Contact Number</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Date Entry</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">User</th>
        </thead>
        <tbody>
            @php
                $i=0;
            @endphp
            @foreach ($accounts as $item)
                <tr>
                    <td class="text-left">{{ $i+1 }}</td>
                    <td class="text-left">{{ $item->AccountNumber }}</a></td>
                    <td class="text-left"><strong>{{ $item->ConsumerName }}</strong></td>
                    <td class="text-left">{{ $item->ConsumerAddress }}</td>
                    <td class="text-left">{{ $item->MeterNumber }}</td>
                    <td class="text-left">{{ $item->Route }} </td>
                    <td class="text-left">{{ $item->SequenceNumber }}</td>
                    <td class="text-left">{{ $item->ConsumerType }}</td>
                    <td class="text-left">{{ $item->UniqueID }}</td>
                    <td class="text-left">{{ $item->ContactNumber }}</td>
                    <td class="text-left">{{ $item->DateEntry != null ? date('M d, Y h:i A', strtotime($item->DateEntry)) : '' }}</td>
                    <td class="text-left">{{ $item->UserName }}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>