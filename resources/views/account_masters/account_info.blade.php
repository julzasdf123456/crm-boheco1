@php
    use App\Models\AccountMaster;
@endphp
<div class="card card-outline shadow-none {{ $accountMaster->AccountStatus=='ACTIVE' ? 'card-success' : 'card-danger' }}" title="{{ $accountMaster->AccountStatus=='ACTIVE' ? 'Account Active' : 'Account Disconnected' }}"">
    <div class="card-header border-0">
        <span class="card-title">
            Account Information
        </span>

        <div class="card-tools">
            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                <button class="btn btn-tool" id="change-name" data-toggle="modal" data-target="#modal-change-name" title="Change Name"><i class="fas fa-pen"></i></button>
            @endif
            <button class="btn btn-tool" data-toggle="modal" data-target="#modal-change-name-history" title="Change Name History"><i class="fas fa-history"></i></button>
            <button class="btn btn-tool" data-toggle="modal" data-target="#modal-relocation-history" title="Location History (Relocations)"><i class="fas fa-map"></i></button>
            <button class="btn btn-tool" data-toggle="modal" data-target="#modal-view-map" title="View in map"><i class="fas fa-map-marker-alt"></i></button>
        </div>
    </div>
    <div class="card-body table-responsive px-0">
        <table class="table table-hover table-sm">
            <thead></thead>
            <tbody>
                <tr>
                    <td class="text-muted">Status</td>
                    <td><strong class="badge {{ $accountMaster->AccountStatus=='ACTIVE' ? 'badge-success' : 'badge-danger' }}">{{ $accountMaster->AccountStatus }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Account Number</td>
                    <td class="text-primary"><strong>{{ $accountMaster->AccountNumber }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Account Address</td>
                    <th>{{ $accountMaster->ConsumerAddress }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Consumer Type</td>
                    <th>{{ $accountMaster->ConsumerType }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Connection Date</td>
                    <th>{{ $accountMaster->ConnectionDate==null ? '-' : date('F d, Y', strtotime($accountMaster->ConnectionDate)) }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Route</td>
                    <th>{{ $accountMaster->Route }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Sequence No.</td>
                    <th>{{ $accountMaster->SequenceNumber }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Meter Number</td>
                    <th class="text-primary">{{ $accountMaster->MeterNumber }}</th>
                </tr>
                <tr>
                    <th>Multiplier</th>
                    <th>{{ $meter != null ? $meter->Multiplier : "1" }}</th>
                </tr>
                <tr>
                    <th>Coreloss</th>
                    <th>{{ $accountMaster->CoreLoss }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Contact Number</td>
                    <th>{{ $accountMaster->ContactNumber }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Email Address</td>
                    <th>{{ $accountMaster->Email }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Feeder</td>
                    <th>{{ $accountMaster->Feeder }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Transformer</td>
                    <th>{{ $accountMaster->Transformer }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Pole Number</td>
                    <th>{{ $accountMaster->Pole }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Date of Entry</td>
                    <th class="{{ $accountMaster->DateEntry != null ? date('M d, Y', strtotime($accountMaster->DateEntry)) : '' }}"></th>
                </tr>
                
            </tbody>            
        </table>
    </div>
</div>

@if ($accountMaster->Item1 != null)
    @include('account_masters.map_modal')    
@endif


{{-- CHANGE NAME --}}
{{-- <div class="modal fade" id="modal-change-name" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Name</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="From">From</label>
                    <input type="text" name="From" id="From" value="{{ $accountMaster->ServiceAccountName }}" class="form-control" readonly>

                    <label for="To">To</label>
                    <input type="text" name="To" id="To" class="form-control" autofocus>

                    <label for="ChangeNameNotes">Notes</label>
                    <textarea type="text" name="ChangeNameNotes" id="ChangeNameNotes" placeholder="Notes/Remarks" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="change-name-proceed">Proceed</button>
            </div>
        </div>
    </div>
</div> --}}

{{-- CHANGE NAME HISTORY --}}
{{-- <div class="modal fade" id="modal-change-name-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Previous Account Names</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Account Names</th>
                        <th>Remarks</th>
                        <th>Changed By</th>
                        <th>Changed On</th>
                    </thead>
                    <tbody>
                        @foreach ($changeNameHistory as $item)
                            <tr>
                                <td>{{ $item->OldAccountName }}</td>
                                <td>{{ $item->Notes }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ date('F d, Y, h:i:s A', strtotime($item->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

{{-- RELOCATION HISTORY --}}
{{-- <div class="modal fade" id="modal-relocation-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Previous Account Addresses</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Address</th>
                        <th>Area Code</th>
                        <th>Sequence</th>
                        <th>Relocation Date</th>
                    </thead>
                    <tbody>
                        @foreach ($relocationHistory as $item)
                            <tr>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td>{{ $item->AreaCode }}</td>
                                <td>{{ $item->SequenceCode }}</td>
                                <td>{{ date('F d, Y', strtotime($item->RelocationDate)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

{{-- @push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#change-name-proceed').on('click', function() {
                changeName()
            })
        })   
        
        function changeName() {
            if (jQuery.isEmptyObject($('#To').val())) {
                Swal.fire({
                    title : 'Empty Name',
                    text : 'Provide a new name to continue',
                    icon : 'error'
                })
            } else {
                $.ajax({
                    url : "{{ route('serviceAccounts.change-name') }}",
                    type : 'GET',
                    data : {
                        id : '{{ $serviceAccounts->id }}',
                        NewName : $('#To').val(),
                        Notes : $('#ChangeNameNotes').val()
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            title : 'Oops',
                            text : 'An error occurred while trying to change the name',
                            icon : 'error'
                        })
                    }
                })
            }
            
        }
    </script>
@endpush --}}