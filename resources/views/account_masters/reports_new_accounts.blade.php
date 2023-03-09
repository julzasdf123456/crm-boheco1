@php
    use App\Models\ServiceConnections;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Newly Migrated Accounts</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'accountMasters.reports-new-accounts', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="From">From</label>
                        {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'From', 'required' => 'true']) !!}
                    </div>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#From').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-2">
                        <label for="To">To</label>
                        {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'To', 'required' => 'true']) !!}
                    </div>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#To').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-md-3">
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check ico-tab"></i>View</button>
                        <button class="btn btn-sm btn-warning" id="print"><i class="fas fa-print ico-tab"></i>Print</button>
                    </div>
                </div>
                
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm" id="results-table">
                    <thead>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Meter Number</th>
                        <th>Route</th>
                        <th>Sequence</th>
                        <th>Consumer Type</th>
                        <th>Account ID</th>
                        <th>Contact Number</th>
                        <th>Date Entry</th>
                        <th>User</th>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $item)
                            <tr>
                                <td>{{ $item->AccountNumber }}</a></td>
                                <td><strong>{{ $item->ConsumerName }}</strong></td>
                                <td>{{ $item->ConsumerAddress }}</td>
                                <td>{{ $item->MeterNumber }}</td>
                                <td>{{ $item->Route }} </td>
                                <td>{{ $item->SequenceNumber }}</td>
                                <td>{{ $item->ConsumerType }}</td>
                                <td>{{ $item->UniqueID }}</td>
                                <td>{{ $item->ContactNumber }}</td>
                                <td>{{ $item->DateEntry != null ? date('M d, Y h:i A', strtotime($item->DateEntry)) : '' }}</td>
                                <td>{{ $item->UserName }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#print').on('click', function(e) {
                e.preventDefault()

                window.location.href = "{{ url('/account_masters/print-new-accounts') }}" + "/" + $('#From').val() + "/" + $('#To').val()
            })
        })
    </script>
@endpush