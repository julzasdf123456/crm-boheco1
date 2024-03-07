@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Meter Assigning</h4>
                </div>
                <div class="col-sm-6">
                    <form action="{{ route('serviceConnectionMtrTrnsfrmrs.assigning') }}" method="GET">
                        <button type="submit" class="float-right btn btn-sm btn-primary">Filter</button>
                        <select name="Options" id="Options" class="form-control form-control-sm float-right" style="width: 250px; margin-right: 10px;">
                            <option value="All" {{ isset($_GET['Options']) && $_GET['Options']=='All' ? 'selected' : '' }}>All New Applications</option>
                            <option value="WithOR" {{ isset($_GET['Options']) && $_GET['Options']=='WithOR' ? 'selected' : '' }}>With OR Only</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Applications</h3>
                  {{-- <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-bars"></i>
                    </a>
                  </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                    @if ($serviceConnections == null)
                        <p class="text-center"><i>No Service Connection Applications with Unassigned Meters.</i></p>
                    @else
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service Account Name</th>
                                    <th>Address</th>
                                    <th>Account Type</th>
                                    <th>ORNumber</th>
                                    <th width="35"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceConnections as $item)
                                    <tr>
                                        <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td>{{ ServiceConnections::getAddress($item) }}</td>
                                        <td>{{ $item->AccountType }}</td>
                                        <td>{{ $item->ORNumber }}</td>
                                        <td>
                                            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Metering Personnel', 'Energization Clerk'])) 
                                                <a href="{{ route('serviceConnectionMtrTrnsfrmrs.create-step-three', [$item->id]) }}" class="text-muted" title="Proceed Assigning"> <i class="fas fa-arrow-alt-circle-right"></i> </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection