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
                    <p><strong><span class="badge-lg bg-warning">Step 5</span>Service Connection - Inspection and Staking</strong></p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-8">
            <div class="content px-3">
                @include('adminlte-templates::common.errors')

                <div class="card shadow-none">

                    <div class="card-header">
                        <span class="card-title">{{ $serviceConnection->ServiceAccountName }} | {{ ServiceConnections::getAddress($serviceConnection) }}</span>
                    </div>

                    {!! Form::open(['route' => 'serviceConnectionInspections.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <!-- HIDDEN INPUTS -->
                            <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">

                            <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                            <input type="hidden" name="Status" value="FOR INSPECTION">

                            @include('service_connection_inspections.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
             </div>
        </div>

        {{-- INSPECTIONS CURRENTLY IN QUEUE --}}
        <div class="col-lg-4">
            <div class="card shadow-none">
                <div class="card-header bg-info">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Verifiers Pending Inspections</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>Verifier</th>
                            <th>No. of Pending Insp.</th>
                        </thead>
                        <tbody>
                            @foreach ($pendingInspections as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->InspectionCount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
