@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>New Change Meter Energized</h4>
            </div>
            <div class="col-sm-6">
                {!! Form::open(['route' => 'tickets.change-meter-unconfirmed', 'method' => 'GET']) !!}

                <button type="submit" class="btn btn-primary btn-sm float-right" id="filterBtn" title="Filter"><i class="fas fa-check"></i> Filter</button>

                <select id="Office" name="Office" class="form-control form-control-sm float-right" style="width: 150px; margin-right: 10px;">
                    <option value="All" {{ isset($_GET['Office']) && $_GET['Office']=='All' ? 'selected' : '' }}>All</option>
                    <option value="MAIN OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                    <option value="SUB-OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                </select>
                
                <label for="Office" class="float-right" style="margin-right: 10px;">Office</label>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- TABLE --}}
        <div class="col-lg-12">
            <table class="table table-hover table-resposive table-sm table-bordered" id="results-table">
                <thead>
                    <th>Ticket ID</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Old Meter No.</th>
                    <th>Old Meter Rdng.</th>
                    <th>New Meter No.</th>
                    <th>New Meter Rdng.</th>
                    <th>Date Executed</th>
                    <td></td>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr id="{{ $item->id }}">
                            <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                            <td>{{ $item->ConsumerName }}</td>
                            <td>{{ Tickets::getAddress($item) }}</td>
                            <td>{{ $item->CurrentMeterNo }}</td>
                            <td class="text-danger"><strong>{{ $item->CurrentMeterReading }}</strong> kWh</td>
                            <td>{{ $item->NewMeterNo }}</td>
                            <td class="text-primary"><strong>{{ $item->NewMeterReading }}</strong> kWh</td>
                            <td>{{ date('M d, Y', strtotime($item->DateTimeLinemanExecuted)) }}</td>
                            <td>
                                <button id="btn-{{ $item->id }}" class="btn btn-primary btn-xs" onclick="confirm('{{ $item->id }}')" ticket_id="{{ $item->id }}">Confirm</button>
                            </td>
                        </tr>
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

        function confirm(id) {
            $.ajax({
                url : "{{ route('tickets.mark-as-change-meter-done') }}",
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Change meter marked as confirmed'
                    })
                    $('#' + id).remove()
                },
                error : function(err) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error confirming change meter'
                    })
                }
            })
        }
    </script>    
@endpush