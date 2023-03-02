@extends('layouts.app')

@push('page_css')
    
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Create Change Meter - Search Consumer</h4>
            </div>
        </div>
    </div>
</section> 

<div class="content">
    <div class="row">
        <div class="container-fluid">
            {!! Form::open(['route' => 'tickets.change-meter', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-6 offset-lg-2">
                        <input type="text" class="form-control" placeholder="Search" name="params" value="{{ old('params') }}" autofocus>
                    </div>
                    <div class="col-md-2">
                        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('tickets.create-change-meter', ['0']) }}" class="btn btn-warning" style="margin-left: 20px;">Skip</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <table class="table table-hover table-sm">
            <thead>
                <th>Account Number</th>
                <th>Service Account Name</th>
                <th>Address</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($serviceAccounts as $item)
                    <tr>
                        <td>{{ $item->AccountNumber }}</td>
                        <td>{{ $item->ConsumerName }}</td>                
                        <td>{{ $item->ConsumerAddress }}</td>
                        <td width="120">
                            <div class='btn-group'>
                                <a href="{{ route('tickets.create-change-meter', [$item->AccountNumber]) }}"
                                   class='btn btn-primary btn-sm'>Go 
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                {{-- <a href="{{ route('serviceAccounts.edit', [$item->id]) }}"
                                   class='btn btn-default btn-xs'>
                                    <i class="far fa-edit"></i>
                                </a>
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                            </div>
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        
        {{ $serviceAccounts->links() }}
    </div>
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
