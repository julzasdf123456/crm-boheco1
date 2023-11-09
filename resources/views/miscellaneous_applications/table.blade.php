<div class="table-responsive">
    <table class="table" id="miscellaneousApplications-table">
        <thead>
        <tr>
            <th>Consumername</th>
        <th>Town</th>
        <th>Barangay</th>
        <th>Sitio</th>
        <th>Application</th>
        <th>Notes</th>
        <th>Status</th>
        <th>Servicedroplength</th>
        <th>Transformerload</th>
        <th>Ticketid</th>
        <th>Serviceconnectionid</th>
        <th>Accountnumber</th>
        <th>Userid</th>
        <th>Totalamount</th>
        <th>Ornumber</th>
        <th>Ordate</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($miscellaneousApplications as $miscellaneousApplications)
            <tr>
                <td>{{ $miscellaneousApplications->ConsumerName }}</td>
            <td>{{ $miscellaneousApplications->Town }}</td>
            <td>{{ $miscellaneousApplications->Barangay }}</td>
            <td>{{ $miscellaneousApplications->Sitio }}</td>
            <td>{{ $miscellaneousApplications->Application }}</td>
            <td>{{ $miscellaneousApplications->Notes }}</td>
            <td>{{ $miscellaneousApplications->Status }}</td>
            <td>{{ $miscellaneousApplications->ServiceDropLength }}</td>
            <td>{{ $miscellaneousApplications->TransformerLoad }}</td>
            <td>{{ $miscellaneousApplications->TicketId }}</td>
            <td>{{ $miscellaneousApplications->ServiceConnectionId }}</td>
            <td>{{ $miscellaneousApplications->AccountNumber }}</td>
            <td>{{ $miscellaneousApplications->UserId }}</td>
            <td>{{ $miscellaneousApplications->TotalAmount }}</td>
            <td>{{ $miscellaneousApplications->ORNumber }}</td>
            <td>{{ $miscellaneousApplications->ORDate }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['miscellaneousApplications.destroy', $miscellaneousApplications->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('miscellaneousApplications.show', [$miscellaneousApplications->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('miscellaneousApplications.edit', [$miscellaneousApplications->id]) }}"
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
