<div class="table-responsive">
    <table class="table" id="servers-table">
        <thead>
        <tr>
            <th>Servername</th>
        <th>Serverip</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($servers as $servers)
            <tr>
                <td>{{ $servers->ServerName }}</td>
            <td>{{ $servers->ServerIp }}</td>
            <td>{{ $servers->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['servers.destroy', $servers->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('servers.show', [$servers->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('servers.edit', [$servers->id]) }}"
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
