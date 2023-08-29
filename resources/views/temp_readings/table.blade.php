<div class="table-responsive">
    <table class="table" id="tempReadings-table">
        <thead>
        <tr>
            <th>Serviceperiodend</th>
        <th>Accountnumber</th>
        <th>Route</th>
        <th>Sequencenumber</th>
        <th>Consumername</th>
        <th>Consumeraddress</th>
        <th>Meternumber</th>
        <th>Previousreading2</th>
        <th>Previousreading1</th>
        <th>Previousreading</th>
        <th>Readingdate</th>
        <th>Readby</th>
        <th>Powerreadings</th>
        <th>Demandreadings</th>
        <th>Fieldfindings</th>
        <th>Misscodes</th>
        <th>Remarks</th>
        <th>Updatestatus</th>
        <th>Consumertype</th>
        <th>Accountstatus</th>
        <th>Shortaccountnumber</th>
        <th>Multiplier</th>
        <th>Meterdigits</th>
        <th>Coreloss</th>
        <th>Corelosskwhlimit</th>
        <th>Additionalkwh</th>
        <th>Tsfrental</th>
        <th>Schooltag</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tempReadings as $tempReadings)
            <tr>
                <td>{{ $tempReadings->ServicePeriodEnd }}</td>
            <td>{{ $tempReadings->AccountNumber }}</td>
            <td>{{ $tempReadings->Route }}</td>
            <td>{{ $tempReadings->SequenceNumber }}</td>
            <td>{{ $tempReadings->ConsumerName }}</td>
            <td>{{ $tempReadings->ConsumerAddress }}</td>
            <td>{{ $tempReadings->MeterNumber }}</td>
            <td>{{ $tempReadings->PreviousReading2 }}</td>
            <td>{{ $tempReadings->PreviousReading1 }}</td>
            <td>{{ $tempReadings->PreviousReading }}</td>
            <td>{{ $tempReadings->ReadingDate }}</td>
            <td>{{ $tempReadings->ReadBy }}</td>
            <td>{{ $tempReadings->PowerReadings }}</td>
            <td>{{ $tempReadings->DemandReadings }}</td>
            <td>{{ $tempReadings->FieldFindings }}</td>
            <td>{{ $tempReadings->MissCodes }}</td>
            <td>{{ $tempReadings->Remarks }}</td>
            <td>{{ $tempReadings->UpdateStatus }}</td>
            <td>{{ $tempReadings->ConsumerType }}</td>
            <td>{{ $tempReadings->AccountStatus }}</td>
            <td>{{ $tempReadings->ShortAccountNumber }}</td>
            <td>{{ $tempReadings->Multiplier }}</td>
            <td>{{ $tempReadings->MeterDigits }}</td>
            <td>{{ $tempReadings->Coreloss }}</td>
            <td>{{ $tempReadings->CorelossKWHLimit }}</td>
            <td>{{ $tempReadings->AdditionalKWH }}</td>
            <td>{{ $tempReadings->TSFRental }}</td>
            <td>{{ $tempReadings->SchoolTag }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['tempReadings.destroy', $tempReadings->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('tempReadings.show', [$tempReadings->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('tempReadings.edit', [$tempReadings->id]) }}"
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
