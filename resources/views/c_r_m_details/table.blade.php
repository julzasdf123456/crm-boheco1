<div class="table-responsive">
    <table class="table" id="cRMDetails-table">
        <thead>
        <tr>
            <th>Referenceno</th>
        <th>Particular</th>
        <th>Glcode</th>
        <th>Subtotal</th>
        <th>Vat</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cRMDetails as $cRMDetails)
            <tr>
                <td>{{ $cRMDetails->ReferenceNo }}</td>
            <td>{{ $cRMDetails->Particular }}</td>
            <td>{{ $cRMDetails->GLCode }}</td>
            <td>{{ $cRMDetails->SubTotal }}</td>
            <td>{{ $cRMDetails->VAT }}</td>
            <td>{{ $cRMDetails->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['cRMDetails.destroy', $cRMDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('cRMDetails.show', [$cRMDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('cRMDetails.edit', [$cRMDetails->id]) }}"
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
