@php
    use App\Models\IDGenerator;
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
    use App\Models\Towns;
    use App\Models\BarangayProxies;

    // ANALYZE ADDRESS
    if ($serviceAccount != null) {
        $townDef = explode(",", $serviceAccount->ConsumerAddress);
        $townFinal = "";
        if (trim($townDef[count($townDef)-1]) == 'ALBUR') {
            $townFinal = 'ALBURQUERQUE';
        } elseif (trim($townDef[count($townDef)-1]) == 'CATIGBI-AN') {
            $townFinal = 'CATIGBIAN';
        } elseif (trim($townDef[count($townDef)-1]) == 'CABILAO') {
            $townFinal = 'LOON';
        } elseif (trim($townDef[count($townDef)-1]) == 'S. ISIDRO') {
            $townFinal = 'SAN ISIDRO';
        } elseif (trim($townDef[count($townDef)-1]) == 'SAN ISIDRRO') {
            $townFinal = 'SAN ISIDRO';
        } elseif (trim($townDef[count($townDef)-1]) == 'BOHOL') {
            $townFinal = 'DIMIAO';
        }  else {
            $townFinal = trim($townDef[count($townDef)-1]);
        }

        // GET TOWN
        $townAnalyzed = Towns::where('Town', 'LIKE', '%' . $townFinal . '%')->first();

        // GET BARANGAY
        $brgyRaw = count($townDef) > 1 ? trim($townDef[count($townDef)-2]) : '';
        $brgyProxy = BarangayProxies::where('Barangay', $brgyRaw)
            ->where('TownId', $townAnalyzed != null ? $townAnalyzed->id : '0')
            ->first();
    } else {
        $townFinal = "";
    }
    
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Create Change Meter Ticket</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        {!! Form::open(['route' => 'tickets.store']) !!}
        <div class="row">
            {{-- TICKET DETAILS --}}
            <div class="col-lg-6 col-md-6">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Complaint Details</span>
                    </div>
                    <div class="card-body">                
                        <div class="row"> 

                            @if ($cond == 'new')
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('ConsumerName', 'Consumer name:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                            </div>
                                            {!! Form::text('ConsumerName', $serviceAccount==null ? '' : $serviceAccount->ConsumerName, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name', 'required' => 'true']) !!}
                                        </div>
                                    </div>  
                                </div> 
                            </div>

                            <!-- Town Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Town', 'Town') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            {!! Form::select('Town', $towns, $serviceAccount==null ? '' : ($townAnalyzed != null ? $townAnalyzed->id : ''), ['class' => 'form-control form-control-sm']) !!}
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            @else
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('ConsumerName', 'Consumer Name:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                            </div>
                                            {!! Form::text('ConsumerName', $serviceAccount != null ? $tickets->ConsumerName : '', ['class' => 'form-control','maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name', 'required' => 'true']) !!}
                                        </div>
                                    </div>  
                                </div> 
                            </div>

                            <!-- Town Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Town', 'Town') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            {!! Form::select('Town', $towns, $serviceAccount !=null ? $tickets->Town : '', ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            @endif

                            <!-- Barangay Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Barangay', 'Barangay') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            {!! Form::select('Barangay', [], null, ['class' => 'form-control', 'required' => 'true']) !!}
                                        </div>
                                    </div>
                                </div>    
                            </div>


                            @if ($cond == 'new')
                            <!-- Sitio Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Sitio', 'Sitio') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            {!! Form::text('Sitio', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            @else
                            <!-- Sitio Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Purok', 'Sitio') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            {!! Form::text('Sitio', $serviceAccount != null ? $tickets->Sitio : '', ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            @endif

                            <!-- Contactnumber Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('ContactNumber', 'Contact Number:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            {!! Form::number('ContactNumber', $serviceAccount != null ? ($serviceAccount->ContactNumber != null ? $serviceAccount->ContactNumber : 0) : 0, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Contact Number', 'required' => 'true']) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="divider"></div>
                            <br>

                            <!-- Reason Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Reason', 'Reason:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-file-video"></i></span>
                                            </div>
                                            {!! Form::textarea('Reason', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Reason', 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <!-- Reportedby Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('ReportedBy', 'Reported by:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                            </div>
                                            {!! Form::text('ReportedBy', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'Personnel who reported', 'required' => 'true']) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            @push('page_scripts')
                            <script type="text/javascript">
                                $('#ORDate').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                            @endpush

                            <div class="divider"></div>
                            <br>

                            <!-- Crewassigned Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('CrewAssigned', 'Crew Assigned:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                            </div>
                                            <select name="CrewAssigned" id="CrewAssigned" class="form-control form-control-sm">
                                                <option value="">-</option>
                                                @foreach ($crew as $item)
                                                    <option value="{{ $item->id }}">{{ $item->StationName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <!-- Neighbor1 Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Neighbor1', 'Neighbor1:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                            </div>
                                            {!! Form::text('Neighbor1', $left != null ? $left->ConsumerName : ($tickets != null && $tickets->Neighbor1 != null ? $tickets->Neighbor1 : ''), ['class' => 'form-control form-control-sm', 'placeholder' => 'Neighbor 1']) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <!-- Neighbor2 Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Neighbor2', 'Neighbor2:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                            </div>
                                            {!! Form::text('Neighbor2', $right != null ? $right->ConsumerName : ($tickets != null && $tickets->Neighbor2 != null ? $tickets->Neighbor2 : ''), ['class' => 'form-control form-control-sm', 'placeholder' => 'Neighbor 2']) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <!-- Notes Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('Notes', 'Notes:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-file-video"></i></span>
                                            </div>
                                            {!! Form::textarea('Notes', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Notes/Remarks', 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            @if ($cond == 'new')
                            <p id="Def_Brgy" style="display: none;">{{ $serviceAccount==null ? '' : ($brgyProxy != null ? $brgyProxy->id : '') }}</p>
                            @else
                            {{-- <p id="Def_Brgy" style="display: none;">{{ $tickets != null ? $tickets->Barangay : '0' }}</p>  --}}
                            @endif


                            {{-- HIDDEN FIELDS --}}
                            <input type="hidden" name="id" value="{{ IDGenerator::generateID(); }}">

                            <input type="hidden" value="{{ Auth::id(); }}" name="UserId">

                            <input type="hidden" value="Received" name="Status">

                            <input type="hidden" value="{{ env("APP_LOCATION") }}" name="Office">

                            <input type="hidden" name="Ticket" id="" value="{{ Tickets::getChangeMeter() }}">

                            @if ($serviceAccount != null)  
                                <input type="hidden" value="{{ $serviceAccount->AccountNumber }}" name="AccountNumber">
                                <input type="hidden" value="{{ $serviceAccount->Pole }}" name="PoleNumber">
                            @endif  
                            
                        </div>
                    </div>
                </div>
            </div>

            {{-- METER DETAILS --}}
            <div class="col-lg-6 col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <span class="card-title">Meter Details</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- CurrentMeterNo Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('CurrentMeterNo', 'Old Meter No:') !!}
                                    {!! Form::text('CurrentMeterNo', $meter != null ? $meter->MeterNumber : '', ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100, 'readonly' => true]) !!}
                                </div>
                            </div>

                            <!-- NewMeterNo Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('NewMeterNo', 'New Meter No:') !!}
                                    {!! Form::text('NewMeterNo', null, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- CurrentMeterBrand Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('CurrentMeterBrand', 'Old Meter Brand:') !!}
                                    {!! Form::text('CurrentMeterBrand', $meter != null ? $meter->Make : '', ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- NewMeterBrand Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('NewMeterBrand', 'New Meter Brand:') !!}
                                    {!! Form::text('NewMeterBrand', null, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- CurrentMeterReading Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('CurrentMeterReading', 'Old Meter Pullout Reading:') !!}
                                    {!! Form::text('CurrentMeterReading', 0, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- NewMeterReading Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('NewMeterReading', 'New Meter Start Reading:') !!}
                                    {!! Form::text('NewMeterReading', 0, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('tickets.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </div>

                {{-- HISTORY --}}
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Ticket History <i class="text-muted">(newest to oldest)</i></span>
                    </div>

                    <div class="card-body">
                        @if ($history != null)
                            <div id="accordion">
                                @foreach ($history as $item)
                                    @php
                                        $parent = TicketsRepository::find($item->ParentTicket);
                                    @endphp
                                    <div class="card mb-0">
                                        <div class="card-header" id="heading-{{ $item->id }}">
                                            <h5 class="card-title mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#id-{{ $item->id }}" aria-expanded="true" aria-controls="id-{{ $item->id }}">
                                                    @if ($parent != null)
                                                        {{ $parent->Name }} - {{ $item->Name }}
                                                    @else
                                                        {{ $item->Name }}
                                                    @endif
                                                    
                                                </button>
                                            </h5>
                                            <div class="card-tools">
                                                <a href="{{ route('tickets.show', [$item->id]) }}" class="btn btn-tool"><i class="fas fa-eye"></i></a>
                                            </div>
                                        </div>
                                    
                                        <div id="id-{{ $item->id }}" class="collapse" aria-labelledby="heading-{{ $item->id }}" data-parent="#accordion">
                                            <div class="card-body">
                                                <table class="table table-sm table-hover">
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{ $item->Barangay }}, {{ $item->Town }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reason</th>
                                                        <td>{{ $item->Reason }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{ $item->Status }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date Filed</th>
                                                        <td>{{ date('F d, Y', strtotime($item->created_at)) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center"><i>No recorded history</i></p>
                        @endif
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
