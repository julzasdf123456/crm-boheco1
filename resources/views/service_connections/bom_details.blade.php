@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp
<div class="row">
    @if ($billOfMaterialsSummary != null)
        @if ($billOfMaterialsSummary->IsPaid == 'Yes')
            <p class="badge bg-success" style="padding: 10px;"><i class="fas fa-check-circle ico-tab-mini"></i>Paid</p>
        @endif
        <div class="col-lg-12 col-sm-12">
            {{-- TOOLBAR --}}
            <span>
                {{-- <a href="{{ route('serviceConnections.spanning-assigning', [$serviceConnections->id]) }}" class="btn btn-sm btn-default"><i class="fas fa-network-wired"></i> Edit Spanning</a>
                <a href="{{ route('serviceConnections.bom-assigning', [$serviceConnections->id]) }}" class="btn btn-sm btn-default"><i class="fas fa-file-invoice-dollar"></i> Edit Materials</a>
                <a href="{{ route('serviceConnections.forward-to-transformer-assigning', [$serviceConnections->id]) }}" class="btn btn-sm btn-default"><i class="fas fa-car-battery"></i> Edit Transformer</a>
                <a href="{{ route('serviceConnections.pole-assigning', [$serviceConnections->id]) }}" class="btn btn-sm btn-default"><i class="fas fa-cross"></i> Edit Pole</a> --}}
                
                @if (Auth::user()->hasAnyPermission(['Super Admin', 'sc powerload update']))
                    <a href="{{ route('serviceConnections.quotation-summary', [$serviceConnections->id]) }}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i> Edit Bill of Materials Summary</a>
                @endif
                
                @if (Auth::user()->hasAnyPermission(['Super Admin', 'sc transformer ammortization']))
                    @if ($totalTransactions != null && ($totalTransactions->TransformerAmmortizationTerms != null && $totalTransactions->TransformerAmmortizationTerms > 0))
                        <a href="{{ route('serviceConnections.transformer-ammortizations-view', [$serviceConnections->id]) }}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i> Edit Transformer Ammortization</a>
                    @else
                        <a href="{{ route('serviceConnections.transformer-ammortizations-view', [$serviceConnections->id]) }}" class="btn btn-xs btn-primary"><i class="fas fa-plus-circle"></i> Create Transformer Ammortization</a>
                    @endif
                @endif
                
                @if ($totalTransactions != null && ($totalTransactions->TransformerAmmortizationTerms != null && $totalTransactions->TransformerAmmortizationTerms > 0))
                    <a href="{{ route('serviceConnections.print-transformer-ammortization', [$serviceConnections->id]) }}" class="btn btn-xs btn-warning"><i class="fas fa-print"></i> Print Transformer Ammortization</a>
                @endif
            </span>
        </div>

        <div class="col-lg-12 col-md-12" style="margin-top: 10px;">
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#materials" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Material Summary</a>
                </li>
                @if ($totalTransactions != null && ($totalTransactions->TransformerAmmortizationTerms != null && $totalTransactions->TransformerAmmortizationTerms > 0))
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#transformer_ammortization" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Transformer Ammortization Sched</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#construction" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Construction Summary</a>
                </li>
            </ul>

            <div class="tab-content" id="custom-content-below-tabContent">
                {{-- Bills of Materials --}}
                <div class="tab-pane fade active show" id="materials" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="row">
                        {{-- <div class="col-md-12 col-lg-12">
                            <div class="header">
                                <p class="text-center p-0" style="margin: 0;"><strong>{{ env("APP_COMPANY") }}</strong></p>
                                <p class="text-center p-0">{{ env("APP_ADDRESS") }}</p>

                                <h4 class="text-center p-0">Bill of Materials</h4>
                            </div>                
                        </div> --}}
        
                        <div class="col-lg-12 col-md-12">
                            {{-- <div class="row invoice-info">
                                <div class="col-sm-12 invoice-col">
                                    <address>
                                        Date : <strong>{{ date('F d, Y') }}</strong><br>
                                        Project Name: <strong>{{ $serviceConnections->ServiceAccountName }}</strong><br>
                                        Project Address: <strong>{{ ServiceConnections::getAddress($serviceConnections) }}</strong><br>
                                    </address>
                                </div>
                            </div> --}}
        
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
                                            <td style="border-top: 1px solid #333333;" class="text-right">{{ $totalTransactions!= null && $totalTransactions->MaterialCost != null ? number_format($totalTransactions->MaterialCost, 2) : 0 }}</td>  
                                        </tr>  
                                        <tr>
                                            <td></td>
                                            <td class="text-right">Labor Cost</td>  
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $totalTransactions!= null && $totalTransactions->LaborCost ? number_format($totalTransactions->LaborCost, 2) : 0 }}</td>  
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="text-right">Contengency, Engineering & Handling, Etc.</td>  
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $totalTransactions!= null && $totalTransactions->ContingencyCost ? number_format($totalTransactions->ContingencyCost, 2) : 0 }}</td> 
                                        </tr>  
                                        <tr>
                                            <td></td>
                                            <td class="text-right">EVAT (12%)</td>  
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $totalTransactions!= null && $totalTransactions->MaterialsVAT ? number_format($totalTransactions->MaterialsVAT, 2) : 0 }}</td> 
                                        </tr>
                                        <tr>
                                            @php
                                                $materialsTotal = $totalTransactions != null ? ($totalTransactions->MaterialCost + $totalTransactions->LaborCost + $totalTransactions->ContingencyCost + $totalTransactions->MaterialsVAT) : 0;
                                            @endphp
                                            <th></th>
                                            <th class="text-right text-muted">Materials Total (Installation Fee)</th>  
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">{{ number_format($materialsTotal, 2) }}</th> 
                                        </tr>   
                                        <tr>
                                            <td></td>
                                            <td class="text-right">Transformer Cost</td>  
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $totalTransactions!= null && $totalTransactions->TransformerCost ? number_format($totalTransactions->TransformerCost, 2) : 0 }}</td>  
                                        </tr>  
                                        <tr>
                                            <td></td>
                                            <td class="text-right">EVAT (12%)</td>  
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $totalTransactions!= null && $totalTransactions->TransformerVAT ? number_format($totalTransactions->TransformerVAT, 2) : 0 }}</td> 
                                        </tr> 
                                        <tr>
                                            <th></th>
                                            <th class="text-right">Overall Total</th>  
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">{{ $totalTransactions!= null && $totalTransactions->BillOfMaterialsTotal ? number_format($totalTransactions->BillOfMaterialsTotal, 2) : 0 }}</th> 
                                        </tr>                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Transformer Ammortization Schedule --}}
                @if ($totalTransactions != null && ($totalTransactions->TransformerAmmortizationTerms != null && $totalTransactions->TransformerAmmortizationTerms > 0))
                    <div class="tab-pane fade show" id="transformer_ammortization" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        @include('service_connections.tab_transformer_ammortization')
                    </div>
                @endif
                {{-- Construction Assets --}}
                <div class="tab-pane fade show" id="construction" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="container">
                        {{-- <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="header">
                                    <p class="text-center p-0" style="margin: 0;"><strong>{{ env("APP_COMPANY") }}</strong></p>
                                    <p class="text-center p-0">{{ env("APP_ADDRESS") }}</p>
        
                                    <h4 class="text-center p-0">Bill of Materials</h4>
                                </div>                  
                            </div>
                        </div> --}}
                        <div class="col-lg-12">
                            {{-- <div class="row invoice-info">
                                <div class="col-sm-12 invoice-col">
                                    <address>
                                        Date : <strong>{{ date('F d, Y') }}</strong><br>
                                        Project Name: <strong>{{ $serviceConnections->ServiceAccountName }}</strong><br>
                                        Project Address: <strong>{{ ServiceConnections::getAddress($serviceConnections) }}</strong><br>
                                    </address>
                                </div>
                            </div> --}}

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
        </div>
    @endif   
</div>