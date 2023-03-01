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
                <h4>Ticket Summary Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="row">
                {{-- TICKET --}}
                <div class="form-group col-lg-2">
                    <label for="Ticket">Select Ticket</label>
                    <select class="custom-select select2"  id="Ticket">
                        <option value="All">All</option>
                        @foreach ($parentTickets as $items)
                            <optgroup label="{{ $items->Name }}">
                                @php
                                    $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->orderBy('Name')->get();
                                @endphp
                                @foreach ($ticketsRep as $item)
                                    <option value="{{ $item->id }}">{{ $item->Name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                {{-- FROM --}}
                <div class="form-group col-lg-1">
                    <label for="From">From</label>
                    {!! Form::text('From', null, ['class' => 'form-control', 'placeholder' => 'Select Date', 'id' => 'From']) !!}
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
                <div class="form-group col-lg-1">
                    <label for="To">To</label>
                    {!! Form::text('To', null, ['class' => 'form-control', 'placeholder' => 'Select Date', 'id' => 'To']) !!}
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

                {{-- STATUS --}}
                <div class="form-group col-lg-2">
                    <label for="Status">Status</label>
                    <select id="Status" class="form-control">
                        <option value="All">All</option>
                        <option value="Received">Pending</option>
                        <option value="Acted">Acted</option>
                        <option value="Downloaded by Crew">Downloaded by Crew</option>
                        <option value="Executed">Executed</option>
                        <option value="Not Executed">Not Executed</option>
                    </select>
                </div>

                {{-- AREA --}}
                <div class="form-group col-lg-2">
                    <label for="Office">Area</label>
                    <select id="Office" name="Office" class="form-control">
                        <option value="All">All</option>
                        <option value="MAIN OFFICE">Main Office</option>
                        <option value="SUB-OFFICE">Sub Office</option>
                    </select>
                </div>

                {{-- BUTTONS --}}
                <div class="form-group col-lg-3">
                    <label style="opacity: 0; width: 100%;">Action</label>
                    <button class="btn btn-primary" id="filterBtn" title="Filter"><i class="fas fa-check"></i></button>
                    <button class="btn btn-success" id="downloadBtn" title="Download Excel"><i class="fas fa-download"></i></button>
                    <div id="loader" class="spinner-border text-info gone" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="col-lg-12">
            <table class="table table-hover table-sm table-bordered" id="results-table">
                <thead>
                    <th>Ticket No</th>
                    <th>Account No.</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Complaint/Request</th>
                    <th>Status</th>
                    <th>Date Recorded</th>
                    <th>Date Executed</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#filterBtn').on('click', function() {
                $('#loader').removeClass('gone')
                $('#results-table tbody tr').remove()
                $.ajax({
                    url : '{{ route("tickets.get-ticket-summary-report") }}',
                    type : 'GET',
                    data : {
                        TicketParam : $('#Ticket').val(),
                        From : $('#From').val(),
                        To : $('#To').val(),
                        Office : $('#Office').val(),
                        Status : $('#Status').val(),
                    },
                    success : function(res) {
                        $('#results-table tbody').append(res)
                        $('#loader').addClass('gone')
                    },
                    error : function(err) {
                        alert('An error occurred upon filtering your request')
                        $('#loader').addClass('gone')
                    }
                })
            })

            $('#downloadBtn').on('click', function() {
                $.ajax({
                    url : '/tickets/ticket-summary-report-download-route',
                    type : 'GET',
                    data : {
                        TicketParam : $('#Ticket').val(),
                        From : $('#From').val(),
                        To : $('#To').val(),
                        Office : $('#Office').val(),
                        Status : $('#Status').val(),
                    },
                    success : function(res) {
                        window.location.href = "{{ url('/tickets/download-tickets-summary-report') }}" + "/" + res['ticket'] + "/" + res['from'] + "/" + res['to'] + "/" + res['area'] + "/" + res['status']
                    },
                    error : function(err) {
                        alert('An error occurred upon downloading')
                    }
                })
            })
        })    
    </script>    
@endpush