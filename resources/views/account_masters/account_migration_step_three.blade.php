@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;"><strong class="text-info">Step 3. </strong>Account Migration Wizzard</h4>
                    <i class="text-muted"> Import transformer details</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- INFO CARDS --}}
    <div class="col-lg-3 col-md-4">
        {{-- CONSUMER INFO --}}
        <div class="card shadow-none">
            <div class="card-header border-0">
                <span class="card-title">Consumer Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-borderless table-sm">
                    <tr>
                        <th><i class="fas fa-user-circle ico-tab"></i>{{ $serviceAccount->ConsumerName }}</th>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-marker-alt ico-tab"></i>{{ $serviceAccount->ConsumerAddress }}</td>
                    </tr>
                    <tr>
                        <td title="Account Number"><i class="fas fa-user-alt ico-tab"></i>{{ $serviceAccount->AccountNumber }}</td>
                    </tr>
                    <tr>
                        <td title="Area Code"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->Route }}</td>
                    </tr>
                    <tr>
                        <td title="Sequence Number"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->SequenceNumber }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- METER INFO --}}
        <div class="card shadow-none">
            <div class="card-header border-0">
                <span class="card-title">Meter Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                @if ($meter != null)
                    <table class="table table-hover table-borderless table-sm">
                        <tr>
                            <td>Brand</td>
                            <td>{{ $meter->Make }}</td>
                        </tr>
                        <tr>
                            <td>Serial No</td>
                            <td>{{ $meter->MeterNumber }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $meter->MeterStatus }}</td>
                        </tr>
                        <tr>
                            <td>Multiplier</td>
                            <td>{{ $meter->Multiplier }}</td>
                        </tr>
                        <tr>
                            <td>Connection Date</td>
                            <td>{{ date('F d, Y', strtotime($serviceAccount->ConnectionDate)) }}</td>
                        </tr>
                    </table>                    
                @endif
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="col-lg-9 col-md-8">
        <div class="card shadow-none">
            {!! Form::model($serviceAccount, ['route' => ['accountMasters.update', $serviceAccount->AccountNumber], 'method' => 'patch']) !!}
            <div class="card-body">
                @include('adminlte-templates::common.errors')
                <div class="row">
                    <input type="hidden" name="AccountNumber" value="{{ $serviceAccount->AccountNumber }}">
                    <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                    <!-- CoreLoss Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('CoreLoss', 'Core Loss:') !!}
                        {!! Form::number('CoreLoss', null, ['class' => 'form-control form-control-sm']) !!}
                    </div>

                    <!-- CoreLossKWHUpperLimit Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('CoreLossKWHUpperLimit', 'Core Loss kWh Upper Limit:') !!}
                        {!! Form::number('CoreLossKWHUpperLimit', null, ['class' => 'form-control form-control-sm']) !!}
                    </div>

                    <!-- TSFRental Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('TSFRental', 'Transformer Rental:') !!}
                        {!! Form::number('TSFRental', null, ['class' => 'form-control form-control-sm', 'step' => 'any']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Finish', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection