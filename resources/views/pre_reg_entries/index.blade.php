@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>AGMA 2023 Pre-Registration Entries</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="content-header">
            <div class="container-fluid">
                {!! Form::open(['route' => 'preRegEntries.index', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Search" name="params" value="{{ isset($_GET['params']) ? $_GET['params'] : '' }}">
                        </div>
                        <div class="col-md-3">
                            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    
        <div class="content px-3">
    
            @include('flash::message')
    
            <div class="clearfix"></div>

            <h4>{{ $total->Count }} Entries</h4>
    
            @include('pre_reg_entries.table')
        </div>
    </div>

@endsection

