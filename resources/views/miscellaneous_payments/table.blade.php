<div class="table-responsive">
    <table class="table" id="miscellaneousPayments-table">
        <thead>
        <tr>
            <th>Id</th>
        <th>Miscellaneousid</th>
        <th>Glcode</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($miscellaneousPayments as $miscellaneousPayments)
            <tr>
                <td>{{ $miscellaneousPayments->id }}</td>
            <td>{{ $miscellaneousPayments->MiscellaneousId }}</td>
            <td>{{ $miscellaneousPayments->GLCode }}</td>
            <td>{{ $miscellaneousPayments->Description }}</td>
            <td>{{ $miscellaneousPayments->Amount }}</td>
            <td>{{ $miscellaneousPayments->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['miscellaneousPayments.destroy', $miscellaneousPayments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('miscellaneousPayments.show', [$miscellaneousPayments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('miscellaneousPayments.edit', [$miscellaneousPayments->id]) }}"
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
