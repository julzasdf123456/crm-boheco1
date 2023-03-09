@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
@if ($meters != null)
    @push('page_scripts')
        <script>
            Swal.fire({
                icon : 'warning',
                text : 'This Meter Number already exists!'
            })
        </script>
    @endpush
@endif
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;"><strong class="text-danger">Step 2. </strong>Account Migration Wizzard</h4>
                    <i class="text-muted">Import meter details and assess computation</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    @if ($meters != null)
        <div class="col-lg-12">
            <div class="card bg-danger">
                <div class="card-body">
                    <p><strong>Meter Already Exists!</strong></p>
                    <span>Owner: {{ $meterOwner != null ? $meterOwner->ConsumerName : '-' }} (Acct. No: {{ $meterOwner != null ? $meterOwner->AccountNumber : '-' }})</span>
                </div>
            </div>
        </div>
    @endif

    <div class="col-lg-3 col-md-4">
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
    </div>

    <div class="col-lg-9 col-md-8">
        @include('flash::message')
        <div class="card shadow-none">
            {!! Form::open(['route' => 'meters.store']) !!}
            <div class="card-body">
                @include('adminlte-templates::common.errors')
                {{-- HIDDEN FIELDS --}}
                <input type="hidden" value="{{ $serviceAccount->AccountNumber }}" name="AccountNumber">
                <input type="hidden" value="{{ $serviceConnection->id }}" name="ServiceConnectionId">
                <input type="hidden" name="MeterDigits" value="5">
                <input type="hidden" name="ChargingMode" value="ENERGY">
                <input type="hidden" name="MeterStatus" value="ACTIVE">

                <div class="row">
                    @include('meters.fields')
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary btn-sm float-right']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection