@php
    use App\Models\Tickets;
    use App\Models\ServiceConnections;
@endphp
@extends('layouts.app')

@section('content')
<div class="row">
    <div class='col-lg-12 col-md-12'>
        <br>
        <h4 class="text-center">Search Tickets</h4>
        <br>
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-8 offset-md-2">
                <form action="{{ route('tickets.index') }}" method="GET">
                <div class="input-group">
                    <input type="search" id='searchparam' name="params" class="form-control" placeholder="Type Name or Ticket Order Number" value="{{ isset($_GET['params']) ? $_GET['params'] : '' }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default" id="searchBtn">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <!-- SEARCH RESULTS -->
        <div class="content px-3">
            <table class="table table-hover">
                <thead>
                    <th>Ticket ID</th>
                    <th>Consumer Name</th>
                    <th>Ticket</th>
                    <th>Address</th>
                    <th>Account No</th>
                    <th>Status</th>
                    <th>Office</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td><strong><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></strong>
                            </td>
                            <td>
                                <img src="{{ URL::asset('imgs/prof-icon.png'); }}" style="width: 30px; margin-right: 15px;" class="img-circle" alt="profile"><strong>
                                    {{ $item->ConsumerName }}</strong></td>
                            <td>{{ $item->ParentTicket }}-<strong>{{ $item->Ticket }}</strong></td>    
                            <td>{{ Tickets::getAddress($item) }}</td>
                            <td>{{ $item->AccountNumber }}</td>   
                            <td>{{ $item->Status }}</td>              
                            <td><span class="badge {{ ServiceConnections::getOfficeBg($item->Office) }}">{{ $item->Office }}</span></td>     
                            <td>
                                {{-- <a href="{{ route('tickets.show', [$item->id]) }}" class="float-right"><i class="fas fa-eye"></i></a>    --}}
                                
                                @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'CWD'])) 
                                {!! Form::open(['route' => ['tickets.destroy', $item->id], 'method' => 'delete']) !!}
                                    <div class='btn-group float-right'>
                                        @if ($item->Status=="Executed")
                                        
                                        @else
                                        
                                        <a href="{{ route('tickets.print-ticket', [$item->id]) }}" class="btn btn-warning btn-xs" title="Re-print Ticket Order"><i class="fas fa-print"></i></a>
                                        <a href="{{ route('tickets.edit', [$item->id]) }}"
                                            class='btn btn-default btn-xs'>
                                                <i class="far fa-edit"></i>
                                            </a>
                                        @endif                                        
                                        
                                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure you want to delete this ticket?')"]) !!}                
                                    </div>
                                {!! Form::close() !!}   
                                @endif                             
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
        // $(document).ready(function() {

        //     fetchConsumers('');

        //     function fetchConsumers(query = '') {
        //         $('#loader').removeClass('gone');
        //         $.ajax({
        //             url : "{{ route('tickets.fetch-tickets') }}",
        //             method : 'GET',
        //             dataType : 'json',
        //             data : { query : query },
        //             success : function(data) {
        //                 $('#search-results').html(data.table_data);
        //                 $('#loader').addClass('gone');
        //             },
        //             error : function(error) {
        //                 console.log(error)
        //                 $('#loader').addClass('gone');
        //             }
        //         });
        //     }

        //     $('#searchparam').on('keyup', function() {
        //         $('#loader').removeClass('gone');
        //         var aSearch = $.ajax({
        //             url : "{{ route('tickets.fetch-tickets') }}",
        //             method : 'GET',
        //             dataType : 'json',
        //             data : { query : this.value },
        //             success : function(data) {
        //                 $('#search-results').html(data.table_data);
        //                 $('#loader').addClass('gone');
        //             },
        //             beforeSend : function() {
        //                 if (aSearch != null) {
        //                     $('#loader').removeClass('gone');
        //                     aSearch.abort();
        //                 }
        //             },
        //             error : function(error) {
        //                 console.log(error)
        //                 $('#loader').addClass('gone');
        //             }
        //         });
        //     });

        //     $('#searchBtn').on('click', function() {
        //         fetchConsumers($('#searchparam').val());
        //     });            
        // });
    </script>
@endpush

