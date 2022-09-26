<div class="table-responsive">
    <table class="table" id="unbundledRatesExtensions-table">
        <thead>
        <tr>
            <th>Rowguid</th>
        <th>Serviceperiodend</th>
        <th>Lcpercustomer</th>
        <th>Item2</th>
        <th>Item3</th>
        <th>Item4</th>
        <th>Item11</th>
        <th>Item12</th>
        <th>Item13</th>
        <th>Item5</th>
        <th>Item6</th>
        <th>Item7</th>
        <th>Item8</th>
        <th>Item9</th>
        <th>Item10</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($unbundledRatesExtensions as $unbundledRatesExtension)
            <tr>
                <td>{{ $unbundledRatesExtension->rowguid }}</td>
            <td>{{ $unbundledRatesExtension->ServicePeriodEnd }}</td>
            <td>{{ $unbundledRatesExtension->LCPerCustomer }}</td>
            <td>{{ $unbundledRatesExtension->Item2 }}</td>
            <td>{{ $unbundledRatesExtension->Item3 }}</td>
            <td>{{ $unbundledRatesExtension->Item4 }}</td>
            <td>{{ $unbundledRatesExtension->Item11 }}</td>
            <td>{{ $unbundledRatesExtension->Item12 }}</td>
            <td>{{ $unbundledRatesExtension->Item13 }}</td>
            <td>{{ $unbundledRatesExtension->Item5 }}</td>
            <td>{{ $unbundledRatesExtension->Item6 }}</td>
            <td>{{ $unbundledRatesExtension->Item7 }}</td>
            <td>{{ $unbundledRatesExtension->Item8 }}</td>
            <td>{{ $unbundledRatesExtension->Item9 }}</td>
            <td>{{ $unbundledRatesExtension->Item10 }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['unbundledRatesExtensions.destroy', $unbundledRatesExtension->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('unbundledRatesExtensions.show', [$unbundledRatesExtension->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('unbundledRatesExtensions.edit', [$unbundledRatesExtension->id]) }}"
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
