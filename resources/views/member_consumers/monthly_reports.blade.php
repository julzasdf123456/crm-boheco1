@php
    use App\Models\MemberConsumers;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Membership Monthly Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'memberConsumers.monthly-reports', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Town">Town</label>
                        <select id="Town" name="Town" class="form-control form-control-sm">
                            <option value="All" {{ isset($_GET['Town']) && $_GET['Town']=='All' ? 'selected' : '' }}>ALL</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Town']) ? ($_GET['Town']=='All' ? '' : ($_GET['Town']==$item->id ? 'selected' : (env('APP_AREA_CODE')==$item->id ? 'selected' : '') ) ) : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>

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

                    <div class="form-group col-md-2">
                        <label for="Office">Office</label>
                        <select name="Office" id="Office" class="form-control form-control-sm">
                            <option value="All" {{ isset($_GET['Office']) && $_GET['Office']=='All' ? 'selected' : '' }}>ALL</option>
                            <option value="MAIN OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                            <option value="SUB-OFFICE" {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                        </select>
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
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm" id="results-table">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>ID</th>
                        <th>Applicant Name</th>
                        <th>Spouse Name</th>
                        <th>Address</th>
                        <th>Category</th>
                        <th>Date Registered</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><a href="{{ route('memberConsumers.show', [$item->Id]) }}">{{ $item->Id }}</a></td>
                                <td>{{ strtoupper(MemberConsumers::serializeMemberNameFormal($item)) }}</td>
                                <td>{{ strtoupper(MemberConsumers::serializeSpouseDeclared($item)) }}</td>
                                <td>{{ strtoupper(MemberConsumers::getAddress($item)) }}</td>
                                <td>{{ strtoupper($item->Type) }}</td>
                                <td>{{ date('M d, Y', strtotime($item->DateApplied)) }}</td>
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
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/member_consumers/download-monthly-reports') }}" + "/" + $('#Town').val() + "/" + $('#Month').val() + "/" + $('#Year').val() + "/" + $('#Office').val()
            })
        })

    </script>
@endpush