@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Unexecuted Tickets</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <form class="col-lg-12" action="{{ route('tickets.not-executed') }}" method="GET">
            <div class="row">
                {{-- FROM --}}
                <div class="form-group col-lg-2">
                    <label for="From">From</label>
                    {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : null, ['class' => 'form-control form-control-sm', 'required' => true, 'placeholder' => 'Select Date', 'id' => 'From']) !!}
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

                {{-- TO --}}
                <div class="form-group col-lg-2">
                    <label for="To">To</label>
                    {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : null, ['class' => 'form-control form-control-sm', 'required' => true, 'placeholder' => 'Select Date', 'id' => 'To']) !!}
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

                {{-- AREA --}}
                <div class="form-group col-lg-2">
                    <label for="Office">Area</label>
                    <select id="Office" name="Office" class="form-control form-control-sm">
                        <option value="All">All</option>
                        <option value="MAIN OFFICE">Main Office</option>
                        <option value="SUB-OFFICE">Sub Office</option>
                    </select>
                </div>

                {{-- BUTTONS --}}
                <div class="form-group col-lg-3">
                    <label style="opacity: 0; width: 100%;">Action</label>
                    <button class="btn btn-primary btn-sm" id="filterBtn" title="Filter"><i class="fas fa-check"></i> Filter</button>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="col-lg-12">
            <table class="table table-hover table-sm table-bordered">
                <thead>
                     <th></th>
                    <th>Ticket No</th>
                    <th>Account No.</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Complaint/Request</th>
                    <th>Status</th>
                    <th>Date Complained</th>
                </thead>
                <tbody>
                  @php
                      $i = 1;
                  @endphp
                  @foreach ($data as $item)
                     <tr>
                        <td>{{ $i }}</td>
                        <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                        <td>{{ $item->AccountNumber }}</td>
                        <td>{{ $item->ConsumerName }}</td>
                        <td>{{ Tickets::getAddress($item) }}</td>
                        <td>{{ $item->ParentTicket }} - {{ $item->Ticket }}</td>
                        <td>{{ $item->Status }}</td>
                        <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
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
        $(document).ready(function() {
            
        })    
    </script>    
@endpush