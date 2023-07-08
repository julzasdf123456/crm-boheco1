<div class="table-responsive">
    <table class="table" id="disconnectionSchedules-table">
        <thead>
        <tr>
            <th>Disconnectorname</th>
        <th>Disconnectorid</th>
        <th>Day</th>
        <th>Serviceperiodend</th>
        <th>Routes</th>
        <th>Sequencefrom</th>
        <th>Sequenceto</th>
        <th>Status</th>
        <th>Datetimedownloaded</th>
        <th>Phonemodel</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($disconnectionSchedules as $disconnectionSchedules)
            <tr>
                <td>{{ $disconnectionSchedules->DisconnectorName }}</td>
            <td>{{ $disconnectionSchedules->DisconnectorId }}</td>
            <td>{{ $disconnectionSchedules->Day }}</td>
            <td>{{ $disconnectionSchedules->ServicePeriodEnd }}</td>
            <td>{{ $disconnectionSchedules->Routes }}</td>
            <td>{{ $disconnectionSchedules->SequenceFrom }}</td>
            <td>{{ $disconnectionSchedules->SequenceTo }}</td>
            <td>{{ $disconnectionSchedules->Status }}</td>
            <td>{{ $disconnectionSchedules->DatetimeDownloaded }}</td>
            <td>{{ $disconnectionSchedules->PhoneModel }}</td>
            <td>{{ $disconnectionSchedules->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['disconnectionSchedules.destroy', $disconnectionSchedules->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('disconnectionSchedules.show', [$disconnectionSchedules->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('disconnectionSchedules.edit', [$disconnectionSchedules->id]) }}"
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
