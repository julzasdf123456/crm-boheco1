<div class="table-responsive">
    <table class="table" id="disconnectionDatas-table">
        <thead>
        <tr>
            <th>Scheduleid</th>
        <th>Disconnectorname</th>
        <th>Userid</th>
        <th>Accountnumber</th>
        <th>Serviceperiodend</th>
        <th>Accountcoordinates</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Status</th>
        <th>Notes</th>
        <th>Netamount</th>
        <th>Surcharge</th>
        <th>Servicefee</th>
        <th>Others</th>
        <th>Paidamount</th>
        <th>Ornumber</th>
        <th>Ordate</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($disconnectionDatas as $disconnectionData)
            <tr>
                <td>{{ $disconnectionData->ScheduleId }}</td>
            <td>{{ $disconnectionData->DisconnectorName }}</td>
            <td>{{ $disconnectionData->UserId }}</td>
            <td>{{ $disconnectionData->AccountNumber }}</td>
            <td>{{ $disconnectionData->ServicePeriodEnd }}</td>
            <td>{{ $disconnectionData->AccountCoordinates }}</td>
            <td>{{ $disconnectionData->Latitude }}</td>
            <td>{{ $disconnectionData->Longitude }}</td>
            <td>{{ $disconnectionData->Status }}</td>
            <td>{{ $disconnectionData->Notes }}</td>
            <td>{{ $disconnectionData->NetAmount }}</td>
            <td>{{ $disconnectionData->Surcharge }}</td>
            <td>{{ $disconnectionData->ServiceFee }}</td>
            <td>{{ $disconnectionData->Others }}</td>
            <td>{{ $disconnectionData->PaidAmount }}</td>
            <td>{{ $disconnectionData->ORNumber }}</td>
            <td>{{ $disconnectionData->ORDate }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['disconnectionDatas.destroy', $disconnectionData->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('disconnectionDatas.show', [$disconnectionData->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('disconnectionDatas.edit', [$disconnectionData->id]) }}"
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
