@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class='col-lg-12 col-md-12'>
            <br>
            <h4 class="text-center display-5">Select Member Consumer</h4>
            <br>
            <div class="row">
                <!-- SEARCH BAR -->
                <div class="col-md-8 offset-md-2">
                    <form action="{{ route('serviceConnections.selectmembership') }}" method="GET">
                    <div class="input-group">
                        <input type="text" id='search' name="search" class="form-control" autofocus placeholder="Type Name or Member Consumer ID" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default" id="searchBtn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>

            <div class="content px-3">
                <table class="table table-hover">
                    <thead>
                        <th>Membership ID</th>
                        <th>MCO Name</th>
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
                                <td>
                                    <a href="{{ route('serviceConnections.create_new', [$item->ConsumerId]) }}" class="float-right btn btn-sm btn-success">Go <i class="fas fa-arrow-right"></i></a>    
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

            // function fetchConsumers(query = '') {
            //     $.ajax({
            //         url : "{{ route('serviceConnections.fetch-member-consumers') }}",
            //         method : 'GET',
            //         dataType : 'json',
            //         data : { query : query },
            //         success : function(data) {
            //             $('#search-results').html(data.table_data);
            //             // console.log(query);
            //         }
            //     });
            // }

            // $('#searchparam').on('keyup', function() {
            //     fetchConsumers(this.value);
            // });

            // $('#searchBtn').on('click', function() {
            //     fetchConsumers($('#searchparam').val());
            // });            
        });
    </script>
@endpush