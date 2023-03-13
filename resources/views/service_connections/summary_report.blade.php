@php
    use App\Models\MemberConsumers;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Monthly Summary Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'serviceConnections.summary-report', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Month">Month</label>
                        <select name="Month" id="Month" class="form-control form-control-sm">
                            <option value="01"  {{ isset($_GET['Month']) && $_GET['Month']=='01' ? 'selected' : '' }}>JANUARY</option>
                            <option value="02"  {{ isset($_GET['Month']) && $_GET['Month']=='02' ? 'selected' : '' }}>FEBRUARY</option>
                            <option value="03"  {{ isset($_GET['Month']) && $_GET['Month']=='03' ? 'selected' : '' }}>MARCH</option>
                            <option value="04"  {{ isset($_GET['Month']) && $_GET['Month']=='04' ? 'selected' : '' }}>APRIL</option>
                            <option value="05"  {{ isset($_GET['Month']) && $_GET['Month']=='05' ? 'selected' : '' }}>MAY</option>
                            <option value="06"  {{ isset($_GET['Month']) && $_GET['Month']=='06' ? 'selected' : '' }}>JUNE</option>
                            <option value="07"  {{ isset($_GET['Month']) && $_GET['Month']=='07' ? 'selected' : '' }}>JULY</option>
                            <option value="08"  {{ isset($_GET['Month']) && $_GET['Month']=='08' ? 'selected' : '' }}>AUGUST</option>
                            <option value="09"  {{ isset($_GET['Month']) && $_GET['Month']=='09' ? 'selected' : '' }}>SEPTEMBER</option>
                            <option value="10"  {{ isset($_GET['Month']) && $_GET['Month']=='10' ? 'selected' : '' }}>OCTOBER</option>
                            <option value="11"  {{ isset($_GET['Month']) && $_GET['Month']=='11' ? 'selected' : '' }}>NOVEMBER</option>
                            <option value="12"  {{ isset($_GET['Month']) && $_GET['Month']=='12' ? 'selected' : '' }}>DECEMBER</option>
                        </select>
                    </div>

                    <div class="form-group col-md-1">
                        <label for="Year">Year</label>
                        <input type="text" maxlength="4" id="Year" name="Year" placeholder="Year" value="{{ isset($_GET['Year']) ? $_GET['Year'] : date('Y') }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check ico-tab-mini"></i>View</button>
                        <button id="download" class="btn btn-sm btn-success"><i class="fas fa-download ico-tab-mini"></i>Download</button>
                    </div>
                </div>
                
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="col-lg-8">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm" id="results-table">
                    <thead>
                        <th>Towns</th>
                        <th>Total Applications</th>
                        <th>Verified Applicants<br>Based on this Month's<br>Applicantions</th>
                        <th>For Inspections<br>Based on this Month's<br>Applicantions</th>
                        <th>Executed Based on<br>this Month's<br>Applications</th>
                        <th>Total Inspections<br>For this Month</th>
                        <th>Total Energization<br>For this Month</th>
                    </thead>
                    <tbody>
                        @php
                            $applicants = 0;
                            $approvedThisMonth = 0;
                            $inspectionThisMonth = 0;
                            $executedThisMonth = 0;
                            $inspectionsTotal = 0;
                            $energizationsTotal = 0;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->Town }}</td>
                                <td>{{ $item->TotalApplicants }}</td>
                                <td>{{ $item->ApprovedThisMonth }}</td>
                                <td>{{ $item->ForInspectionThisMonth }}</td>
                                <td>{{ $item->ExecutedThisMonth }}</td>
                                <td>{{ $item->TotalInspections }}</td>
                                <td>{{ $item->TotalEnergizations }}</td>
                            </tr>

                            @php
                                $applicants += intval($item->TotalApplicants);
                                $approvedThisMonth += intval($item->ApprovedThisMonth);
                                $inspectionThisMonth += intval($item->ForInspectionThisMonth);
                                $executedThisMonth += intval($item->ExecutedThisMonth);
                                $inspectionsTotal += intval($item->TotalInspections);
                                $energizationsTotal += intval($item->TotalEnergizations);
                            @endphp
                        @endforeach
                        <tr>
                            <th>TOTAL</th>
                            <th>{{ $applicants }}</th>
                            <th>{{ $approvedThisMonth }}</th>
                            <th>{{ $inspectionThisMonth }}</th>
                            <th>{{ $executedThisMonth }}</th>
                            <th>{{ $inspectionsTotal }}</th>
                            <th>{{ $energizationsTotal }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- ENERGIZATION ORDER --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Energization Order Issuance Summary</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Main Office</th>
                        <th>Sub-Office</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                        @if ($summaryData != null)
                            <tr>
                                <td>{{ $summaryData->EOIssuedMain }}</td>
                                <td>{{ $summaryData->EOIssuedSub }}</td>
                                <td>{{ $summaryData->EOIssuedSub + $summaryData->EOIssuedMain }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BILLED CONSUMERS ORDER --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Billed Consumers Summary</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover">
                    <tbody>
                        @if ($billsSummary != null)
                            <tr>
                                <td><strong>Complete Billed Consumers for {{ date('M Y', strtotime($billsSummary->PrevMonth)) }}</strong></td>
                                <td>{{ number_format($billsSummary->PrevMonthBillsTotal) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Billed Consumers for Selected Month ({{ date('M Y', strtotime($billsSummary->CurrentMonth)) }})</strong></td>
                                <td>{{ number_format($billsSummary->BillsTotalAsOf) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/service_connections/download-summary-report') }}" + "/" + $('#Month').val() + "/" + $('#Year').val()
            })
        })

    </script>
@endpush