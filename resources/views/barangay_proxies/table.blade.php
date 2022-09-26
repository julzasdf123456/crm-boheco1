<div class="table-responsive">
    <table class="table" id="barangayProxies-table">
        <thead>
        <tr>
            <th>Barangay</th>
        <th>Townid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($barangayProxies as $barangayProxies)
            <tr>
                <td>{{ $barangayProxies->Barangay }}</td>
            <td>{{ $barangayProxies->TownId }}</td>
            <td>{{ $barangayProxies->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['barangayProxies.destroy', $barangayProxies->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('barangayProxies.show', [$barangayProxies->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('barangayProxies.edit', [$barangayProxies->id]) }}"
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
