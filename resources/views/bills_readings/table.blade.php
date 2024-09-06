<div class="table-responsive">
    <table class="table" id="billsReadings-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Readingdate</th>
        <th>Readby</th>
        <th>Powerreadings</th>
        <th>Demandreadings</th>
        <th>Fieldfindings</th>
        <th>Misscodes</th>
        <th>Remarks</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billsReadings as $billsReadings)
            <tr>
                <td>{{ $billsReadings->AccountNumber }}</td>
            <td>{{ $billsReadings->ReadingDate }}</td>
            <td>{{ $billsReadings->ReadBy }}</td>
            <td>{{ $billsReadings->PowerReadings }}</td>
            <td>{{ $billsReadings->DemandReadings }}</td>
            <td>{{ $billsReadings->FieldFindings }}</td>
            <td>{{ $billsReadings->MissCodes }}</td>
            <td>{{ $billsReadings->Remarks }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billsReadings.destroy', $billsReadings->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billsReadings.show', [$billsReadings->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billsReadings.edit', [$billsReadings->id]) }}"
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
