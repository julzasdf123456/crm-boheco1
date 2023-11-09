@php
    use App\Models\MiscellaneousApplications;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Service Drop Purchasing Request</h4>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('miscellaneousApplications.create-service-drop-purchasing') }}" class="btn btn-primary btn-sm float-right">Create New Request <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="container-fluid">
            {!! Form::open(['route' => 'miscellaneousApplications.service-drop-purchasing', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-6 offset-md-3">
                        <input type="text" class="form-control" placeholder="Search Consumer Name" name="searchParams" value="{{ isset($_GET['searchParams']) ? $_GET['searchParams'] : '' }}" autofocus>
                    </div>
                    <div class="col-md-3">
                        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="col-lg-10 offset-lg-1 col-md-12">
        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>SDW Length</th>
                        <th>Total Amount</th>
                        <th>Date Logged</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($miscellaneousApplications as $item)
                            <tr>
                                <td><strong>{{ $item->ConsumerName }}</strong></td>   
                                <td>{{ MiscellaneousApplications::getAddress($item) }}</td>   
                                <td>{{ $item->ServiceDropLength }} meters</td>     
                                <td>{{ number_format($item->TotalAmount, 2) }}</td>        
                                <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>     
                                <td class="text-right">
                                    <a href="{{ route('miscellaneousApplications.service-drop-purchasing-view', [$item->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye ico-tab-mini"></i>View</a>
                                </td>            
                            </tr>                    
                        @endforeach
                    </tbody>
                </table>
                {{ $miscellaneousApplications->links() }}
            </div>
        </div>
    </div>
</div>

@endsection