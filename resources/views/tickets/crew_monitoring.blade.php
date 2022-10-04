<div class="card shadow-none">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-hard-hat ico-tab"></i>Crew Monitoring</span>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover" id="crew-table">
            <thead>
                <th>Crew</th>
                <th>Total Tickets Assigned</th>
                <th>Not Yet Downloaded</th>
                <th>Downloaded</th>
                <th>Site Arrivals</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            fetchData()
        })

        function fetchData() {
            $.ajax({
                url : "{{ route('tickets.get-crew-monitor-data') }}",
                type : 'GET',
                success : function(res) {
                    $('#crew-table tbody tr').remove()
                    $('#crew-table tbody').append(res)
                    console.log(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting crew data',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush