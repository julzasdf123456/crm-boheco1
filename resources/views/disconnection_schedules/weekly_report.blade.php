@php
    use App\Models\DisconnectionSchedules;
    use App\Models\Towns;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Disconnection Weekly Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        {{-- SETUP --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                {!! Form::open(['route' => 'disconnectionSchedules.weekly-report', 'method' => 'GET']) !!}
                <div class="card-body py-1">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            {!! Form::label('From', 'From') !!}
                            {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : null, ['class' => 'form-control form-control-sm', 'id'=>'From', 'required' => true]) !!}
                        </div>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#From').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush
    
                        <div class="form-group col-lg-2">
                            {!! Form::label('To', 'To') !!}
                            {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : null, ['class' => 'form-control form-control-sm', 'id'=>'To', 'required' => true]) !!}
                        </div>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#To').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush
    
                        <div class="form-group col-lg-3">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-primary btn-sm" title="Show Results"><i class="fas fa-check-circle ico-tab-mini"></i>View</button>
                            {{-- <button type="submit" class="btn btn-link text-success" title="Download in Excel File"><i class="fas fa-file-download"></i></button> --}}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        {{-- VIEW --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <thead>
                            <tr>
                                <td style="width: 140px;" class="text-center" rowspan="2"><strong>Date</strong></td>
                                <td  style="width: 140px;"class="text-center" rowspan="2"><strong>Location</strong></td>
                                <td style="min-width: 80px; max-width: 190px;" class="text-center" rowspan="2"><strong>Blocks</strong></td>
                                <td class="text-center" rowspan="2"><strong>No. of Accounts<br>for Disco</strong></td>
                                <td class="text-center" colspan="2"><strong>No. Paid</strong></td>
                                <td class="text-center" rowspan="2"><strong>No. of<br>Disconnected</strong></td>
                                <td class="text-center" rowspan="2"><strong>Accts. w/ Remarks</strong></td>
                                <td class="text-center" rowspan="2"><strong>Unfinished</strong></td>
                                <td style="width: 100px;" class="text-center" rowspan="2"><strong>% Accomplished</strong></td>
                                <td style="width: 170px;" class="text-center" rowspan="2"><strong>Crew</strong></td>
                                <td class="text-center" rowspan="2"><strong>Remarks Poll</strong></td>
                            </tr>
                            <tr>
                                <td class="text-center"><strong>Bills</strong></td>
                                <td class="text-center"><strong>S.F.</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                @php
                                    $percent = DisconnectionSchedules::getPercent($item->Finished, $item->Accounts);
                                @endphp
                                <tr>
                                    <td><strong>{{ date('M d, Y', strtotime($item->Day)) }}</strong></td>
                                    <td>{{ Towns::parseTownCode(substr($item->Blocks, 0, 2)) }}</td>
                                    <td>{{ $item->Blocks }}</td>
                                    <td>{{ $item->Accounts }}</td>
                                    <td>{{ $item->BillsTotal }}</td>
                                    <td>{{ $item->BillsPaid }}</td>                                    
                                    <td>{{ $item->Disconnected }}</td>                                 
                                    <td>{{ $item->WithRemarks }}</td>                                 
                                    <td>{{ $item->Unfinished }}</td>                               
                                    <td class="{{ $percent > 0 ? 'text-success' : 'text-muted' }}"><strong>{{ $percent }} %</strong></td>                               
                                    <td>{{ strtoupper($item->DisconnectorName) }}</td>                       
                                    <td>{{ $item->RemarksPoll }}</td>  
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        
    </script>
@endpush

