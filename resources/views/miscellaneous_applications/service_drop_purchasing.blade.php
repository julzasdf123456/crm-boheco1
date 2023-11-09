@php
    
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

@endsection