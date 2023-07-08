<div class="table-responsive">
    <table class="table" id="disconnectionRoutes-table">
        <thead>
        <tr>
            <th>Scheduleid</th>
        <th>Route</th>
        <th>Sequencefrom</th>
        <th>Sequenceto</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($disconnectionRoutes as $disconnectionRoutes)
            <tr>
                <td>{{ $disconnectionRoutes->ScheduleId }}</td>
            <td>{{ $disconnectionRoutes->Route }}</td>
            <td>{{ $disconnectionRoutes->SequenceFrom }}</td>
            <td>{{ $disconnectionRoutes->SequenceTo }}</td>
            <td>{{ $disconnectionRoutes->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['disconnectionRoutes.destroy', $disconnectionRoutes->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('disconnectionRoutes.show', [$disconnectionRoutes->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('disconnectionRoutes.edit', [$disconnectionRoutes->id]) }}"
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
