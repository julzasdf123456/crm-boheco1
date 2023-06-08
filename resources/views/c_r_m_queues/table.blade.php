<div class="table-responsive">
    <table class="table" id="cRMQueues-table">
        <thead>
        <tr>
            <th>Consumername</th>
        <th>Consumeraddress</th>
        <th>Transactionpurpose</th>
        <th>Source</th>
        <th>Sourceid</th>
        <th>Subtotal</th>
        <th>Vat</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cRMQueues as $cRMQueue)
            <tr>
                <td>{{ $cRMQueue->ConsumerName }}</td>
            <td>{{ $cRMQueue->ConsumerAddress }}</td>
            <td>{{ $cRMQueue->TransactionPurpose }}</td>
            <td>{{ $cRMQueue->Source }}</td>
            <td>{{ $cRMQueue->SourceId }}</td>
            <td>{{ $cRMQueue->SubTotal }}</td>
            <td>{{ $cRMQueue->VAT }}</td>
            <td>{{ $cRMQueue->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['cRMQueues.destroy', $cRMQueue->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('cRMQueues.show', [$cRMQueue->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('cRMQueues.edit', [$cRMQueue->id]) }}"
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
