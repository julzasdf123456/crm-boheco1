@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
    <div class="row">
        {{-- PARAMS --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="margin-top: 3px;">
                <div class="card-body p-1">
                    <div class="row">
                        {{-- TICKET --}}
                        <div class="form-group col-sm-3">
                            <label for="Ticket">Ticket Type</label>
                            <select class="custom-select select2"  name="Ticket" id="Ticket">
                                <option value="All">All</option>
                                @foreach ($parentTickets as $items)
                                    <optgroup label="{{ $items->Name }}">
                                        @php
                                            $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->whereNotIn('Id', Tickets::getMeterRelatedComplainsId())->orderBy('Name')->get();
                                        @endphp
                                        @foreach ($ticketsRep as $item)
                                            <option value="{{ $item->id }}">{{ $item->Name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="form-group col-sm-2">
                            <label for="Status">Ticket Status</label>
                            <select name="Status" id="Status" class="form-control">
                                @foreach ($status as $item)
                                    <option value="{{ $item->Status }}">{{ $item->Status }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CREW --}}
                        <div class="form-group col-sm-2">
                            {!! Form::label('CrewAssigned', 'Crew Assigned:') !!}
                            <select name="CrewAssigned" id="CrewAssigned" class="form-control">
                                <option value="All">All</option>
                                @foreach ($crew as $item)
                                    <option value="{{ $item->id }}">{{ $item->StationName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Actions</label><br>
                            <button class="btn btn-primary" id="filter">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-4">
            <div class="card shadow-none" style="height: 75vh;">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm" id="res-table">
                        <thead>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MAP --}}
        <div class="col-lg-8">
            <div id="map" style="width: 100%; height: 75vh;"></div>
        </div>
    </div>
    
@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [124.048419, 9.776509], // starting position [lng, lat], , 
            zoom: 10 // starting zoom
        });

        $(document).ready(function() {
            $('#filter').on('click', function() {
                getTickets()
            })
        })

        function getTickets() {
            $.ajax({
                url : 
            })
        }
    </script>
@endpush