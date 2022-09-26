<div class="table-responsive">
    <table class="table" id="electricians-table">
        <thead>
        <tr>
            <th>Idnumber</th>
        <th>Name</th>
        <th>Address</th>
        <th>Contactnumber</th>
        <th>Banknumber</th>
        <th>Bank</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($electricians as $electricians)
            <tr>
                <td>{{ $electricians->IDNumber }}</td>
            <td>{{ $electricians->Name }}</td>
            <td>{{ $electricians->Address }}</td>
            <td>{{ $electricians->ContactNumber }}</td>
            <td>{{ $electricians->BankNumber }}</td>
            <td>{{ $electricians->Bank }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['electricians.destroy', $electricians->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('electricians.show', [$electricians->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('electricians.edit', [$electricians->id]) }}"
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
