@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Abrupt Increase/Decrease Monitoring </h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="ConsumerType">Select Consumer Type</label>
                        <select name="ConsumerType" id="ConsumerType" class="form-control form-control-sm">
                            @foreach ($consumerTypes as $item)
                                <option value="{{ $item->ConsumerType }}">{{ $item->ConsumerType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Action</label><br>
                        <button class="btn btn-sm btn-primary" id="filter">Filter</button>
                        <div id="loader" class="spinner-border gone text-success float-right" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-bordered table-hover" id="res-table">
                    <thead>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>From</th>
                        <th>kWh Usage</th>
                        <th>To </th>
                        <th>kWh Usage</th>
                        <th>Difference <br> (in kWh)</th>
                        <th>% Inc/Dec</th>
                    </thead>
                    <tbody>

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
            $('#filter').on('click', function(e) {
                e.preventDefault()
                fetchResults()
            })
        })

        function fetchResults() {
            $('#res-table tbody tr').remove()
            var consType = $('#ConsumerType').val()

            if (jQuery.isEmptyObject(consType)) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Please select parameters'
                })
            } else {
                $('#loader').removeClass('gone')
                $.ajax({
                    url : "{{ route('accountMasters.get-abrupt-increase-decrease') }}",
                    type : 'GET',
                    data : {
                        ConsumerType : consType,
                    },
                    success : function(res) {
                        $('#loader').addClass('gone')
                        $('#res-table tbody').append(res)
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error getting data'
                        })
                        $('#loader').addClass('gone')
                    }
                })
            }
        }
    </script>
@endpush