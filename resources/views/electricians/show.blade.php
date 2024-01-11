@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Electricians Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('electricians.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <td>Electrician ID</td>
                            <td>{{ $electricians->id }}</td>
                        </tr>
                        <tr>
                            <td>Electrician Name</td>
                            <td><strong>{{ $electricians->Name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>{{ $electricians->Address }}</td>
                        </tr>
                        <tr>
                            <td>Contact Numbers</td>
                            <td>{{ $electricians->ContactNumber }}</td>
                        </tr>
                        <tr>
                            <td>ID Number</td>
                            <td>{{ $electricians->IDNumber }}</td>
                        </tr>
                        <tr>
                            <td>Bank</td>
                            <td>{{ $electricians->Bank }}</td>
                        </tr>
                        <tr>
                            <td>Bank Number</td>
                            <td>{{ $electricians->BankNumber }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Applications in this Electrician</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th>ID</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Date Applied</th>
                        <th>Connection Type</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($serviceConnections as $item)
                            <tr>
                                <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td><strong>{{ $item->ServiceAccountName }}</strong></td>
                                <td>{{ ServiceConnections::getAddress($item) }}</td>
                                <td>{{ date('F d, Y', strtotime($item->DateOfApplication)) }}</td>
                                <td>{{ $item->ConnectionApplicationType }}</td>
                                <td>{{ $item->Status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $serviceConnections->links() }}
            </div>
        </div>
    </div>
@endsection
