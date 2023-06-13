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
                    <h4 style="display: inline; margin-right: 15px;">New Energized Rewiring</h4>
                    <i class="text-muted">Energized rewiring service connection accounts for activation</i>
                </span>
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
        <form action="{{ route('serviceConnections.new-energized-rewiring') }}" method="GET" class="col-lg-12">
         <div class="card shadow-none">
            <div class="card-body">
               <div class="row">
                  <div class="col-lg-2">
                     <label for="From">From</label>
                     <input type="text" class="form-control form-control-sm" id="From" name="From" required value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}">
                     @push('page_scripts')
                        <script type="text/javascript">
                           $('#From').datetimepicker({
                                 format: 'YYYY-MM-DD',
                                 useCurrent: true,
                                 sideBySide: true
                           })
                        </script>
                     @endpush
                  </div>
                  <div class="col-lg-2">
                     <label for="To">To</label>
                     <input type="text" class="form-control form-control-sm" id="To" name="To" required value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}">
                     @push('page_scripts')
                        <script type="text/javascript">
                           $('#To').datetimepicker({
                                 format: 'YYYY-MM-DD',
                                 useCurrent: true,
                                 sideBySide: true
                           })
                        </script>
                     @endpush
                  </div>
                  <div class="col-lg-2">                     
                     <label for="Office">Select Office</label>
                     <select name="Office" id="Office" class="form-control form-control-sm">
                        <option value="All">All</option>
                        <option value="MAIN OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                        <option value="SUB-OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                    </select>
                  </div>
                  <div class="col-lg-4">
                     <label for="print">Action</label><br>
                     <button class="btn btn-sm btn-primary" style="margin-left: 10px;" type="submit">Filter</button>
                     {{-- <button class="btn btn-sm btn-warning" id="print">Print</button> --}}
                  </div>
               </div>
            </div>
         </div>
         </form> 

        <div class="col-lg-12">
            <table class="table table-hover table-sm">
                <thead>
                    <th width="3%"></th>
                    <th>Account No.</th>
                    <th>Account Name</th>
                    <th>Account Address</th>
                    <th>Account Type</th>
                    <th>Meter Number</th>
                    <th width="8%"></th>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($serviceConnections as $item)
                        <tr id="{{ $item->id }}">
                           <th>{{ $i }}</th>
                           <td><a href="{{ route('serviceConnections.show', [$item->id]) }}" target="_blank">{{ $item->id }}</a></td>
                           <td>{{ $item->ServiceAccountName }} ({{ $item->AccountCount }})</td>
                           <td>{{ ServiceConnections::getAddress($item) }}</td>
                           <td>{{ $item->AccountType }} ({{ $item->Alias }})</td>
                           <td>{{ $item->MeterSerialNumber }}</td>
                           <td class="text-right">
                              <button class="btn btn-pimary btn-success btn-xs" onclick="confirm('{{ $item->id }}')"><i class="fas fa-check-circle"></i></button>
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
      //   $(document).ready(function() {
      //       $('#print').on('click', function(e) {
      //           e.preventDefault()
      //           window.location.href = "{{ url('/account_masters/print-sdir') }}" + "/" + $('#Office').val()
      //       })
      //   })

      function confirm(id) {
         $.ajax({
            url : "{{ route('serviceConnections.update-status') }}",
            type : "GET",
            data : {
               id : id,
               Status : 'Closed'
            },
            success : function(res) {
               Toast.fire({
                  icon : 'success',
                  text : 'Rewiring application closed',
               })
               $('#' + id).remove()
            },
            error : function(err) {
               Toast.fire({
                  icon : 'error',
                  text : 'Error closing rewiring',
               })
            }
         })
      }
    </script>
@endpush