@php
    use Illuminate\Support\Facades\Auth;
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
    <p class="text-center"><strong>DOUBLE PAYMENTS FOR {{ strtoupper(date('F d, Y', strtotime($date))) }}</strong></p>
    <p class="text-center"><strong>DISCONNECTION COLLECTION - {{ strtoupper($name) }} </strong></p>
    <br>    
    <table style="width: 100%;">
        <thead>
            <th class="text-left">Account No</th>
            <th class="text-left">Name</th>
            <th class="text-left">Address</th>
            <th class="text-left">Billing Month</th>
            <th class="text-left">Payment Date</th>
            <th class="text-right">Teller</th>
            <th class="text-right">Amount</th>
            <th class="text-right">Surcharge</th>
            <th class="text-right">Total Amount</th>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($doublePayments as $item)
                <tr>
                    <td class="text-left">{{ $item->AccountNumber }}</td>
                    <td class="text-left">{{ $item->ConsumerName }}</td>
                    <td class="text-left">{{ $item->ConsumerAddress }}</td>
                    <td class="text-left">{{ date('M Y', strtotime($item->ServicePeriodEnd)) }}</td>
                    <td class="text-left">{{ date('M d, Y', strtotime($item->DatePaid)) }}</td>
                    <td class="text-left">{{ $item->Teller }}</td>
                    <td class="text-right">{{ number_format($item->NetAmount, 2) }}</td>
                    <td class="text-right">{{ number_format($item->Surcharge, 2) }}</td>
                    <td class="text-right">{{ number_format($item->PaidAmount, 2) }}</td>
                </tr>
                @php
                    $total += floatval($item->PaidAmount);
                @endphp
            @endforeach
            <tr>
                <td colspan="8" class="text-left"><strong>Total</strong></td>
                <td class="text-right"><strong>{{ number_format($total, 2) }}</strong></td>
            </tr>
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