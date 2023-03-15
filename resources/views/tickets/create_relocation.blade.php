@php
    use App\Models\IDGenerator;
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
    use App\Models\Towns;
    use App\Models\BarangayProxies;

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
                    <h4>Create Relocation Request</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    {!! Form::open(['route' => 'tickets.store-relocation']) !!}
                    <div class="card-body">                
                        <div class="row">              

                            <div class="form-group col-sm-12">
                                <div class="row">
                                @if ($cond == 'new')
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('ConsumerName', 'Consumer Name:') !!}
                                                {!! Form::text('ConsumerName', $serviceAccount==null ? '' : $serviceAccount->ConsumerName, ['class' => 'form-control form-control-sm','maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name']) !!}                    
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('Town', 'Town') !!}
                                                {!! Form::select('Town', $towns, $serviceAccount==null ? '' : ($townAnalyzed != null ? $townAnalyzed->id : ''), ['class' => 'form-control form-control-sm']) !!}
                                            </div>
                                        </div>

                                        <!-- Barangay Field -->
                                        <div class="col-lg-3 col-md-5">
                                            <div class="form-group">
                                                {!! Form::label('Barangay', 'Barangay') !!}
                                                {!! Form::select('Barangay', [], null, ['class' => 'form-control form-control-sm',]) !!}
                                            </div>
                                        </div>
                                @else
                                    <div class="col-lg-3 col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('ConsumerName', 'Consumer Name:') !!}
                                            {!! Form::text('ConsumerName', $tickets->ConsumerName, ['class' => 'form-control form-control-sm','maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name']) !!}                    
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('Town', 'Town') !!}
                                            {!! Form::select('Town', $towns, $tickets->Town, ['class' => 'form-control form-control-sm']) !!}
                                        </div>
                                    </div>

                                    <!-- Barangay Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('Barangay', 'Barangay') !!}
                                            {!! Form::select('Barangay', [], $tickets->Barangay, ['class' => 'form-control form-control-sm',]) !!}
                                        </div>
                                    </div>
                                @endif

                                    <!-- Sitio Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('Sitio', 'Sitio') !!}
                                            {!! Form::text('Sitio', null, ['class' => 'form-control form-control-sm','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
                                        </div>
                                    </div>

                                    <!-- Contactnumber Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('ContactNumber', 'Contact Number:') !!}
                                            {!! Form::number('ContactNumber', $serviceAccount != null ? ($serviceAccount->ContactNumber != null ? $serviceAccount->ContactNumber : 0) : 0, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Contact Number']) !!}
                                        </div>
                                    </div>

                                    <!-- Neighbor1 Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('Neighbor1', 'Neighbor1:') !!}
                                            {!! Form::text('Neighbor1', $left != null ? $left->ConsumerName : ($tickets != null && $tickets->Neighbor1 != null ? $tickets->Neighbor1 : ''), ['class' => 'form-control form-control-sm', 'placeholder' => 'Neighbor 1']) !!}
                                        </div>
                                    </div>

                                    <!-- Neighbor2 Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('Neighbor 2', 'Neighbor 2:') !!}
                                            {!! Form::text('Neighbor2', $right != null ? $right->ConsumerName : ($tickets != null && $tickets->Neighbor2 != null ? $tickets->Neighbor2 : ''), ['class' => 'form-control form-control-sm', 'placeholder' => 'Neighbor 2']) !!}
                                        </div>
                                    </div>

                                    <!-- Inspector Field -->
                                    {{-- <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('Inspector', ' Verifier:') !!}
                                            {!! Form::select('Inspector', $inspectors, null, ['class' => 'form-control form-control-sm',]) !!}
                                        </div>
                                    </div> --}}

                                    <div class="col-lg-12">
                                        <div class="divider"></div>
                                    </div>

                                    <!-- Ticket Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('Ticket', 'Ticket Type:') !!}
                                            <select class="custom-select select2"  name="Ticket">
                                                @foreach ($parentTickets as $items)
                                                    <optgroup label="{{ $items->Name }}">
                                                        @php
                                                            $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->whereNotIn('Id', Tickets::getMeterRelatedComplainsId())->orderBy('Name')->get();
                                                        @endphp
                                                        @foreach ($ticketsRep as $item)
                                                            <option value="{{ $item->id }}" {{ $tickets != null && $tickets->Ticket==$item->id ? 'selected' : '' }}>{{ $item->Name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Reportedby Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('ReportedBy', 'Reported By:') !!}
                                            {!! Form::text('ReportedBy', null, ['class' => 'form-control form-control-sm','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'Personnel who reported']) !!}
                                        </div>
                                    </div>

                                    <!-- Ornumber Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('ORNumber', 'OR Number:') !!}
                                            {!! Form::text('ORNumber', null, ['class' => 'form-control form-control-sm','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'OR Number']) !!}
                                        </div>
                                    </div>

                                    <!-- Ordate Field -->
                                    <div class="col-lg-3 col-md-5">
                                        <div class="form-group">
                                            {!! Form::label('ORDate', 'OR Date:') !!}
                                            {!! Form::text('ORDate', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'OR Date']) !!}

                                            @push('page_scripts')
                                                <script type="text/javascript">
                                                    $('#ORDate').datetimepicker({
                                                        format: 'YYYY-MM-DD',
                                                        useCurrent: true,
                                                        sideBySide: true
                                                    })
                                                </script>
                                            @endpush
                                        </div>
                                    </div>

                                    <!-- Reason Field -->
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('Reason', 'Reason:') !!}
                                            {!! Form::textarea('Reason', null, ['class' => 'form-control form-control-sm','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Reason', 'rows' => 2]) !!}
                                        </div>
                                    </div>

                                    <!-- Notes Field -->
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('Notes', 'Notes/Remarks:') !!}
                                            {!! Form::textarea('Notes', null, ['class' => 'form-control form-control-sm','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Notes/Remarks', 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            @if ($cond == 'new')
                            <p id="Def_Brgy" style="display: none;">{{ $serviceAccount==null ? '' : ($brgyProxy != null ? $brgyProxy->id : '') }}</p>
                            @else
                            <p id="Def_Brgy" style="display: none;">{{ $tickets->Barangay }}</p> 
                            @endif

                            {{-- HIDDEN FIELDS --}}
                            <input type="hidden" name="id" value="{{ IDGenerator::generateID(); }}">

                            <input type="hidden" value="{{ Auth::id(); }}" name="UserId">

                            <input type="hidden" value="Received" name="Status">

                            <input type="hidden" value="{{ env("APP_LOCATION") }}" name="Office">

                            @if ($serviceAccount != null)  
                                <input type="hidden" value="{{ $serviceAccount->AccountNumber }}" name="AccountNumber">
                                <input type="hidden" value="{{ $serviceAccount->Pole }}" name="PoleNumber">
                            @endif  
                            
                        </div>
                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('tickets.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card card-outline card-primary">
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
        </div>
    </div>
@endsection
