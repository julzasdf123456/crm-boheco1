<div class="table-responsive">
    <table class="table" id="accountMasterExtensions-table">
        <thead>
        <tr>
            <th>Polecode</th>
        <th>Servicevoltage</th>
        <th>Phase</th>
        <th>Pf</th>
        <th>Phasing</th>
        <th>Substationid</th>
        <th>Sdwtype</th>
        <th>Item1</th>
        <th>Item2</th>
        <th>Item3</th>
        <th>Item4</th>
        <th>Item5</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accountMasterExtensions as $accountMasterExtension)
            <tr>
                <td>{{ $accountMasterExtension->PoleCode }}</td>
            <td>{{ $accountMasterExtension->ServiceVoltage }}</td>
            <td>{{ $accountMasterExtension->Phase }}</td>
            <td>{{ $accountMasterExtension->PF }}</td>
            <td>{{ $accountMasterExtension->Phasing }}</td>
            <td>{{ $accountMasterExtension->SubstationID }}</td>
            <td>{{ $accountMasterExtension->SDWType }}</td>
            <td>{{ $accountMasterExtension->Item1 }}</td>
            <td>{{ $accountMasterExtension->Item2 }}</td>
            <td>{{ $accountMasterExtension->Item3 }}</td>
            <td>{{ $accountMasterExtension->Item4 }}</td>
            <td>{{ $accountMasterExtension->Item5 }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['accountMasterExtensions.destroy', $accountMasterExtension->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('accountMasterExtensions.show', [$accountMasterExtension->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('accountMasterExtensions.edit', [$accountMasterExtension->id]) }}"
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
