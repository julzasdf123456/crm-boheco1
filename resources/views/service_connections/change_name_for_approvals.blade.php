@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@push('page_css')
   <style>
      p, h4, h3, h1, h2 {
         padding: 0 !important;
         margin: 0 !important;
      }
   </style>
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Change Name Applicants For Approval</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">    
   @include('flash::message')
   @foreach ($data as $item)
       <div class="col-lg-12" id="{{ $item->id }}">
         <div class="card shadow-none">
            <div class="card-body">
               <div class="row">
                  {{-- FROM --}}
                  <div class="col-lg-6">
                     <span class="text-muted">From:</span>
                     <p style="font-size: 1.2em;"><strong>{{ $item->OrganizationAccountNumber }}</strong></p>
                     <p><span class="text-muted">Account Number : </span> <strong> {{ $item->AccountNumber }}</strong></p>
                     <p><span class="text-muted">Address : </span> <strong> {{ ServiceConnections::getAddress($item) }}</strong></p>
                     <p><span class="text-muted">Contact No. : </span> <strong> {{ $item->ContactNumber }}</strong></p>
                  </div>

                  {{-- FROM --}}
                  <div class="col-lg-6">
                     <span class="text-muted">To:</span>
                     <p style="font-size: 1.2em;" class="text-primary"><strong>{{ $item->ServiceAccountName }}</strong></p>
                     <p><span class="text-muted">Relationship : </span> <strong> {{ $item->ResidenceNumber }}</strong></p>
                     <p><span class="text-muted">Reason for Changing : </span> <strong> {{ $item->Notes }}</strong></p>
                     <p><span class="text-muted">Date Applied : </span> <strong> {{ $item->DateOfApplication != null ? date('M d, Y', strtotime($item->DateOfApplication)) : '-' }}</strong></p>
                  </div>

                  <div class="col-lg-12">
                     <div class="divider"></div>
                     <button onclick="approveChangeName(`{{ $item->id }}`)" class="btn btn-sm btn-success float-right" style="margin-left: 10px;"><i class="fas fa-check-circle ico-tab"></i>Approve</button>
                     <a target="_blank" href="{{ route('serviceConnections.show', [$item->id]) }}" class="btn btn-default btn-sm float-right"><i class="fas fa-eye ico-tab"></i>View Application</a>
                  </div>
               </div>
            </div>
         </div>
       </div>
   @endforeach
</div>
@endsection

@push('page_scripts')
   <script>
      function approveChangeName(id) {
         $.ajax({
            url : "{{ route('serviceConnections.update-status') }}",
            type : "GET",
            data : {
               id : id,
               Status : 'Approved for Change Name'
            },
            success : function(res) {
               Toast.fire({
                  icon : 'success',
                  text : 'Application approved for change name!'
               })
               $('#' + id).remove()
            },
            error : function(err) {
               Toast.fire({
                  icon : 'error',
                  text : 'Error approving application!'
               })
            }
         })
      }
   </script>
@endpush