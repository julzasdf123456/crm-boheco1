@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        {!! Form::open(['route' => 'tickets.create-select', 'method' => 'GET']) !!}
            <div class="row mb-2">
                <div class="col-md-6 offset-md-3">
                    <input type="text" class="form-control" placeholder="Search Account #, Account Name, or Meter No" name="params" value="{{ isset($_GET['params']) ? $_GET['params'] : '' }}" autofocus>
                </div>
                <div class="col-md-3">
                    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('tickets.create-new', ['0']) }}" class="btn btn-warning" style="margin-left: 20px;">Skip</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</section>

<div class="content px-3">
    <table class="table table-hover">
        <thead>
            <th>AccountNumber</th>
            <th>Service Account Name</th>
            <th>Address</th>
            <th>Meter Number</th>
        </thead>
        <tbody>
            @foreach ($serviceAccounts as $item)
                <tr>
                    <td><a href="{{ route('tickets.create-new', [$item->AccountNumber]) }}">{{ $item->AccountNumber }}</a></td>
                    <td><a href="{{ route('tickets.create-new', [$item->AccountNumber]) }}">{{ $item->ConsumerName }}</a></td>                
                    <td><a href="{{ route('tickets.create-new', [$item->AccountNumber]) }}">{{ $item->ConsumerAddress }}</a></td>          
                    <td><a href="{{ route('tickets.create-new', [$item->AccountNumber]) }}">{{ $item->MeterNumber }}</a></td>
                </tr>
                
            @endforeach
        </tbody>
    </table>

    {{ $serviceAccounts->links() }}
</div>
@endsection
