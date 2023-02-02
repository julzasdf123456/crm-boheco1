@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <a href="{{ route('serviceAccounts.accounts-map-view') }}" class="btn btn-xs btn-warning float-right"><i class="fas fa-map-marker-alt ico-tab-mini"></i>Go to Map View</a>
            {!! Form::open(['route' => 'accountMasters.index', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Search" name="params" value="{{ isset($_GET['params']) ? $_GET['params'] : '' }}">
                    </div>
                    <div class="col-md-3">
                        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <table class="table table-hover table-sm">
            <thead>
                <th>Account Number</th>
                <th>Service Account Name</th>
                <th>Address</th>
                <th>Meter Number</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($serviceAccounts as $item)
                    <tr>
                        <td>
                            <a href="{{ route('accountMasters.show', [$item->AccountNumber]) }}">
                                {{ $item->AccountNumber }}
                            </a>
                        </td>
                        <td>{{ $item->ConsumerName }}</td>                
                        <td>{{ $item->ConsumerAddress }}</td>          
                        <td>{{ $item->MeterNumber }}</td>
                        <td width="120">
                            {{-- {!! Form::open(['route' => ['serviceAccounts.destroy', $item->id], 'method' => 'delete']) !!} --}}
                            <div class='btn-group'>
                                <a href="{{ route('accountMasters.show', [$item->AccountNumber]) }}"
                                   class='btn btn-primary btn-xs'>
                                    <i class="far fa-eye ico-tab-mini"></i>View
                                </a>
                                {{-- <a href="{{ route('serviceAccounts.edit', [$item->id]) }}"
                                   class='btn btn-default btn-xs'>
                                    <i class="far fa-edit"></i>
                                </a>
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                            </div>
                            {{-- {!! Form::close() !!} --}}
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        
        {{ $serviceAccounts->links() }}
    </div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#old-account-no').focus()

            $("#old-account-no").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });
        })
    </script>
@endpush

