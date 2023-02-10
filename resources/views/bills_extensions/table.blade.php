<div class="table-responsive">
    <table class="table" id="billsExtensions-table">
        <thead>
        <tr>
            <th>Serviceperiodend</th>
        <th>Generationvat</th>
        <th>Transmissionvat</th>
        <th>Slvat</th>
        <th>Distributionvat</th>
        <th>Othersvat</th>
        <th>Item5</th>
        <th>Item6</th>
        <th>Item7</th>
        <th>Item8</th>
        <th>Item9</th>
        <th>Item10</th>
        <th>Item11</th>
        <th>Item12</th>
        <th>Item13</th>
        <th>Item14</th>
        <th>Item15</th>
        <th>Item16</th>
        <th>Item17</th>
        <th>Item18</th>
        <th>Item19</th>
        <th>Item20</th>
        <th>Item21</th>
        <th>Item22</th>
        <th>Item23</th>
        <th>Item24</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billsExtensions as $billsExtension)
            <tr>
                <td>{{ $billsExtension->ServicePeriodEnd }}</td>
            <td>{{ $billsExtension->GenerationVAT }}</td>
            <td>{{ $billsExtension->TransmissionVAT }}</td>
            <td>{{ $billsExtension->SLVAT }}</td>
            <td>{{ $billsExtension->DistributionVAT }}</td>
            <td>{{ $billsExtension->OthersVAT }}</td>
            <td>{{ $billsExtension->Item5 }}</td>
            <td>{{ $billsExtension->Item6 }}</td>
            <td>{{ $billsExtension->Item7 }}</td>
            <td>{{ $billsExtension->Item8 }}</td>
            <td>{{ $billsExtension->Item9 }}</td>
            <td>{{ $billsExtension->Item10 }}</td>
            <td>{{ $billsExtension->Item11 }}</td>
            <td>{{ $billsExtension->Item12 }}</td>
            <td>{{ $billsExtension->Item13 }}</td>
            <td>{{ $billsExtension->Item14 }}</td>
            <td>{{ $billsExtension->Item15 }}</td>
            <td>{{ $billsExtension->Item16 }}</td>
            <td>{{ $billsExtension->Item17 }}</td>
            <td>{{ $billsExtension->Item18 }}</td>
            <td>{{ $billsExtension->Item19 }}</td>
            <td>{{ $billsExtension->Item20 }}</td>
            <td>{{ $billsExtension->Item21 }}</td>
            <td>{{ $billsExtension->Item22 }}</td>
            <td>{{ $billsExtension->Item23 }}</td>
            <td>{{ $billsExtension->Item24 }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billsExtensions.destroy', $billsExtension->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billsExtensions.show', [$billsExtension->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billsExtensions.edit', [$billsExtension->id]) }}"
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
