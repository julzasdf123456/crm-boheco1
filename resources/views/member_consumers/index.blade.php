@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class='col-lg-12 col-md-12'>
            <br>
            <h4 class="text-center display-5">Search Member Consumers</h4>
            <br>
            <form class="row" method="GET" action="{{ route('memberConsumers.index') }}">
                <!-- SEARCH BAR -->
                <div class="col-md-8 offset-md-2">
                    <div class="input-group">
                        <input type="search" id='searchparam' class="form-control" name="search" placeholder="Type Name or Member Consumer ID" autofocus value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default" id="searchBtn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="content px-3">
                <table class="table table-hover">
                    <thead>
                        <th>Membership ID</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Contact No.</th>
                        <th>Membership Type</th>
                        <th>Office</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <strong><a href="{{ route('memberConsumers.show', [$item->ConsumerId]) }}">{{ $item->ConsumerId }}</a></strong>
                                </td>
                                <td>
                                    <img src="{{ URL::asset('imgs/prof-icon.png'); }}" style="width: 30px; margin-right: 15px;" class="img-circle" alt="profile"><strong>{{ MemberConsumers::serializeMemberName($item) }}</strong></td>
                                <td>{{ MemberConsumers::getAddress($item) }}</td>    
                                <td>{{ $item->ContactNumbers }}</td>
                                <td>{{ $item->Type }}</td>              
                                <td><span class="badge {{ ServiceConnections::getOfficeBg($item->Office) }}">{{ $item->Office }}</span></td>     
                                <td class="text-right">
                                    {!! Form::open(['route' => ['memberConsumers.destroy', $item->ConsumerId], 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        <a href="{{ route('memberConsumers.show', [$item->ConsumerId]) }}"
                                        class='btn btn-default btn-xs'>
                                            <i class="far fa-eye"></i>
                                        </a>
                                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers'])) 
                                            <a href="{{ route('memberConsumers.edit', [$item->ConsumerId]) }}"
                                            class='btn btn-default btn-xs'>
                                                <i class="far fa-edit"></i>
                                            </a>
                                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                        @endif
                                    </div>
                                    {!! Form::close() !!}
                                </td>                  
                            </tr>                    
                        @endforeach
                    </tbody>
                </table>
        
                {{ $data->links() }}
            </div>
        </div>        
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

            // fetchConsumers('');

            // function fetchConsumers(query) {
            //     $.ajax({
            //         url : "{{ route('memberConsumers.fetch-member-consumers') }}",
            //         method : 'GET',
            //         data : { query : query },
            //         success : function(data) {
            //             $('#search-results').html(data);
            //             // console.log(query);
            //         }
            //     });
            // }

            // $('#searchparam').on('keyup', function() {
            //     if (this.value.length > 4) {
            //         fetchConsumers(this.value);
            //     }
                
            // });

            // $('#searchBtn').on('click', function() {
            //     fetchConsumers($('#searchparam').val());
            // });            
        });
    </script>
@endpush
