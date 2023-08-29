@php
    header('Access-Control-Allow-Origin: *');
@endphp

<div class="table-responsive">
    <table class="table table-hover" id="sites-table">
        <thead>
        <tr>
            <th>Url</th>
            <th>Notes/Remarks</th>
            <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sites as $item)
            <tr>
                <td><a target="_blank" href="{{ $item->URL }}">{{ $item->URL }}</a></td>
                <td>{{ $item->Notes }}</td>
                <td id="status-{{ $item->id }}">...</td>
                <td width="120">
                    {!! Form::open(['route' => ['sites.destroy', $item->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('sites.show', [$item->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('sites.edit', [$item->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>

                @push('page_scripts')
                    <script>
                        $.ajax({
                            url: "{{ $item->URL }}",
                            type: "GET",
                            timeout:1000,
                            statusCode: {
                                200: function (response) {
                                    alert('Working!');
                                },
                                400: function (response) {
                                    alert('400 - Not working!');
                                },
                                0: function (response) {
                                    alert('0 - Not working!');
                                }              
                            }
                        })
                    </script>
                @endpush
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('page_scripts')
    <script>
        
    </script>
@endpush