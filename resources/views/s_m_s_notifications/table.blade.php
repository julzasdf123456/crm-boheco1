<div class="table-responsive">
    <table class="table" id="sMSNotifications-table">
        <thead>
        <tr>
            <th>Source</th>
        <th>Sourceid</th>
        <th>Contactnumber</th>
        <th>Message</th>
        <th>Status</th>
        <th>Aifacilitator</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sMSNotifications as $sMSNotifications)
            <tr>
                <td>{{ $sMSNotifications->Source }}</td>
            <td>{{ $sMSNotifications->SourceId }}</td>
            <td>{{ $sMSNotifications->ContactNumber }}</td>
            <td>{{ $sMSNotifications->Message }}</td>
            <td>{{ $sMSNotifications->Status }}</td>
            <td>{{ $sMSNotifications->AIFacilitator }}</td>
            <td>{{ $sMSNotifications->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['sMSNotifications.destroy', $sMSNotifications->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('sMSNotifications.show', [$sMSNotifications->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('sMSNotifications.edit', [$sMSNotifications->id]) }}"
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
