<div class="table-responsive">
    <table class="table" id="accountMasters-table">
        <thead>
        <tr>
            <th>Rowguid</th>
        <th>Computemode</th>
        <th>Recordstatus</th>
        <th>Changedate</th>
        <th>Area</th>
        <th>Route</th>
        <th>Sequencenumber</th>
        <th>Uniqueid</th>
        <th>Temporarystatus</th>
        <th>Email</th>
        <th>Contactnumber</th>
        <th>Mreader</th>
        <th>Accountid</th>
        <th>Item1</th>
        <th>Depositornumber</th>
        <th>Tinno</th>
        <th>Cust Id</th>
        <th>Dateentry</th>
        <th>Municipal</th>
        <th>Barangay</th>
        <th>Sapprovedby</th>
        <th>Sdiscountstatus</th>
        <th>Sremarks</th>
        <th>Bapaecacode</th>
        <th>Billdeposit</th>
        <th>Depositdate</th>
        <th>Tsfrental</th>
        <th>Save5</th>
        <th>Sapplicationdate</th>
        <th>Sdiscountexpiry</th>
        <th>Sdateofbirth</th>
        <th>Sdocument</th>
        <th>Soatag</th>
        <th>Oldroute</th>
        <th>Username</th>
        <th>Deleteddate</th>
        <th>Grouptag</th>
        <th>Schooltag</th>
        <th>Sdwlength</th>
        <th>Feeder</th>
        <th>Coreloss</th>
        <th>Corelosskwhupperlimit</th>
        <th>Readdate</th>
        <th>Unreaddate</th>
        <th>Privilegetype</th>
        <th>Installedby</th>
        <th>Installationtype</th>
        <th>Rategroup</th>
        <th>Disconnectiondate</th>
        <th>Remarks</th>
        <th>Meternumber</th>
        <th>Membertype</th>
        <th>Transformer</th>
        <th>Pole</th>
        <th>Connectiondate</th>
        <th>Turnonnumber</th>
        <th>Cifkey</th>
        <th>Consumername</th>
        <th>Consumeraddress</th>
        <th>Consumertype</th>
        <th>Accountstatus</th>
        <th>Billingstatus</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accountMasters as $accountMaster)
            <tr>
                <td>{{ $accountMaster->rowguid }}</td>
            <td>{{ $accountMaster->ComputeMode }}</td>
            <td>{{ $accountMaster->RecordStatus }}</td>
            <td>{{ $accountMaster->ChangeDate }}</td>
            <td>{{ $accountMaster->Area }}</td>
            <td>{{ $accountMaster->Route }}</td>
            <td>{{ $accountMaster->SequenceNumber }}</td>
            <td>{{ $accountMaster->UniqueID }}</td>
            <td>{{ $accountMaster->TemporaryStatus }}</td>
            <td>{{ $accountMaster->Email }}</td>
            <td>{{ $accountMaster->ContactNumber }}</td>
            <td>{{ $accountMaster->MReader }}</td>
            <td>{{ $accountMaster->AccountID }}</td>
            <td>{{ $accountMaster->Item1 }}</td>
            <td>{{ $accountMaster->DepositORNumber }}</td>
            <td>{{ $accountMaster->TINNo }}</td>
            <td>{{ $accountMaster->cust_id }}</td>
            <td>{{ $accountMaster->DateEntry }}</td>
            <td>{{ $accountMaster->Municipal }}</td>
            <td>{{ $accountMaster->Barangay }}</td>
            <td>{{ $accountMaster->SApprovedBy }}</td>
            <td>{{ $accountMaster->SDiscountStatus }}</td>
            <td>{{ $accountMaster->SRemarks }}</td>
            <td>{{ $accountMaster->BAPAECACode }}</td>
            <td>{{ $accountMaster->BillDeposit }}</td>
            <td>{{ $accountMaster->DepositDate }}</td>
            <td>{{ $accountMaster->TSFRental }}</td>
            <td>{{ $accountMaster->Save5 }}</td>
            <td>{{ $accountMaster->SApplicationDate }}</td>
            <td>{{ $accountMaster->SDiscountExpiry }}</td>
            <td>{{ $accountMaster->SDateOfBirth }}</td>
            <td>{{ $accountMaster->SDocument }}</td>
            <td>{{ $accountMaster->SOATag }}</td>
            <td>{{ $accountMaster->OldRoute }}</td>
            <td>{{ $accountMaster->UserName }}</td>
            <td>{{ $accountMaster->DeletedDate }}</td>
            <td>{{ $accountMaster->GroupTag }}</td>
            <td>{{ $accountMaster->SchoolTag }}</td>
            <td>{{ $accountMaster->SDWLength }}</td>
            <td>{{ $accountMaster->Feeder }}</td>
            <td>{{ $accountMaster->CoreLoss }}</td>
            <td>{{ $accountMaster->CoreLossKWHUpperLimit }}</td>
            <td>{{ $accountMaster->ReadDate }}</td>
            <td>{{ $accountMaster->UnreadDate }}</td>
            <td>{{ $accountMaster->PrivilegeType }}</td>
            <td>{{ $accountMaster->InstalledBy }}</td>
            <td>{{ $accountMaster->InstallationType }}</td>
            <td>{{ $accountMaster->RateGroup }}</td>
            <td>{{ $accountMaster->DisconnectionDate }}</td>
            <td>{{ $accountMaster->Remarks }}</td>
            <td>{{ $accountMaster->MeterNumber }}</td>
            <td>{{ $accountMaster->MemberType }}</td>
            <td>{{ $accountMaster->Transformer }}</td>
            <td>{{ $accountMaster->Pole }}</td>
            <td>{{ $accountMaster->ConnectionDate }}</td>
            <td>{{ $accountMaster->TurnOnNumber }}</td>
            <td>{{ $accountMaster->CIFKey }}</td>
            <td>{{ $accountMaster->ConsumerName }}</td>
            <td>{{ $accountMaster->ConsumerAddress }}</td>
            <td>{{ $accountMaster->ConsumerType }}</td>
            <td>{{ $accountMaster->AccountStatus }}</td>
            <td>{{ $accountMaster->BillingStatus }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['accountMasters.destroy', $accountMaster->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('accountMasters.show', [$accountMaster->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('accountMasters.edit', [$accountMaster->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
