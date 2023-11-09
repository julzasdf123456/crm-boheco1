@php    
    use App\Models\MiscellaneousApplications;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Service Drop Purchase Request</h4>
            </div>            
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item" title="Service Drop Purchasing Request Home"><a href="{{ route('miscellaneousApplications.service-drop-purchasing') }}">SDPR Home</a></li>
                    <li class="breadcrumb-item"><a href="">View SDPR</a></li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- DETAILS --}}
    <div class="col-lg-8">
        <div class="card shadow-none">
            <div class="card-header">
                <h3 class="no-pads">{{ $miscellaneousApplication->ConsumerName }}</h3>
                <p class="no-pads">{{ strtoupper(MiscellaneousApplications::getAddress($miscellaneousApplication)) }}</p>
                <span class="badge bg-warning">{{ $miscellaneousApplication->Status }}</span>

                <div class="card-tools">
                    <a href="" class="btn btn-tool"><i class="fas fa-pen"></i></a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted">SDW Length</td>
                            <td><strong>{{ $miscellaneousApplication->ServiceDropLength }}</strong> meters</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Price per Meter</td>
                            <td><strong>₱ {{ number_format($miscellaneousPayment->PricePerQuantity, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Amount</td>
                            <td><strong>₱ {{ number_format(($miscellaneousApplication->ServiceDropLength * $miscellaneousPayment->PricePerQuantity), 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">VAT (12%)</td>
                            <td><strong>₱ {{ number_format(($miscellaneousApplication->ServiceDropLength * $miscellaneousPayment->PricePerQuantity) * .12, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Amount</td>
                            <td class="text-success"><strong>₱ {{ number_format($miscellaneousApplication->TotalAmount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- LOGS --}}
    <div class="col-lg-4">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Logs</span>
            </div>
            <div class="card-body">
                <div class="timeline timeline-inverse">
                    @if ($logs == null)
                        <p><i>No logs recorded</i></p>
                    @else
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($logs as $item)
                            <div class="time-label" style="font-size: .9em !important;">
                                <span class="{{ $i==0 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->Log }}
                                </span>
                            </div>
                            <div>
                            <i class="fas fa-info-circle bg-primary"></i>
            
                            <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> {{ date('h:i A', strtotime($item->created_at)) }}</span>
            
                                    <p class="timeline-header"  style="font-size: .9em !important;"><a href="">{{ date('F d, Y', strtotime($item->created_at)) }}</a> by {{ $item->name }}</p>
            
                                    @if ($item->LogDetails != null)
                                        <div class="timeline-body" style="font-size: .9em !important;">
                                            {{ $item->LogDetails }}
                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection