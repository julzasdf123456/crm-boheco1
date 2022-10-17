@php
    use App\Models\ServiceAccounts;
@endphp
    
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            {!! Form::open(['route' => 'tickets.relocation-search', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-6 offset-md-3">
                        <input type="text" class="form-control" placeholder="Search Account # or Account Name" name="params" value="{{ old('params') }}">
                    </div>
                    <div class="col-md-3">
                        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
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
                <th></th>
            </thead>
            <tbody>
                @foreach ($serviceAccounts as $item)
                    <tr>
                        <td>{{ $item->AccountNumber }}</td>
                        <td>{{ $item->ConsumerName }}</td>                
                        <td>{{ $item->ConsumerAddress }}</td>          
                        <td>{{ $item->MeterNumber }}</td>
                        <td width="120">
                            <div class='btn-group'>
                                <a href="{{ route('serviceConnections.create-relocation', [$item->AccountNumber]) }}"
                                class='btn btn-primary btn-xs'>
                                    Relocate
                                </a>
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>

        {{ $serviceAccounts->links() }}
    </div>
@endsection