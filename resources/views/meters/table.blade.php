<div class="table-responsive">
    <table class="table" id="meters-table">
        <thead>
        <tr>
            <th>Recordstatus</th>
        <th>Changedate</th>
        <th>Meterdigits</th>
        <th>Multiplier</th>
        <th>Chargingmode</th>
        <th>Demandtype</th>
        <th>Make</th>
        <th>Serialnumber</th>
        <th>Calibrationdate</th>
        <th>Meterstatus</th>
        <th>Initialreading</th>
        <th>Initialdemand</th>
        <th>Remarks</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($meters as $meters)
            <tr>
                <td>{{ $meters->RecordStatus }}</td>
            <td>{{ $meters->ChangeDate }}</td>
            <td>{{ $meters->MeterDigits }}</td>
            <td>{{ $meters->Multiplier }}</td>
            <td>{{ $meters->ChargingMode }}</td>
            <td>{{ $meters->DemandType }}</td>
            <td>{{ $meters->Make }}</td>
            <td>{{ $meters->SerialNumber }}</td>
            <td>{{ $meters->CalibrationDate }}</td>
            <td>{{ $meters->MeterStatus }}</td>
            <td>{{ $meters->InitialReading }}</td>
            <td>{{ $meters->InitialDemand }}</td>
            <td>{{ $meters->Remarks }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['meters.destroy', $meters->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('meters.show', [$meters->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('meters.edit', [$meters->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
