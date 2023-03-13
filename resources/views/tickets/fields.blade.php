
@php
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

<div class="form-group col-sm-12">
    <div class="row">
@if ($cond == 'new')
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    {!! Form::label('ConsumerName', 'Consumer Name:') !!}
                    {!! Form::text('ConsumerName', $serviceAccount==null ? '' : $serviceAccount->ConsumerName, ['class' => 'form-control form-control-sm','required' => 'true', 'maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name']) !!}                    
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    {!! Form::label('Town', 'Town') !!}
                    {!! Form::select('Town', $towns, $serviceAccount==null ? '' : ($townAnalyzed != null ? $townAnalyzed->id : ''), ['class' => 'form-control form-control-sm','required' => 'true']) !!}
                </div>
            </div>

            <!-- Barangay Field -->
            <div class="col-lg-3 col-md-5">
                <div class="form-group">
                    {!! Form::label('Barangay', 'Barangay') !!}
                    {!! Form::select('Barangay', [], null, ['class' => 'form-control form-control-sm','required' => 'true']) !!}
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

        <!-- Crewassigned Field -->
        <div class="col-lg-3 col-md-5">
            <div class="form-group">
                {!! Form::label('CrewAssigned', 'Crew Assigned:') !!}
                {{-- {!! Form::select('CrewAssigned', $crew, null, ['class' => 'form-control form-control-sm',]) !!} --}}
                <select name="CrewAssigned" id="CrewAssigned" class="form-control form-control-sm">
                    <option value="">-</option>
                    @foreach ($crew as $item)
                        <option value="{{ $item->id }}">{{ $item->StationName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

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
                                $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->whereNotIn('Id', [Tickets::getChangeMeter()])->orderBy('Name')->get();
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
