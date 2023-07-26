<div class="table-responsive">
    <table class="table" id="serverStats-table">
        <thead>
        <tr>
            <th>Serverid</th>
        <th>Cpupercentage</th>
        <th>Memorypercentage</th>
        <th>Diskpercentage</th>
        <th>Totalmemory</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serverStats as $serverStats)
            <tr>
                <td>{{ $serverStats->ServerId }}</td>
            <td>{{ $serverStats->CpuPercentage }}</td>
            <td>{{ $serverStats->MemoryPercentage }}</td>
            <td>{{ $serverStats->DiskPercentage }}</td>
            <td>{{ $serverStats->TotalMemory }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serverStats.destroy', $serverStats->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serverStats.show', [$serverStats->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serverStats.edit', [$serverStats->id]) }}"
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
