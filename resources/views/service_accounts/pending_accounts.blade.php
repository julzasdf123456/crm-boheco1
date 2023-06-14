@php
    use App\Models\ServiceConnections;
@endphp
@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Pending Accounts</h4>
                    <i class="text-muted">Energized service connection accounts for activation</i>
                </span>
            </div>

            <div class="col-lg-6">
                <form action="{{ route('serviceAccounts.pending-accounts') }}" method="GET">
                    <button class="btn btn-sm btn-warning float-right" id="print">Print</button>
                    <button class="btn btn-sm btn-primary float-right" style="margin-right: 10px;" type="submit">Filter</button>
                    <select name="Office" id="Office" class="form-control form-control-sm float-right" style="width: 150px; margin-right: 10px; margin-left: 5px;">
                        <option value="All">All</option>
                        <option value="MAIN OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                        <option value="SUB-OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                    </select>
                    <label for="Office" class="float-right">Select Office</label>
                </form> 
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    
    <div class="row">
        @if(session()->has('message'))
            <div class="col-lg-12">
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            </div>
        @endif
        <div class="col-lg-12">
            <table class="table table-hover table-sm">
                <thead>
                    <th width="3%"></th>
                    <th>Account No.</th>
                    <th>Account Name</th>
                    <th>Account Address</th>
                    <th>Account Type</th>
                    <th>Application</th>
                    <th>Energized At</th>
                    <th width="8%"></th>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($serviceConnections as $item)
                        <tr>
                            <th>{{ $i }}</th>
                            <td><a href="{{ route('serviceConnections.show', [$item->id]) }}" target="_blank">{{ $item->id }}</a></td>
                            <td>{{ $item->ServiceAccountName }} ({{ $item->AccountCount }})<i class="fas fa-check-circle text-primary" style="font-size: .75em;"></i></td>
                            <td>{{ ServiceConnections::getAddress($item) }}</td>
                            <td>{{ $item->AccountType }} ({{ $item->Alias }})</td>
                            <td>{{ $item->ConnectionApplicationType }}</td>
                            <td>
                                {{ date('M d, Y', strtotime($item->DateTimeOfEnergization)) }}
                                <br>
                                @php
                                    $past = new \DateTime($item->created_at);
                                    $present = new \DateTime(date('Y-m-d H:i:s'));
                                    $days = $present->diff($past);
                                    if ($days->format('%a')==0) {
                                        $color = '#2e7d32';
                                    } elseif ($days->format('%a')==1) {
                                        $color = '#ff6f00';
                                    } elseif ($days->format('%a')==2) {
                                        $color = '#e64a19';
                                    } else {
                                        $color = '#c62828';
                                    }
                                @endphp 
                                <span class="badge" style="background-color: {{ $color }};">
                                    {{ $days->format('%a days & %h hrs') }}
                                </span>
                            </td>
                            <td class="text-right" >
                                @if ($item->ConnectionApplicationType == 'Relocation')
                                    {{-- <a href="{{ route('serviceAccounts.relocation-form', [$item->AccountNumber, $item->id]) }}" title="Proceed relocating {{ $item->ServiceAccountName }}" ><i class="fas fa-arrow-circle-right text-success"></i></a> --}}
                                @elseif ($item->ConnectionApplicationType == 'Change Name')
                                    {{-- <a href="{{ route('serviceAccounts.confirm-change-name', [$item->id]) }}" title="Proceed Change Name"><i class="fas fa-arrow-circle-right text-success"></i></a> --}}
                                @else
                                    <a href="{{ route('accountMasters.account-migration-step-one', [$item->id]) }}" title="Proceed activating {{ $item->ServiceAccountName }}" ><i class="fas fa-arrow-circle-right text-success"></i></a>
                                @endif                                
                            </td>
                        </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#print').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/account_masters/print-sdir') }}" + "/" + $('#Office').val()
            })
        })
    </script>
@endpush