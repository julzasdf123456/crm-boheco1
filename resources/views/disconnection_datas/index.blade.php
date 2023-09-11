@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Disconnection Analytics</h4>
            </div>
        </div>
    </div>
</section>

@include('disconnection_datas.analytics_disconnection')

@endsection


