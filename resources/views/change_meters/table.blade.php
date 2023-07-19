<div class="table-responsive">
    <table class="table" id="changeMeters-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Changedate</th>
        <th>Oldmeter</th>
        <th>Newmeter</th>
        <th>Pulloutreading</th>
        <th>Replaceby</th>
        <th>Remarks</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($changeMeters as $changeMeter)
            <tr>
                <td>{{ $changeMeter->AccountNumber }}</td>
            <td>{{ $changeMeter->ChangeDate }}</td>
            <td>{{ $changeMeter->OldMeter }}</td>
            <td>{{ $changeMeter->NewMeter }}</td>
            <td>{{ $changeMeter->PullOutReading }}</td>
            <td>{{ $changeMeter->ReplaceBy }}</td>
            <td>{{ $changeMeter->Remarks }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['changeMeters.destroy', $changeMeter->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('changeMeters.show', [$changeMeter->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('changeMeters.edit', [$changeMeter->id]) }}"
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
