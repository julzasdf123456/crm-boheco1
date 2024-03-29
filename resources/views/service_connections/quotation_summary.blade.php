@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
    use App\Models\BillsOfMaterialsSummary;
@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Bill of Materials Summary</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Summary</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- <span style="margin-bottom: 10px;">
        <a href="{{ route('serviceConnections.forward-to-verficaation', [$serviceConnection->id]) }}" class="btn btn-success">Finish <i class="fas fa-check-circle"></i></a> 
        <i class="text-muted" style="margin-left: 15px;">Finish and forward to Verification</i>
    </span>

    <div class="divider"></div> --}}

    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#materials" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Material Summary</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#construction" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Construction Summary</a>
        </li>
    </ul>
    <div class="tab-content" id="custom-content-below-tabContent">
        {{-- Bills of Materials --}}
        <div class="tab-pane fade active show" id="materials" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
            <div class="row">
                {{-- <div class="col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <div class="header">
                                <p class="text-center p-0" style="margin: 0;"><strong>{{ env("APP_COMPANY") }}</strong></p>
                                <p class="text-center p-0">{{ env("APP_ADDRESS") }}</p>

                                <h4 class="text-center p-0">Bill of Materials</h4>
                            </div>
                        </div>
                    </div>                    
                </div> --}}

                <div class="col-lg-4 col-md-6">
                    {{-- PAYMENT SUMMARY --}}
                    <div class="card card-outline card-primary shadow-none">
                        <div class="card-header">
                            <span class="card-title">Payment Summary</span>

                            <div class="card-tools">
                                <a href="{{-- route('billOfMaterialsMatrices.download-bill-of-materials', [$serviceConnection->id]) --}}" class="btn btn-tool text-success" title="Download Excel File"><i class="fas fa-download"></i></a>
                                <button class="btn btn-tool" title="Print"><i class="fas fa-print"></i></button>
                            </div>
                        </div>
                        <div class="card-body table-resposive p-0">
                            <table class="table table-sm table-borderless table-hover">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Material Cost</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="MaterialCost" placeholder="Material Cost Amount" autofocus value="{{ $totalTransactions != null ? $totalTransactions->MaterialCost : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Labor Cost</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="LaborCost" placeholder="Labor Cost Amount" autofocus value="{{ $totalTransactions != null ? $totalTransactions->LaborCost : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Contngcy., Handling, etc.</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="ContingencyCost" placeholder="Other Cost Amount" autofocus value="{{ $totalTransactions != null ? $totalTransactions->ContingencyCost : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Materials VAT (12%)</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="MaterialsVAT" placeholder="Materials VAT" autofocus value="{{ $totalTransactions != null ? $totalTransactions->MaterialsVAT : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-info"><strong>Sub-Total</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="MaterialsSubTotal" placeholder="Materials Sub-Total" autofocus>
                                        </td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dbdbdb;">
                                        <td class="text-muted">Transformer Full Amount</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="TransformerCost" placeholder="Transformer Amount" autofocus autofocus value="{{ $totalTransactions != null ? $totalTransactions->TransformerCost : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Transformer VAT (12%)</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="TransformerVAT" placeholder="Transformer VAT" autofocus autofocus value="{{ $totalTransactions != null ? $totalTransactions->TransformerVAT : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Transformer is Ammortized</td>
                                        <td class="text-right">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="TransformerAmmortized" {{ $totalTransactions != null ? ($totalTransactions->TransformerAmmortizationTerms != null ? 'checked' : '') : '' }}>
                                                <label class="custom-control-label" for="TransformerAmmortized" id="TransformerAmmortizedLabel">No</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-info"><strong>Sub-Total</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-right" id="TransformerSubTotal" placeholder="Transformer Sub-Total" autofocus>
                                        </td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dbdbdb;">
                                        <td class="text-danger"><strong>Grand Total</strong></td>
                                        <td>
                                            <input type="number" style="font-weight: bold;" class="form-control form-control-sm text-right text-danger" id="GrandTotal" placeholder="Grand Total" autofocus>
                                        </td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dbdbdb;" class="text-right">
                                        <td colspan="2">
                                            <button id="save-btn" class="btn btn-primary btn-sm">Save <i style="margin-left: 10px;" class="fas fa-download"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                        
                    </div>

                    <div class="card shadow-none card-disabled">
                        <div class="card-header">
                            <span class="card-title">Structures in this BoM</span>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm">
                                <thead>
                                    <th>Structure</th>
                                    <th>Quantity</th>
                                </thead>
                                <tbody>
                                    @if ($structures != null)
                                        @foreach ($structures as $item)
                                            <tr>
                                                <td><a href="{{ route('structures.show', [$item->id]) }}">{{ $item->StructureId }}</a></td>
                                                <td>{{ $item->Quantity }}</td>
                                            </tr>
                                        @endforeach
                                    @endif                                    
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-6">
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                Date : <strong>{{ date('F d, Y') }}</strong><br>
                                Project Name: <strong>{{ $serviceConnection->ServiceAccountName }}</strong><br>
                                Project Address: <strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong><br>
                                Project Load: <strong>{{ $serviceConnection->LoadCategory }} kVA</strong><br>
                                Application Type: <strong>{{ $serviceConnection->AccountApplicationType }}</strong><br>
                                Account Type: <strong>{{ $serviceConnection->AccountType }}</strong><br>
                            </address>
                        </div>
                    </div>

                    <div class="table-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>NEA Code</th>
                                <th>Description</th>
                                <th class="text-right">Unit Cost (Php)</th>
                                <th class="text-right">Project Requirements</th>
                                <th class="text-right">Extended Cost</th>
                            </thead>
                            <tbody>
                                @foreach ($materials as $item)
                                    <tr>
                                        <td class="px-4">{{ $item->id }}</td>
                                        <td>{{ $item->Description }}</td>
                                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                        <td class="text-right">{{ $item->ProjectRequirements }}</td>
                                        <td class="text-right">{{ number_format($item->Cost, 2) }}</td>
                                    </tr>
                                @endforeach
                                @if ($transformers != null)
                                    <tr>
                                        <th>Transformer</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    @foreach ($transformers as $item)
                                        <tr>
                                            <td class="px-4">{{ $item->id }}</td>
                                            <td>{{ $item->Description }}</td>
                                            <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                            <td class="text-right">{{ $item->Quantity }}</td>
                                            <td class="text-right">{{ number_format(floatval($item->Amount) * floatval($item->Quantity), 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif 
                                <tr>
                                    <td style="border-top: 1px solid #333333;"></td>
                                    <td style="border-top: 1px solid #333333;" class="text-right">Sub-Total</td>  
                                    <td style="border-top: 1px solid #333333;"></td>
                                    <td style="border-top: 1px solid #333333;"></td>
                                    <td style="border-top: 1px solid #333333;" class="text-right">{{ number_format($billOfMaterialsSummary->SubTotal, 2) }}</td>  
                                </tr>  
                                <tr>
                                    <td></td>
                                    <td class="text-right">Transformer Labor Cost</td>  
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($billOfMaterialsSummary->TransformerLaborCost, 2) }}</td>  
                                </tr>  
                                <tr>
                                    <td></td>
                                    <td class="text-right">Other Materials Labor Cost</td>  
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($billOfMaterialsSummary->MaterialLaborCost, 2) }}</td>  
                                </tr>  
                                <tr>
                                    <td></td>
                                    <td class="text-right">Total Labor Cost</td>  
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($billOfMaterialsSummary->LaborCost, 2) }}</td>  
                                </tr>  
                                <tr>
                                    <td></td>
                                    <td class="text-right">Contengency, Engineering & Handling, Etc.</td>  
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($billOfMaterialsSummary->HandlingCost, 2) }}</td> 
                                </tr>  
                                <tr>
                                    <td></td>
                                    <td class="text-right">VAT ({{ BillsOfMaterialsSummary::getVat() * 100 }} %)</td>  
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($billOfMaterialsSummary->TotalVAT, 2) }}</td> 
                                </tr> 
                                <tr>
                                    <th></th>
                                    <th class="text-right">Overall Total</th>  
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">{{ number_format($billOfMaterialsSummary->Total, 2) }}</th> 
                                </tr>                       
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Construction Assets --}}
        <div class="tab-pane fade show" id="construction" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="header">
                            <p class="text-center p-0" style="margin: 0;"><strong>{{ env("APP_COMPANY") }}</strong></p>
                            <p class="text-center p-0">{{ env("APP_ADDRESS") }}</p>

                            <h4 class="text-center p-0">Bill of Materials</h4>
                        </div>                  
                    </div>
                </div>
                <table class="table table-sm">
                    <thead>
                        <th width="8%" class="text-center">Item</th>
                        <th >Description</th>
                        <th class="text-right">Quantity</th>
                    </thead>
                    <tbody>
                        @if ($poles != null)
                            @php
                                $poleInc = 1;
                            @endphp
                            @foreach ($poles as $item)
                                <tr>
                                    <td width="8%" class="text-center">
                                        @php
                                            if ($poleInc < 2) {
                                                echo IDGenerator::numberToRomanRepresentation($poleInc);
                                            }
                                            $poleInc++;
                                        @endphp
                                    </td>
                                    <td>{{ $item->Description }}</td>
                                    <td class="text-right">{{ $item->ProjectRequirements }}</td>
                                </tr>
                            @endforeach                           
                        @endif

                        @if ($conAss != null)
                            @php
                                $i = 1;
                                $first = null;
                                $rank = count($poles) > 0 ? 2 : 1;
                            @endphp
                            @foreach ($conAss as $item)
                                <tr>
                                    <td width="8%" class="text-center">
                                        @php
                                            if ($i < 2) {
                                                $first = $item->ConAssGrouping;
                                                echo IDGenerator::numberToRomanRepresentation($rank);
                                                $rank += 1;
                                            }

                                            if ($item->ConAssGrouping != $first) { 
                                                echo IDGenerator::numberToRomanRepresentation($rank);
                                                $rank += 1;                                                                                          
                                            } 

                                            $first = $item->ConAssGrouping;     
                                            
                                            $i++;
                                        @endphp
                                    </td>
                                    <td>                                        
                                        {{ $item->StructureId }}
                                        @php
                                            if ($item->Type == 'A_DT') {
                                                echo 'Transformer';
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-right">{{ $item->Quantity }}</td>
                                </tr>
                            @endforeach                        
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
            showTotals()

            $('#MaterialCost').on('keyup', function() {
                showTotals()
            })

            $('#MaterialCost').on('change', function() {
                showTotals()
            })

            $('#LaborCost').on('keyup', function() {
                showTotals()
            })

            $('#LaborCost').on('change', function() {
                showTotals()
            })

            $('#ContingencyCost').on('keyup', function() {
                showTotals()
            })

            $('#ContingencyCost').on('change', function() {
                showTotals()
            })

            $('#TransformerCost').on('keyup', function() {
                showTotals()
            })

            $('#TransformerCost').on('change', function() {
                showTotals()
            })

            $('#TransformerAmmortized').on('change', function(e) {
                if (e.target.checked) {
                    $('#TransformerAmmortizedLabel').text('Yes').addClass('text-success')
                } else {
                    $('#TransformerAmmortizedLabel').text('No').removeClass('text-success')
                }
            })

            $('#save-btn').on('click', function() {
                Swal.fire({
                    title: 'Confirm Save?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `Cancel`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('serviceConnections.save-material-summary-amount') }}",
                            type : "GET",
                            data : {
                                MaterialCost : $('#MaterialCost').val(),
                                LaborCost : $('#LaborCost').val(),
                                ContingencyCost : $('#ContingencyCost').val(),
                                MaterialsVAT : $('#MaterialsVAT').val(),
                                TransformerCost : $('#TransformerCost').val(),
                                TransformerVAT : $('#TransformerVAT').val(),
                                BillOfMaterialsTotal : $('#GrandTotal').val(),
                                IsAmmortized : $('#TransformerAmmortized').prop('checked') ? 'Yes' : 'No',
                                ServiceConnectionId : "{{ $serviceConnection->id }}"
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Material Summary Saved!'
                                })
                                window.location.href = "{{ url('/serviceConnections') }}/{{ $serviceConnection->id }}"
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    text : 'Error saving material summary!'
                                })
                            }
                        })
                    }
                })
            })
        })

        function getMaterialsVat() {
            var materialCost = jQuery.isEmptyObject($('#MaterialCost').val()) ? 0 : parseFloat($('#MaterialCost').val())
            var laborCost = jQuery.isEmptyObject($('#LaborCost').val()) ? 0 : parseFloat($('#LaborCost').val())
            var contingencyCost = jQuery.isEmptyObject($('#ContingencyCost').val()) ? 0 : parseFloat($('#ContingencyCost').val())

            var total = materialCost + laborCost + contingencyCost

            return Math.round(((total * .12) + Number.EPSILON) * 100) / 100
        }

        function getMaterialsSubTotal() {
            var materialCost = jQuery.isEmptyObject($('#MaterialCost').val()) ? 0 : parseFloat($('#MaterialCost').val())
            var laborCost = jQuery.isEmptyObject($('#LaborCost').val()) ? 0 : parseFloat($('#LaborCost').val())
            var contingencyCost = jQuery.isEmptyObject($('#ContingencyCost').val()) ? 0 : parseFloat($('#ContingencyCost').val())
            var materialsVat = jQuery.isEmptyObject($('#MaterialsVAT').val()) ? 0 : parseFloat($('#MaterialsVAT').val())

            var total = materialCost + laborCost + contingencyCost + materialsVat

            return Math.round((total + Number.EPSILON) * 100) / 100
        }

        function getTransformerVat() {
            var transformerCost = jQuery.isEmptyObject($('#TransformerCost').val()) ? 0 : parseFloat($('#TransformerCost').val())

            return Math.round(((transformerCost * .12) + Number.EPSILON) * 100) / 100
        }

        function getTransformerSubTotal() {
            var transformerCost = jQuery.isEmptyObject($('#TransformerCost').val()) ? 0 : parseFloat($('#TransformerCost').val())
            var transformerVat = jQuery.isEmptyObject($('#TransformerVAT').val()) ? 0 : parseFloat($('#TransformerVAT').val())

            var total = transformerCost + transformerVat

            return Math.round((total + Number.EPSILON) * 100) / 100
        }

        function getGrandTotal() {
            var materialsTotal = getMaterialsSubTotal()
            var transformerTotal = getTransformerSubTotal()

            var total = materialsTotal + transformerTotal

            return Math.round((total + Number.EPSILON) * 100) / 100
        }

        function showTotals() {
            $('#MaterialsVAT').val(getMaterialsVat())
            $('#MaterialsSubTotal').val(getMaterialsSubTotal())
            $('#TransformerVAT').val(getTransformerVat())
            $('#TransformerSubTotal').val(getTransformerSubTotal())
            $('#GrandTotal').val(getGrandTotal())
        }
    </script>
@endpush