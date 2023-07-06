@php
    use App\Models\ServiceConnections;
@endphp
@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Approved Change Names</h4>
                    <i class="text-muted">Approved Changed of Name Applications to be updated.</i>
                </span>
            </div>

            <div class="col-lg-6">
                <form action="{{ route('serviceConnections.approved-change-names') }}" method="GET">
                    {{-- <button class="btn btn-sm btn-warning float-right" id="print">Print</button> --}}
                    <button class="btn btn-sm btn-primary float-right" style="margin-right: 10px;" type="submit">Filter</button>
                    <select name="Office" id="Office" class="form-control form-control-sm float-right" style="width: 150px; margin-right: 10px; margin-left: 5px;">
                        <option value="All">All</option>
                        <option value="MAIN OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                        <option value="SUB-OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                    </select>
                    <label for="Office" class="float-right">Select Office</label>
                </form> 
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    
    <div class="row">
        @if(session()->has('message'))
            <div class="col-lg-12">
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            </div>
        @endif
        <div class="col-lg-12">
            <table class="table table-hover table-sm">
                <thead>
                    <th width="3%"></th>
                    <th>Application ID</th>
                    <th>Old Name</th>
                    <th>New Name</th>
                    <th>Account Address</th>
                    <th>Relationship</th>
                    <th>Reason for Changing</th>
                    <th>Approved At</th>
                    <th width="8%"></th>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($data as $item)
                        <tr id="{{ $item->id }}">
                            <th>{{ $i }}</th>
                            <td><a href="{{ route('serviceConnections.show', [$item->id]) }}" target="_blank">{{ $item->id }}</a></td>
                            <td>
                              {{ $item->OrganizationAccountNumber }}
                              <br>
                              <span class="text-muted">Account No: </span> <span><strong>{{ $item->AccountNumber }}</strong></span>
                           </td>
                            <td class="text-info"><strong>{{ $item->ServiceAccountName }}</strong></td>
                            <td>{{ ServiceConnections::getAddress($item) }}</td>
                            <td>{{ $item->ResidenceNumber }}</td>
                            <td>{{ $item->Notes }}</td>
                            <td>
                                {{ date('M d, Y', strtotime($item->updated_at)) }}
                                <br>
                                @php
                                    $past = new \DateTime($item->updated_at);
                                    $present = new \DateTime(date('Y-m-d H:i:s'));
                                    $days = $present->diff($past);
                                    if ($days->format('%a')==0) {
                                        $color = '#2e7d32';
                                    } elseif ($days->format('%a')==1) {
                                        $color = '#ff6f00';
                                    } elseif ($days->format('%a')==2) {
                                        $color = '#e64a19';
                                    } else {
                                        $color = '#c62828';
                                    }
                                @endphp 
                                <span class="badge" style="background-color: {{ $color }}; color: white;">
                                    {{ $days->format('%a days & %h hrs') }}
                                </span>
                            </td>
                            <td class="text-right" >
                              <button onclick="changeName(`{{ $item->id }}`)" class="btn btn-success btn-sm"><i class="fas fa-check-circle ico-tab"></i> Change</button>              
                            </td>
                        </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script>
        function changeName(id) {
         Swal.fire({
            title: 'Confirm change of name?',
            showCancelButton: true,
            confirmButtonText: 'Confirm Change',
         }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               $.ajax({
                  url : "{{ route('serviceConnections.change-account-name') }}",
                  type : "GET",
                  data : {
                     ServiceConnectionId : id, 
                  },
                  success : function(res) {
                     Toast.fire({
                        icon : 'success',
                        text : 'Name changed successfully!'
                     })
                     $('#' + id).remove()
                  },
                  error : function(err) {
                     Toast.fire({
                        icon : 'error',
                        text : 'Error changing name!'
                     })
                  }
               })
            } 
         })
         
        }
    </script>
@endpush