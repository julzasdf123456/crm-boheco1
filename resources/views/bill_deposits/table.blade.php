<div class="table-responsive">
    <table class="table" id="billDeposits-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Load</th>
        <th>Powerfactor</th>
        <th>Demandfactor</th>
        <th>Hours</th>
        <th>Averagerate</th>
        <th>Averagetransmission</th>
        <th>Averagedemand</th>
        <th>Billdepositamount</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billDeposits as $billDeposits)
            <tr>
                <td>{{ $billDeposits->ServiceConnectionId }}</td>
            <td>{{ $billDeposits->Load }}</td>
            <td>{{ $billDeposits->PowerFactor }}</td>
            <td>{{ $billDeposits->DemandFactor }}</td>
            <td>{{ $billDeposits->Hours }}</td>
            <td>{{ $billDeposits->AverageRate }}</td>
            <td>{{ $billDeposits->AverageTransmission }}</td>
            <td>{{ $billDeposits->AverageDemand }}</td>
            <td>{{ $billDeposits->BillDepositAmount }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billDeposits.destroy', $billDeposits->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billDeposits.show', [$billDeposits->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billDeposits.edit', [$billDeposits->id]) }}"
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
