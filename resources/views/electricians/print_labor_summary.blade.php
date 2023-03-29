
<style>
    /* @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    } */
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table {
        border-collapse: collapse;
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

    .border-collapse td,
    .border-collapse th {
        border: 1px solid #454455;
        border-collapse: collapse;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .half {
        width: 49.5%;
        display: inline-block;
    }

</style>

<div>
    {{-- SUMMARY --}}
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center" style="padding-bottom: 15px; padding-top: 15px;">LABOR SHARE SUMMARY FOR {{ strtoupper(date('F', strtotime($from))) }} {{ date('d', strtotime($from)) . '-' . date('d', strtotime($to)) }}, {{ strtoupper(date('Y', strtotime($from))) }} | {{ $office }}</th>
            </tr>
            <tr class="border-collapse">
                <!-- <th style="width: 25px;"></th> -->
                <th>#</th>
                <th>Electrician</th>
                <th>Gross Share</th>
                <th>2% WT</th>
                <th>Net Share</th>
                <th style="width: 24% !important;">Signature</th>
            </tr>            
        </thead>
        <tbody>
            @php
                $i=1;
                $laborTotal = 0;
                $percent2 = 0;
                $netTotal = 0;
            @endphp
            @foreach ($data as $item)
                <tr class="border-collapse">
                    <td>{{ $i }}</td>
                    <td>{{ strtoupper($item->ElectricianName) }}</td>
                    <td class="text-right">₱ {{ is_numeric($item->LaborCharge) ? number_format($item->LaborCharge, 2) : $item->LaborCharge }}</td>
                    <td class="text-right"><strong>- ₱ {{ is_numeric($item->LaborCharge) ? number_format(floatval($item->LaborCharge) * .02, 2) : 'error' }}</strong></td>
                    <td class="text-right"><strong>₱ {{ is_numeric($item->LaborCharge) ? number_format(floatval($item->LaborCharge) - (floatval($item->LaborCharge) * .02), 2) : 'error' }}</strong></td>
                    <td></td>
                </tr>
                @php
                    $i++;
                    $laborTotal += is_numeric($item->LaborCharge) ? floatval($item->LaborCharge) : 0;
                    $percent2 += is_numeric($item->LaborCharge) ? (floatval($item->LaborCharge) * .02) : 0;
                    $netTotal += is_numeric($item->LaborCharge) ? (floatval($item->LaborCharge) - (floatval($item->LaborCharge) * .02)) : 0;
                @endphp
            @endforeach
            <tr class="border-collapse">
                <th class="text-left" colspan="2">TOTAL</th>
                <th class="text-right">₱ {{ number_format($laborTotal, 2) }}</th>
                <th class="text-right">- ₱ {{ number_format($percent2, 2) }}</th>
                <th class="text-right">₱ {{ number_format($netTotal) }}</th>
                <th></th>
            </tr>
        </tbody>
    </table>  
    
    <div class="half">
        <br>
        <br>
        <p>Prepared By:</p>
        <br>
        <br>
        <p><strong>ESTRELLA GEROCHE</strong></p>
        <p style="margin-top: 5px;"><i>Payroll Clerk</i></p>
    </div>

    <div class="half">
        <br>
        <br>
        <p>Verified By:</p>
        <br>
        <br>
        <p><strong>MARISSA LEORNA</strong></p>
        <p style="margin-top: 5px;"><i>OSD Manager</i></p>
    </div>

    <div class="half">
        <br>
        <br>
        <p>Audited By:</p>
        <br>
        <br>
        <p><strong>MARLYN MANAPSAL</strong></p>
        <p style="margin-top: 5px;"><i>Internal Auditor</i></p>
    </div>

    <div class="half">
        <br>
        <br>
        <p>Approved By:</p>
        <br>
        <br>
        <p><strong>DINO NICOLAS T. ROXAS</strong></p>
        <p style="margin-top: 5px;"><i>General Manager, BOHECO I</i></p>
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1600);
</script>