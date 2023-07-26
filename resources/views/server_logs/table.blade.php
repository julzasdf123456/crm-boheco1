<div class="table-responsive">
    <table class="table" id="serverLogs-table">
        <thead>
        <tr>
            <th>Serveripsource</th>
        <th>Servernamesource</th>
        <th>Details</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serverLogs as $serverLogs)
            <tr>
                <td>{{ $serverLogs->ServerIpSource }}</td>
            <td>{{ $serverLogs->ServerNameSource }}</td>
            <td>{{ $serverLogs->Details }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serverLogs.destroy', $serverLogs->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serverLogs.show', [$serverLogs->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serverLogs.edit', [$serverLogs->id]) }}"
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
