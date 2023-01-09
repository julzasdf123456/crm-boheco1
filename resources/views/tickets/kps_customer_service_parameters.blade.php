@php
    use App\Models\MemberConsumers;
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>NEW KPS Customer Service Parameter Standard Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'tickets.kps-customer-service-parameters', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    {{-- <div class="form-group col-lg-2">
                        <label for="Ticket">Ticket</label>
                        <select class="custom-select select2"  id="Ticket">
                            <option value="All">All</option>
                            @foreach ($parentTickets as $items)
                                <optgroup label="{{ $items->Name }}">
                                    @php
                                        $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->orderBy('Name')->get();
                                    @endphp
                                    @foreach ($ticketsRep as $item)
                                        <option value="{{ $item->id }}">{{ $item->Name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div> --}}

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
                        {{-- <button id="download" class="btn btn-sm btn-success"><i class="fas fa-download ico-tab-mini"></i>Download</button> --}}
                    </div>
                </div>
                
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered" id="results-table">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2">Customer Service Parameter</th>
                            <th class="text-center" rowspan="2" colspan="3">Compliance</th>
                            <th class="text-center" colspan="2">Actual Hours *</th>
                        </tr>
                        <tr>
                            <th class="text-center">Previous Month</th>
                            <th class="text-center">Current Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>1. </strong> Processing/ Approval  of applications for service connection (with complete requirements)</td>
                            <td><i>Within</i></td>
                            <td class="text-center">24</td>
                            <td><strong>hours</strong> upon receipt of application</td>
                            <td class="text-center text-red"><strong></strong>-</td>
                            <td class="text-center text-red"><strong></strong>-</td>
                        </tr>
                        <tr>
                            <td><strong>2. </strong> Service drop connection</td>
                            <td><i>Within</i></td>
                            <td class="text-center">48</td>
                            <td><strong>hours</strong> upon payment of fees</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category2Previous'] : 0 }}</strong> hours</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category2Current'] : 0 }}</strong> hours</td>
                        </tr>
                        <tr>
                            <td><strong>3. </strong> Restoration of service after line fault on the secondary side, including service drop/ lateral</td>
                            <td><i>Within</i></td>
                            <td class="text-center">4</td>
                            <td><strong>hours</strong> upon on-site arrival</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category3Previous'] : 0 }}</strong> hours</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category3Current'] : 0 }}</strong> hours</td>
                        </tr>
                        <tr>
                            <td><strong>4. </strong> Response time on Consumer  Complaints (Billing, Payment and Meter Complaints)</td>
                            <td><i>Within</i></td>
                            <td class="text-center">24</td>
                            <td><strong>hours</strong> after receipt of complaints</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category4Previous'] : 0 }}</strong> hours</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category4Current'] : 0 }}</strong> hours</td>
                        </tr>
                        <tr>
                            <td><strong>5. </strong> Timeframe in informing Customer on scheduled power interruptions</td>
                            <td><i>At least</i></td>
                            <td class="text-center">72</td>
                            <td><strong>hours</strong> before scheduled interruption</td>
                            <td class="text-center text-red"><strong></strong>-</td>
                            <td class="text-center text-red"><strong></strong>-</td>
                        </tr>
                        <tr>
                            <td><strong>6. </strong> Response time to  emergency calls</td>
                            <td><i>Within</i></td>
                            <td class="text-center">0.5</td>
                            <td><strong>hours</strong> after receipt of call</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category6Previous'] : 0 }}</strong> hours</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category6Current'] : 0 }}</strong> hours</td>
                        </tr>
                        <tr>
                            <td><strong>7. </strong> Response time to reconnection of service due to disconnection</td>
                            <td><i>Within</i></td>
                            <td class="text-center">24</td>
                            <td><strong>hours</strong> after settlement of amount due/compromise agreement</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category7Previous'] : 0 }}</strong> hours</td>
                            <td class="text-center text-red"><strong>{{ $data != null ? $data['Category7Current'] : 0 }}</strong> hours</td>
                        </tr>
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
            
        })

    </script>
@endpush