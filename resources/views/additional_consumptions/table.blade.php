<div class="table-responsive">
    <table class="table" id="additionalConsumptions-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Additionalkwh</th>
        <th>Additionalkw</th>
        <th>Remarks</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($additionalConsumptions as $additionalConsumptions)
            <tr>
                <td>{{ $additionalConsumptions->AccountNumber }}</td>
            <td>{{ $additionalConsumptions->AdditionalKWH }}</td>
            <td>{{ $additionalConsumptions->AdditionalKW }}</td>
            <td>{{ $additionalConsumptions->Remarks }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['additionalConsumptions.destroy', $additionalConsumptions->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('additionalConsumptions.show', [$additionalConsumptions->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('additionalConsumptions.edit', [$additionalConsumptions->id]) }}"
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
