@php
    use App\Models\Tickets;

    $inspection = Tickets::find($tickets->TaggedTicketId);
@endphp

@if ($inspection != null)
<div class="row">
    <div class="col-md-12 col-lg-12">
        <table class="table table-hover table-sm table-borderless">
            <tr>
                <th>Status :</th>
                <td>{{ $inspection->Status }}</td>
            </tr>
            <tr>
                <th>OR Number :</th>
                <td>{{ $inspection->ORNumber }}</td>
            </tr>
            <tr>
                <th>OR Date :</th>
                <td>{{ $inspection->ORDate }}</td>
            </tr>
            <tr>
                <th>Office :</th>
                <td>{{ $inspection->Office }}</td>
            </tr>
            <tr>
                <th>Crew Assigned :</th>
                <td>{{ $inspection->StationName }}</td>
            </tr>
            <tr>
                <th>Lineman/Crew/Group Executed :</th>
                <td>{{ $inspection->LinemanCrewExecuted }}</td>
            </tr>
            <tr>
                <th>Notes/Remarks :</th>
                <td>{{ $inspection->Notes }}</td>
            </tr>
            <tr>
                <th>Date Filed :</th>
                <td>{{ date('M d, Y h:i A', strtotime($inspection->created_at)) }}</td>
            </tr>            
            <tr>
                <th>Date Executed/Acted :</th>
                <td>{{ $inspection->DateTimeLinemanExecuted != null ? date('M d, Y h:i A', strtotime($inspection->DateTimeLinemanExecuted)) : '' }}</td>
            </tr>
        </table>             
    </div>                            
</div>
@endif

