@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>KPS Monitoring</h4>
                </div>
            </div>
        </div>
    </section>

    @include('tickets.ticket_crew_monitor')
@endsection

