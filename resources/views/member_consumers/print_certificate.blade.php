@php
    use App\Models\MemberConsumers;

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
            orientation: portrait;
            margin: 0;
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

        .pms {
          color: black;
          background: rgb(243, 231, 57);
          padding: 10px !important;
          margin-top: 10px;
          display: inline-block;
          font-size: 2em;
          font-weight: bold;
          border-radius: 20px;;
          border: 3px solid #343434;
          -webkit-print-color-adjust: exact;
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
        font-size: 1.2em;
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

    .half {
        display: inline-table; 
        width: 49%;
    }

    .thirty {
        display: inline-table; 
        width: 30%;
    }

    .seventy {
        display: inline-table; 
        width: 69%;
    }

    .watermark {
        position: fixed;
        left: 15%;
        top: 60px;
        width: 65%;
        opacity: 0.16;
        z-index: -99;
        color: white;
        user-select: none;
    }

    .border {
        position: fixed;
        width: 100%;
        z-index: 1;
        color: white;
        left: 0;
        top: 0;
    }

    .pms {
      color: black;
      background: rgb(243, 231, 57);
      padding: 30px;
      font-size: 2em;
      -webkit-print-color-adjust: exact;
    }

</style>

<img src="{{ URL::asset('imgs/cert_border.png'); }}" class="border"> 
<div id="print-area" class="content">
  <img src="{{ URL::asset('imgs/company_logo.png'); }}" class="watermark"> 
  <div style="text-align: center; display: inline;">
      <img src="{{ URL::asset('imgs/company_logo.png'); }}" width="70px;" style="position: absolute; left: 150; top: 50;"> 

      <p class="text-center" style="padding-bottom: 2px; padding-top: 50px !important; font-size: 1.52em;"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
      <p class="text-center" style="padding-bottom: 2px;"><strong>({{ strtoupper(env('APP_COMPANY_ABRV')) }})</strong></p>
      <p class="text-center" style="padding-bottom: 2px;"><strong>{{ strtoupper(env('APP_ADDRESS')) }}</strong></p>
      <br>
  </div>

  <p style="font-family: Brush Script MT, Brush Script Std, cursive; margin-top: 10px; font-size: 5.2em;" class="text-center">Certificate of Attendance</p>
  <br>
  <p class="text-center">is awarded to</p>
  <br>
  <br>

  <p class="text-center" style="font-size: 2em;"><strong>{{ $memberConsumer != null ? strtoupper(MemberConsumers::serializeMemberNameFormal($memberConsumer)) : '-' }}</strong></p>
  <p class="text-center" style="font-size: 1.3em; border-bottom: 1px solid #343434; margin-left: 10%; margin-right: 10%; padding-bottom: 10px;"><strong>{{ $memberConsumer != null ? strtoupper(MemberConsumers::getAddress($memberConsumer)) : '-' }}</strong></p>  
  <br>
  <p class="text-center">for having attended the</p>
  <p class="text-center ">
    <span class="pms">PRE-MEMBERSHIP SEMINAR</span>
  </p>
  <br>
  <p class="text-center"> held at 
    <strong style="border-bottom: 1px solid #343434; padding-bottom: 4px;">{{ strtoupper(env('APP_ADDRESS')) }}</strong> on 
    <strong style="border-bottom: 1px solid #343434; padding-bottom: 4px;">{{ strtoupper(date('F, Y', strtotime($memberConsumer->DateApplied))) }}</strong></p>
  <br>
  <br><br><br>
  <br>
  <div class="half">
      <p class="text-center" style="border-bottom: 1px solid #454545; padding-bottom: 3px; margin-right: 15%; margin-left: 15%;"><strong>{{ env('MEM_CERTIF_SECRETARY') }}</strong></p>
      <p class="text-center">Member-Consumer Educ. <br>& Training Officer</p>
  </div>

  <div class="half">
      <p class="text-center" style="border-bottom: 1px solid #454545; padding-bottom: 3px; margin-right: 15%; margin-left: 15%;"><strong>{{ env('ISD_MANAGER') }}</strong></p>
      <p class="text-center">Manager, ISD</p>
  </div>
    
</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>