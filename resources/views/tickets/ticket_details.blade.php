@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp
<div class="row">
    <div class="col-lg-9 col-md-8">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-9">
                        <h3>{{ $tickets->ConsumerName }}</h3>
                    </div>
                    <div class="col-lg-3">
                        {!! Form::open(['route' => ['tickets.destroy', $tickets->id], 'method' => 'delete', 'style' => 'width: 30px; display: inline;']) !!}                
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-tool text-danger float-right', 'onclick' => "return confirm('Are you sure you want to delete this ticket?')"]) !!}                
                        {!! Form::close() !!}
                        {{-- IF CHANGE METER --}}
                        @if ($tickets->TicketRepoId=='1668541254390' | $tickets->TicketRepoId=='1672792232225')
                            <a href="{{ route('tickets.change-meter-update', [$tickets->id]) }}" class="btn btn-tool text-info float-right" title="Update meter info"><i class="fas fa-tachometer-alt"></i></a>
                        @endif
                        @if ($tickets->Status=="Executed")
                            <a class="btn btn-tool text-info float-right" title="This ticket is closed because it's been already tagged as executed."><i class="fas fa-lock"></i></a>
                        @else
                            <a href="{{ route('tickets.edit', [$tickets->id]) }}" class="btn btn-tool float-right" title="Edit this ticket"><i class="fas fa-pen"></i></a>
                            <a href="{{ route('tickets.print-ticket', [$tickets->id]) }}" class="btn btn-tool float-right" title="Re-print Ticket Order"><i class="fas fa-print"></i></a>
                        @endif
                    </div>
                </div>                
                <p class="text-muted">Ticket No: {{ $tickets->id }}</p>
                <div class="row">
                    <span class="col-lg-4 text-muted"><i class="fas fa-location-arrow ico-tab"></i>{{ Tickets::getAddress($tickets) }}</span><br>
                    <span class="col-lg-4 text-muted text-center" title="Account Number"><i class="fas fa-user-circle ico-tab"></i><a href="{{ $tickets->AccountNumber != null ? route('serviceAccounts.show', [$tickets->AccountNumber]) : '' }}">{{ $tickets->AccountNumber }}</a></span><br>
                    <span class="col-lg-4 text-muted text-right" title="Contact Number"><i class="fas fa-phone-alt ico-tab"></i>{{ $tickets->ContactNumber }}</span><br>
                </div>
                    
                <div class="divider"></div>

                <span class="text-muted"><i class="fas fa-info-circle ico-tab"></i>{{ $tickets->TicketType }}</span><br>
                @php
                    $parent = TicketsRepository::where('id', $tickets->ParentTicket)->first();
                @endphp
                <h4><span class="text-muted">{{ $parent != null ? $parent->Name . ' - ' : '' }}</span>{{ $tickets->Ticket }}</h4>
                <p>{{ $tickets->Reason }}</p>

                {{-- TABS --}}
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#details" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#meter" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Meter Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#history" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">History</a>
                    </li>
                </ul>

                <div class="tab-content" id="custom-content-below-tabContent">
                    {{-- Details Tab --}}
                    <div class="tab-pane fade active show" id="details" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <table class="table table-hover table-sm table-borderless">
                                    <tr>
                                        <th>Reported By :</th>
                                        <td>{{ $tickets->ReportedBy }}</td>
                                    </tr>
                                    <tr>
                                        <th>Crew Assigned :</th>
                                        <td>{{ $tickets->StationName }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lineman/Crew/Group Executed :</th>
                                        <td>{{ $tickets->LinemanCrewExecuted }}</td>
                                    </tr>
                                    <tr>
                                        <th>OR Number :</th>
                                        <td>{{ $tickets->ORNumber }}</td>
                                    </tr>
                                    <tr>
                                        <th>OR Date :</th>
                                        <td>{{ $tickets->ORDate }}</td>
                                    </tr>
                                    <tr>
                                        <th>Neighbor 1 :</th>
                                        <td>{{ $tickets->Neighbor1 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Neighbor 2 :</th>
                                        <td>{{ $tickets->Neighbor2 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notes :</th>
                                        <td>{{ $tickets->Notes }}</td>
                                    </tr>
                                    <tr>
                                        <th>Office :</th>
                                        <td>{{ $tickets->Office }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pole Number :</th>
                                        <td>{{ $tickets->PoleNumber }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Filed :</th>
                                        <td>{{ date('M d, Y h:i:s A', strtotime($tickets->created_at)) }}</td>
                                    </tr>
                                </table>             
                            </div>                            
                        </div>
                    </div>

                    {{-- Meter Tab --}}
                    <div class="tab-pane fade show" id="meter" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                @if ($tickets->TicketRepoId=='1668541254390' | $tickets->TicketRepoId=='1672792232225')
                                    <a href="{{ route('tickets.change-meter-update', [$tickets->id]) }}" class="btn btn-tool text-info" title="Update meter info" style="margin-top: 5px; margin-bottom: 10px;"><i class="fas fa-pen ico-tab-mini"></i> Update</a>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-bordered table-hover table-sm">
                                    <tr>
                                        <td>Old Meter Brand</td>
                                        <td><strong>{{ $tickets->CurrentMeterBrand }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Old Meter Serial</td>
                                        <td><strong>{{ $tickets->CurrentMeterNo }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Old Meter Pullout Reading</td>
                                        <td><strong>{{ $tickets->CurrentMeterReading }}</strong></td>
                                    </tr>
                                </table>    
                            </div>  
                            
                            <div class="col-lg-6">
                                <table class="table table-bordered table-hover table-sm">
                                    <tr>
                                        <td>New Meter Brand</td>
                                        <td><strong>{{ $tickets->NewMeterBrand }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>New Meter Serial</td>
                                        <td><strong>{{ $tickets->NewMeterNo }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>New Meter Start Reading</td>
                                        <td><strong>{{ $tickets->NewMeterReading }}</strong></td>
                                    </tr>
                                </table>    
                            </div>  
                        </div>
                    </div>

                    {{-- History Tab --}}
                    <div class="tab-pane fade active" id="history" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <table class="table table-hover">
                                    <thead>
                                        <th>Ticket</th>
                                        <th>Date Filed</th>
                                        <th>Status</th>
                                        <th width="8%"></th>
                                    </thead>
                                    <tbody>
                                        @if ($history != null)
                                            @foreach ($history as $item)
                                                @php
                                                    $parentHist = TicketsRepository::where('id', $item->ParentTicket)->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $parentHist != null ? $parentHist->Name . ' - ' : '' }}{{ $item->Ticket }}</td>
                                                    <td>{{ date('F d, Y, h:i A', strtotime($item->created_at)) }}</td>
                                                    <td>{{ $item->Status }}</td>
                                                    <td class="text-right">
                                                        <a href="{{ route('tickets.show', [$item->id]) }}" title="Expand ticket"><i class="fas fa-share"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif                                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4">        
        {{-- STATUS BOXES --}}
        <div class="col-lg-12">
            <div class="info-box {{ $tickets->created_at==null ? 'bg-light' : 'bg-success' }}">
                <div class="info-box-content">
                    <span class="info-box-text text-center {{ $tickets->created_at==null ? 'text-muted' : 'text-white' }}">Filed</span>
                    <span class="info-box-number text-center {{ $tickets->created_at==null ? 'text-muted' : 'text-white' }} mb-0">
                        @if ($tickets->created_at==null)
                            @if ($tickets->Status!="Executed")
                                <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-date-filed" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                            @else
                                -
                            @endif                                                
                        @else
                            {{ date('F d, Y h:i:s A', strtotime($tickets->created_at)) }}
                            @if ($tickets->Status!="Executed")
                                <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-date-filed" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                            @endif
                        @endif 
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="info-box {{ $tickets->DateTimeDownloaded==null ? 'bg-light' : 'bg-success' }}">
                <div class="info-box-content">
                    <span class="info-box-text text-center {{ $tickets->DateTimeDownloaded==null ? 'text-muted' : 'text-white' }}">Sent to Lineman</span>
                    <span class="info-box-number text-center {{ $tickets->DateTimeDownloaded==null ? 'text-muted' : 'text-white' }} mb-0">
                        @if ($tickets->DateTimeDownloaded==null)
                            <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-lineman-sent" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                        @else
                            {{ date('F d, Y h:i:s A', strtotime($tickets->DateTimeDownloaded)) }}
                            <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-lineman-sent" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                              
                        @endif                                            
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="info-box {{ $tickets->DateTimeLinemanArrived==null ? 'bg-light' : 'bg-success' }}">
                <div class="info-box-content">
                    <span class="info-box-text text-center {{ $tickets->DateTimeLinemanArrived==null ? 'text-muted' : 'text-white' }}">Lineman Site Arrival</span>
                    <span class="info-box-number text-center {{ $tickets->DateTimeLinemanArrived==null ? 'text-muted' : 'text-white' }} mb-0">
                        @if ($tickets->DateTimeLinemanArrived==null)
                            <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-lineman-arrived" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                              
                        @else                                            
                            {{ date('F d, Y h:i:s A', strtotime($tickets->DateTimeLinemanArrived)) }}
                            <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-lineman-arrived" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                        @endif 
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="info-box {{ $tickets->DateTimeLinemanExecuted==null ? 'bg-light' : ($tickets->Status=="Executed" ? 'bg-success' : 'bg-danger') }}">
                <div class="info-box-content">
                    <span class="info-box-text text-center {{ $tickets->DateTimeLinemanExecuted==null ? 'text-muted' : 'text-white' }}">Execution</span>
                    <span class="info-box-number text-center {{ $tickets->DateTimeLinemanExecuted==null ? 'text-muted' : 'text-white' }} mb-0">
                        @if ($tickets->DateTimeLinemanExecuted==null)
                            <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-execution" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                               
                        @else
                            {{ date('F d, Y h:i:s A', strtotime($tickets->DateTimeLinemanExecuted)) }}
                            <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-execution" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                             
                        @endif 
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>