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
    <table style="width: 100%;">
        <thead>
            <tr>
                <th colspan="4">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="4">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="4"><strong>43rd AGMA PRE-REGISTRANTS</strong></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Account No.</th>
                <th>Consumer Name</th>
                {{-- <th>Address</th> --}}
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i=1;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $item->AccountNumber }}</td>
                    <td>{{ $item->Name }}</td>
                    {{-- <td>{{ Tickets::getAddress($item) }}</td> --}}
                    <td>
                        <img src="data:image/png;base64,{{ $item->Signature }}" alt="" style="width: 60px;">
                    </td>
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

    // window.setTimeout(function(){
    //     window.history.go(-1)
    // }, 1000);
</script>