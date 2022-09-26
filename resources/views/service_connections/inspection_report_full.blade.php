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
            <div class="col-sm-8">
                <p><strong>Inspectors and Verifiers Monitoring</strong></p>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-5">
                        <label for="" class="float-right">Select Month</label>
                    </div>

                    <div class="col-sm-7">
                        <div class="form-group">
                            <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <p>Inspection Summary</p>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover table-bordered" id="table-summary">
                    <thead>
                        <th class="text-center">Inspector</th>
                        <th class="text-center">Inspections<br>Filed Today</th>
                        <th class="text-center">For Inspection</th>
                        <th class="text-center">Approved</th>
                        <th class="text-center">Total Inspections</th>
                        <th class="text-center">No. of Days</th>
                        <th class="text-center">Average Daily</th>
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
            getSummary()

            $('#ServicePeriod').on('change', function() {
                getSummary()
            })
        })

        function getSummary() {
            $.ajax({
                url : "{{ route('serviceConnections.get-inspection-summary-data') }}",
                type : 'GET',
                data : {
                    ServicePeriod : $('#ServicePeriod').val()
                },
                success : function(res) {
                    $('#table-summary tbody tr').remove()
                    $('#table-summary tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error fetching inspection summary'
                    })
                }
            })
        }
    </script>
@endpush