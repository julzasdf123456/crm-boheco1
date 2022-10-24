<table class="table table-hover table-sm">
    <thead>
        <th>Account No</th>
        <th>Account Name</th>
        <th>Date Registered</th>
        <th>Registration Medium</th>
        <th>Signature</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->AccountNumber }}</td>
                <td>{{ $item->Name }}</td>                
                <td>{{ date('F d, Y h:i:s A', strtotime($item->DateRegistered)) }}</td>
                <td>{{ $item->RegistrationMedium }}</td>
                <td>
                    <img src="data:image/png;base64,{{ $item->Signature }}" alt="Sign" width="60">
                </td>
                <td width="120">
                    <div class='btn-group'>
                        <a href="{{ route('preRegEntries.show', [$item->Id]) }}"
                           class='btn btn-primary btn-xs'>
                            <i class="far fa-eye ico-tab-mini"></i>View
                        </a>
                        <a href="{{ route('preRegEntries.edit', [$item->Id]) }}"
                           class='btn btn-danger btn-xs'>
                            <i class="far fa-edit"></i> Edit
                        </a>
                        {{-- 
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            
        @endforeach
    </tbody>
</table>

{{ $data->links() }}