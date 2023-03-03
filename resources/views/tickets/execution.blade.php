@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h4>Tickets | Execution Updating <span class="text-muted" style="font-size: .7em; margin-left: 10px;">Press <strong>F3</strong> to search</span></h4>
            </div>
            <div class="col-lg-6">
                {!! Form::open(['route' => 'tickets.execution', 'method' => 'GET']) !!}
                <button type="submit" class="btn btn-sm btn-primary float-right" style="margin-left: 10px;"><i class="fas fa-check ico-tab-mini"></i>View</button>
                <select name="Crew" id="Crew" class="float-right form-control form-control-sm" style="width: 250px; margin-left: 10px;">
                    <option value="All">All</option>
                    @foreach ($crews as $item)
                    <option value="{{ $item->id }}">{{ $item->CrewLeader }} - {{ $item->StationName }}</option>
                    @endforeach                     
                </select>
                <label for="Crew" class="float-right">Filter Crew</label>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Ticket No/Acct.No</th>
                            <th>Consumer Name/Address</th>
                            <th>Complain</th>
                            <th>Date Filed/Status</th>
                            <th>Date/Time of Arrival</th>
                            <th>Date/Time Executed</th>
                            <th>Edit Status</th>
                            <th width="40px"></th>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $item)
                                <tr id="{{ $item->id }}">
                                    <td>
                                        <strong><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></strong>
                                        <br>
                                        <span class="text-muted" style="font-size: .9em;">{{ $item->AccountNumber }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item->ConsumerName }}</strong>
                                        <br>
                                        <span class="text-muted" style="font-size: .9em;">{{ Tickets::getAddress($item) }}</span>
                                    </td>
                                    <th>
                                        <strong>{{ $item->Ticket }}</strong>
                                        <br>
                                        <span class="text-muted" style="font-size: .9em;">{{ $item->ParentTicket }}</span>
                                    </th>
                                    <td>
                                        <span class="text-muted">{{ date('M d, Y', strtotime($item->created_at)) }}</span>
                                        <br>
                                        <span class="badge bg-info">{{ $item->Status }}</span>
                                    </td>
                                    <td>
                                       <input type="datetime-local" class="form-control form-control-sm" id="arrival-{{ $item->id }}" placeholder="Input date of arrival">                                       
                                    </td>
                                    <td>
                                        <input type="datetime-local" class="form-control form-control-sm" id="executed-{{ $item->id }}" placeholder="Input date executed">                                        
                                     </td>
                                    <td>
                                        <select id="status-{{ $item->id }}" class="form-control form-control-sm">
                                            <option value="Executed">Executed</option>
                                            <option value="Acted">Acted</option>
                                            <option value="Not Executed">Not Executed</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button onclick="update('{{ $item->id }}')" class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function update(id) {
            var arrival = moment($('#arrival-' + id).val()).format('YYYY-MM-DD HH:mm:ss')
            var executed = moment($('#executed-' + id).val()).format('YYYY-MM-DD HH:mm:ss')
            var status = $('#status-' + id).val()
            
            $.ajax({
                url : "{{ route('tickets.update-execution-data') }}",
                type : 'GET',
                data : {
                    id : id,
                    Arrival : arrival,
                    Executed : executed,
                    Status : status,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Execution udpated!',
                    })
                    $('#' + id).remove()
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error updating status!'
                    })
                }
            })
        }
    </script>
@endpush