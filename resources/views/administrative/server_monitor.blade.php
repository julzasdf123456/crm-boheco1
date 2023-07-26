@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
            <div class="col-sm-12">
               <p><strong style="font-size: 1.5em; margin-right: 20px;"><i class="fas fa-server ico-tab"></i>Tracey</strong><span class='text-muted'>An integrated AI tool for monitoring servers.</span></p>
            </div>
      </div>
   </div>
</section>

<div class="row">
   {{-- SERVER RESOURCES MONITOR --}}
   <div class="col-lg-12">
      <div class="row">
         @foreach ($servers as $item)
            <div class="col-lg-4 col-md-6">
               <div class="card">
                  <div class="card-header">
                     <span class="card-title"><i class="fas fa-server ico-tab"></i>{{ $item->ServerName }} <span class="text-muted"> ({{ $item->ServerIp }})</span></span>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        {{-- CPU --}}
                        <div class="col-lg-4">
                           <p class="text-center" style="margin: 0px; padding: 0px;">
                              <input id="cpu-{{ $item->id }}" class="knob" data-readonly="true" value="0" data-width="60" data-height="60" data-fgColor="#39CCCC">
                           </p>
                           <p style="color: #39CCCC; margin: 0px; padding: 0px;" class="text-center">CPU</p>
                           <p id="cputext-{{ $item->id }}" style="color: #39CCCC; margin: 0px; padding: 0px; font-size: 1.2em;" class="text-center">...</p>
                        </div>

                        {{-- RAM --}}
                        <div class="col-lg-4">
                           <p class="text-center" style="margin: 0px; padding: 0px;">
                              <input id="ram-{{ $item->id }}" class="knob" data-readonly="true" value="0" data-width="60" data-height="60" data-fgColor="#cc397e">
                           </p>
                           <p style="color: #cc397e; margin: 0px; padding: 0px;" class="text-center">MEMORY</p>
                           <p id="ramtext-{{ $item->id }}" style="color: #cc397e; margin: 0px; padding: 0px; font-size: 1.2em;" class="text-center">...</p>
                        </div>

                        {{-- STORAGE --}}
                        <div class="col-lg-4">
                           <p class="text-center" style="margin: 0px; padding: 0px;">
                              <input id="storage-{{ $item->id }}" class="knob" data-readonly="true" value="0" data-width="60" data-height="60" data-fgColor="#7fb402">
                           </p>
                           <p style="color: #7fb402; margin: 0px; padding: 0px;" class="text-center">STORAGE</p>
                           <p id="storagetext-{{ $item->id }}" style="color: #cc397e; margin: 0px; padding: 0px; font-size: 1.2em;" class="text-center">...</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            @push('page_scripts')
               <script>
                  setInterval(function() {
                     $.ajax({
                        url : "http://{{ env('TRACEY_IP') }}:3000/get-active-server-stats",
                        type : "GET",
                        data : {
                           ServerId : "{{ $item->id }}",
                           From : encodeURIComponent(moment().subtract('seconds', 3).format("YYYY-MM-DD H:mm:ss")),
                           To : encodeURIComponent(moment().format("YYYY-MM-DD H:mm:ss"))
                        },
                        success : function(res) {
                           res = JSON.parse(res)
                           // console.log(res[0]['CpuPercentage'])
                           if (jQuery.isEmptyObject(res)) {
                              $('#cpu-{{ $item->id }}').val(0).change()
                              $('#cputext-{{ $item->id }}').text("UNREACHABLE").change()

                              $('#ram-{{ $item->id }}').val(0).change()
                              $('#ramtext-{{ $item->id }}').text("UNREACHABLE").change()

                              $('#storage-{{ $item->id }}').val(0).change()
                              $('#storagetext-{{ $item->id }}').text("UNREACHABLE").change()
                           } else {
                              $('#cpu-{{ $item->id }}').val(res[0]['CpuPercentage']).change()
                              $('#cputext-{{ $item->id }}').text(res[0]['CpuPercentage'] + " %").change()

                              var ramPercent = (parseFloat(res[0]['MemoryPercentage']) / parseFloat(res[0]['TotalMemory'])) * 100;

                              $('#ram-{{ $item->id }}').val(ramPercent).change()
                              $('#ramtext-{{ $item->id }}').text(res[0]['MemoryPercentage'] + "/" + res[0]['TotalMemory'] + " GB").change()

                              $('#storage-{{ $item->id }}').val(res[0]['DiskPercentage']).change()
                              $('#storagetext-{{ $item->id }}').text(res[0]['DiskPercentage'] + " %").change()
                           }
                        },
                        error : function(err) {
                           console.log(err)
                        }
                     })
                  }, 3000);
               </script>
            @endpush
         @endforeach         
      </div>
   </div>
</div>
@endsection