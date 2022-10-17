<?php
    use App\Models\IDGenerator;
    use App\Models\ServiceConnections;
?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong><span class="badge-lg bg-danger">Step 7</span>Service Connection - Meter and Transformer Assignment</strong></p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="content px-3">

                @include('adminlte-templates::common.errors')

                <div class="card shadow-none">

                    <div class="card-header">
                        <span class="card-title"><strong><a href="{{ route('serviceConnections.show', [$serviceConnection->id]) }}">{{ $serviceConnection->ServiceAccountName }}</a></strong> | {{ ServiceConnections::getAddress($serviceConnection) }}</span>
                    </div>

                    {!! Form::open(['route' => 'serviceConnectionMtrTrnsfrmrs.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <!-- HIDDEN INPUTS -->
                            <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">

                            <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                            @include('service_connection_mtr_trnsfrmrs.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
             </div>
        </div>
    </div>
@endsection
