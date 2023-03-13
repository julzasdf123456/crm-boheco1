@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Daily Monitoring</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-2 col-md-4">
            {{-- FORM --}}
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Pick Date</span>
                </div>
                <div class="card-body">
                    <div id="target" style="position:relative" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" id="daypicker" data-toggle="datetimepicker" data-target="#target" autocomplete="off"/>
                    </div>
                </div>
            </div>

            {{-- TOTAL --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Summary</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tbody>
                            <tr>
                                <td>Main Office</td>
                                <td><strong id="main-total"></strong></td>
                            </tr>
                            <tr>
                                <td>Sub-Office</td>
                                <td><strong id="sub-total"></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong id="total"></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-10 col-md-8">
            <div class="card">
                <div class="card-body table-responsive px-0">
                    <table id="memberconsumer-table" class="table table-hover">
                        <thead>
                            <th>Membership ID</th>
                            <th>Applicant Name</th>
                            <th>Address</th>
                            <th>OR Number</th>
                            <th>OR Date</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_css')
<link rel="stylesheet" href="{{ URL::asset('css/tempusdominus-bootstrap-4.css'); }} ">
@endpush

@push('page_scripts')
<script src="{{ URL::asset('js/tempusdominus-bootstrap-4.js'); }}"></script>
<script type="text/javascript">
    // INITIALIZE DATE PICKER
    $(document).ready(function() {
        $("#target").datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: new Date(),
            inline : true,
            sideBySide : true,
        });

        fetchData($('#daypicker').val())
        fetchSummary($('#daypicker').val())

        $("#target").on('change.datetimepicker', function() {
            fetchData($('#daypicker').val())   
            fetchSummary($('#daypicker').val())         
        })
    })

    function fetchData(day) {
        $.ajax({
            url : "{{ route('memberConsumers.daily-monitor-data') }}",
            type : 'GET',
            data : {
                Date : day,
            },
            success : function(res) {
                $('#memberconsumer-table tbody tr').remove()
                $('#memberconsumer-table tbody').append(res)
            },
            error : function(err) {
                alert('An error occurred while fetching data. See console for details!')
            }
        })
    }

    function fetchSummary(day) {
        $.ajax({
            url : "{{ route('memberConsumers.daily-monitor-total') }}",
            type : 'GET',
            data : {
                Date : day,
            },
            success : function(res) {
                $('#main-total').text(res['MainOfficeCount'])
                $('#sub-total').text(res['SubOfficeCount'])
                $('#total').text(parseInt(res['SubOfficeCount']) + parseInt(res['MainOfficeCount']))
            },
            error : function(err) {
                Toast.fire({
                    icon : 'error',
                    text : 'Error getting membership summary'
                })
            }
        })
    }
    
</script>
@endpush  