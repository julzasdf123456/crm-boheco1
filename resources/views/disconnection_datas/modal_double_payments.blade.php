{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-double-payments" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>Double Payments for this Collection</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">                
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover table-sm table-bordered">
                            <thead>
                                <th>Acct. No.</th>
                                <th>Consumer Name</th>
                                <th>Address</th>
                                <th>Billing Mo.</th>
                                <th>Amnt.</th>
                                <th>Teller</th>
                                <th>Date/Time Posted</th>
                            </thead>
                            <tbody>
                                @foreach ($doublePayments as $item)
                                    <tr>
                                        <td>{{ $item->AccountNumber }}</td>
                                        <td>{{ $item->ConsumerName }}</td>
                                        <td>{{ $item->ConsumerAddress }}</td>
                                        <td>{{ date('M Y', strtotime($item->ServicePeriodEnd)) }}</td>
                                        <td>{{ number_format($item->AmountPaid, 2) }}</td>
                                        <td>{{ $item->Teller }}</td>
                                        <td>{{ date('M d, Y, h:i A', strtotime($item->DatePaid)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('disconnectionDatas.print-double-payments', [$name, $date]) }}" class="btn btn-primary" id="print"><i class="fas fa-print ico-tab-mini"></i>Print</a>
            </div>
        </div>
    </div>
</div>