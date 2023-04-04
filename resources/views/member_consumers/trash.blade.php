@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-12">
               <h4>Trash</h4>
           </div>
       </div>
   </div>
</section>

    <div class="row">
        <div class='col-lg-12 col-md-12'>
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
                                    <a href="{{ route('memberConsumers.restore', [$item->ConsumerId]) }}"
                                       class='btn btn-success btn-xs'>
                                          <i class="fas fa-recycle"></i> Restore
                                       </a>
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
