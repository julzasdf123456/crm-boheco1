@php
    use App\Models\ServiceConnections;
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $years[] = date("Y", strtotime( date( 'Y' )." -$i year"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Electricians Housewiring Labor</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body">
                {!! Form::open(['route' => 'electricians.housewiring-labor', 'method' => 'GET']) !!}
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
                        <label for="Term">Term</label>
                        <select name="Term" id="Term" class="form-control form-control-sm">
                            <option value="1"  {{ isset($_GET['Term']) && $_GET['Term']=='1' ? 'selected' : '' }}>1-15</option>
                            <option value="2"  {{ isset($_GET['Term']) && $_GET['Term']=='2' ? 'selected' : '' }}>16-31</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-2">
                        <label for="Year">Year</label>
                        <select name="Year" id="Year" class="form-control form-control-sm">
                            @for ($i = 0; $i < count($years); $i++)
                                <option value="{{ $years[$i] }}" {{ isset($_GET['Year']) && $_GET['Year']==$years[$i] ? 'selected' : '' }}>{{ $years[$i] }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group col-lg-1">
                        <label for="Office">Office</label>
                        <select name="Office" id="Office" class="form-control form-control-sm">
                            <option value="MAIN OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='MAIN OFFICE' ? 'selected' : '' }}>Main Office</option>
                            <option value="SUB-OFFICE"  {{ isset($_GET['Office']) && $_GET['Office']=='SUB-OFFICE' ? 'selected' : '' }}>Sub-Office</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="">Action</label><br>
                        {!! Form::submit('View', ['class' => 'btn btn-primary btn-sm']) !!}
                        <button class="btn btn-sm btn-success" id="download">Download</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>OR Date</th>
                        <th>OR Number</th>
                        <th>Service Account Name</th>
                        <th>Address</th>
                        <th>Electrician</th>
                        <th class="text-right">Labor Share</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('M d, Y', strtotime($item->ORDate)) }}</td>
                                <td>{{ $item->ORNumber }}</td>
                                <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->ServiceAccountName }}</a></td>
                                <td>{{ ServiceConnections::getAddress($item) }}</td>
                                <td>{{ $item->ElectricianName }}</td>
                                <td class="text-right text-red"><strong>₱ {{ is_numeric($item->LaborCharge) ? number_format($item->LaborCharge, 2) : $item->LaborCharge }}</strong></td>
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
            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/electricians/download-housewiring-labor') }}" + "/" + $('#Month').val() + "/" + $('#Term').val() + "/" + $('#Year').val() + "/" + $('#Office').val()
            }) 
        })
    </script>
@endpush