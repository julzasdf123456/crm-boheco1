@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Tickets | Execution Updating</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                {!! Form::open(['route' => 'tickets.execution', 'method' => 'GET']) !!}
                <div class="card-body">
                    <div class="row">
    
                        <div class="form-group col-md-3">
                            <label for="Office">Office</label>
                            <select name="Office" id="Office" class="form-control form-control-sm">
                                <option value="MAIN OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>    
                                <option value="SUB-OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>                            
                            </select>
                        </div>
    
                        <div class="form-group col-md-3">
                            <label for="Action">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check ico-tab-mini"></i>View</button>
                        </div>
                    </div>
                    
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <table class="table table-hover table-sm">
                <thead>
                    <th>Ticket No</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Complain</th>
                    <th>Datetime Complained</th>
                    <th>Office</th>
                    <th>Select Crew</th>
                    <th width="40px"></th>
                </thead>
                <tbody>
                    @if ($tickets != null)
                        @foreach ($tickets as $item)
                            @php
                                $ticketMain = TicketsRepository::find($item->TicketID);
                                $parent = TicketsRepository::where('id', $ticketMain->ParentTicket)->first();
                            @endphp
                            <tr id="{{ $item->id }}">
                                <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td><strong>{{ $item->ConsumerName }}</strong></td>
                                <td>{{ Tickets::getAddress($item) }}</td>
                                <th>{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $item->Ticket }}</th>
                                <td>{{ date('M d, Y h:m A', strtotime($item->created_at)) }}</td>
                                <td>{{ $item->Office }}</td>
                                <td>
                                    <select id="crew-{{ $item->id }}" class="form-control form-control-sm">
                                        <option value="">-</option>
                                        @foreach ($crew as $crews)
                                            <option value="{{ $crews->id }}">{{ $crews->StationName }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button onclick="changeCrew('{{ $item->id }}')" class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
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

        function changeCrew(id) {
            var crew = $('#crew-' + id).val()
            if (!jQuery.isEmptyObject(crew)) {
                $.ajax({
                    url : "/tickets/update-ordinary-ticket-assessment",
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id,
                        CrewAssigned : crew,
                    },
                    success : function(res) {
                        $('#' + id).remove()
                        Toast.fire({
                            icon : 'success',
                            text : 'Crew assigned successfully'
                        })
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'An error occurred while attempting to change crew. Contact support for more!'
                        })
                    }
                })
            } else {
                Toast.fire({
                    icon : 'warning',
                    text : 'No crew selected!'
                })
            }
            
        }
    </script>
@endpush