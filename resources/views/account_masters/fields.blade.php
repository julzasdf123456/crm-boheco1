<!-- Rowguid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rowguid', 'Rowguid:') !!}
    {!! Form::text('rowguid', null, ['class' => 'form-control']) !!}
</div>

<!-- Computemode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ComputeMode', 'Computemode:') !!}
    {!! Form::text('ComputeMode', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Recordstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RecordStatus', 'Recordstatus:') !!}
    {!! Form::text('RecordStatus', null, ['class' => 'form-control','maxlength' => 1,'maxlength' => 1]) !!}
</div>

<!-- Changedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ChangeDate', 'Changedate:') !!}
    {!! Form::text('ChangeDate', null, ['class' => 'form-control','id'=>'ChangeDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ChangeDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Area Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Area', 'Area:') !!}
    {!! Form::text('Area', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Route Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Route', 'Route:') !!}
    {!! Form::text('Route', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Sequencenumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SequenceNumber', 'Sequencenumber:') !!}
    {!! Form::number('SequenceNumber', null, ['class' => 'form-control']) !!}
</div>

<!-- Uniqueid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UniqueID', 'Uniqueid:') !!}
    {!! Form::text('UniqueID', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Temporarystatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TemporaryStatus', 'Temporarystatus:') !!}
    {!! Form::text('TemporaryStatus', null, ['class' => 'form-control','maxlength' => 3,'maxlength' => 3]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Email', 'Email:') !!}
    {!! Form::email('Email', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200]) !!}
</div>

<!-- Contactnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    {!! Form::text('ContactNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Mreader Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MReader', 'Mreader:') !!}
    {!! Form::text('MReader', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Accountid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountID', 'Accountid:') !!}
    {!! Form::text('AccountID', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Item1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item1', 'Item1:') !!}
    {!! Form::text('Item1', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Depositornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DepositORNumber', 'Depositornumber:') !!}
    {!! Form::text('DepositORNumber', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Tinno Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('TINNo', 'Tinno:') !!}
    {!! Form::textarea('TINNo', null, ['class' => 'form-control']) !!}
</div>

<!-- Cust Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cust_id', 'Cust Id:') !!}
    {!! Form::text('cust_id', null, ['class' => 'form-control','maxlength' => 12,'maxlength' => 12]) !!}
</div>

<!-- Dateentry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateEntry', 'Dateentry:') !!}
    {!! Form::text('DateEntry', null, ['class' => 'form-control','id'=>'DateEntry']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateEntry').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Municipal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Municipal', 'Municipal:') !!}
    {!! Form::text('Municipal', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Barangay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Barangay', 'Barangay:') !!}
    {!! Form::text('Barangay', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Sapprovedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SApprovedBy', 'Sapprovedby:') !!}
    {!! Form::text('SApprovedBy', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Sdiscountstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SDiscountStatus', 'Sdiscountstatus:') !!}
    {!! Form::text('SDiscountStatus', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Sremarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SRemarks', 'Sremarks:') !!}
    {!! Form::text('SRemarks', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Bapaecacode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BAPAECACode', 'Bapaecacode:') !!}
    {!! Form::text('BAPAECACode', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Billdeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillDeposit', 'Billdeposit:') !!}
    {!! Form::number('BillDeposit', null, ['class' => 'form-control']) !!}
</div>

<!-- Depositdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DepositDate', 'Depositdate:') !!}
    {!! Form::text('DepositDate', null, ['class' => 'form-control','id'=>'DepositDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DepositDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Tsfrental Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TSFRental', 'Tsfrental:') !!}
    {!! Form::number('TSFRental', null, ['class' => 'form-control']) !!}
</div>

<!-- Save5 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Save5', 'Save5:') !!}
    {!! Form::text('Save5', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Sapplicationdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SApplicationDate', 'Sapplicationdate:') !!}
    {!! Form::text('SApplicationDate', null, ['class' => 'form-control','id'=>'SApplicationDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#SApplicationDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Sdiscountexpiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SDiscountExpiry', 'Sdiscountexpiry:') !!}
    {!! Form::text('SDiscountExpiry', null, ['class' => 'form-control','id'=>'SDiscountExpiry']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#SDiscountExpiry').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Sdateofbirth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SDateOfBirth', 'Sdateofbirth:') !!}
    {!! Form::text('SDateOfBirth', null, ['class' => 'form-control','id'=>'SDateOfBirth']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#SDateOfBirth').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Sdocument Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SDocument', 'Sdocument:') !!}
    {!! Form::text('SDocument', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200]) !!}
</div>

<!-- Soatag Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SOATag', 'Soatag:') !!}
    {!! Form::text('SOATag', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Oldroute Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldRoute', 'Oldroute:') !!}
    {!! Form::text('OldRoute', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Username Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserName', 'Username:') !!}
    {!! Form::text('UserName', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Deleteddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeletedDate', 'Deleteddate:') !!}
    {!! Form::text('DeletedDate', null, ['class' => 'form-control','id'=>'DeletedDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DeletedDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Grouptag Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GroupTag', 'Grouptag:') !!}
    {!! Form::text('GroupTag', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Schooltag Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SchoolTag', 'Schooltag:') !!}
    {!! Form::text('SchoolTag', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Sdwlength Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SDWLength', 'Sdwlength:') !!}
    {!! Form::number('SDWLength', null, ['class' => 'form-control']) !!}
</div>

<!-- Feeder Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Feeder', 'Feeder:') !!}
    {!! Form::text('Feeder', null, ['class' => 'form-control','maxlength' => 40,'maxlength' => 40]) !!}
</div>

<!-- Coreloss Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CoreLoss', 'Coreloss:') !!}
    {!! Form::number('CoreLoss', null, ['class' => 'form-control']) !!}
</div>

<!-- Corelosskwhupperlimit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CoreLossKWHUpperLimit', 'Corelosskwhupperlimit:') !!}
    {!! Form::number('CoreLossKWHUpperLimit', null, ['class' => 'form-control']) !!}
</div>

<!-- Readdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadDate', 'Readdate:') !!}
    {!! Form::text('ReadDate', null, ['class' => 'form-control','id'=>'ReadDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ReadDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Unreaddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UnreadDate', 'Unreaddate:') !!}
    {!! Form::text('UnreadDate', null, ['class' => 'form-control','id'=>'UnreadDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#UnreadDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Privilegetype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PrivilegeType', 'Privilegetype:') !!}
    {!! Form::text('PrivilegeType', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Installedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InstalledBy', 'Installedby:') !!}
    {!! Form::text('InstalledBy', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
</div>

<!-- Installationtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InstallationType', 'Installationtype:') !!}
    {!! Form::text('InstallationType', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Rategroup Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RateGroup', 'Rategroup:') !!}
    {!! Form::text('RateGroup', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Disconnectiondate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DisconnectionDate', 'Disconnectiondate:') !!}
    {!! Form::text('DisconnectionDate', null, ['class' => 'form-control','id'=>'DisconnectionDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DisconnectionDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Remarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Remarks', 'Remarks:') !!}
    {!! Form::text('Remarks', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Meternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    {!! Form::text('MeterNumber', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Membertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MemberType', 'Membertype:') !!}
    {!! Form::text('MemberType', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Transformer Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Transformer', 'Transformer:') !!}
    {!! Form::text('Transformer', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Pole Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Pole', 'Pole:') !!}
    {!! Form::text('Pole', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Connectiondate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConnectionDate', 'Connectiondate:') !!}
    {!! Form::text('ConnectionDate', null, ['class' => 'form-control','id'=>'ConnectionDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ConnectionDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Turnonnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TurnOnNumber', 'Turnonnumber:') !!}
    {!! Form::text('TurnOnNumber', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Cifkey Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CIFKey', 'Cifkey:') !!}
    {!! Form::text('CIFKey', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Consumername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    {!! Form::text('ConsumerName', null, ['class' => 'form-control','maxlength' => 128,'maxlength' => 128]) !!}
</div>

<!-- Consumeraddress Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerAddress', 'Consumeraddress:') !!}
    {!! Form::text('ConsumerAddress', null, ['class' => 'form-control','maxlength' => 128,'maxlength' => 128]) !!}
</div>

<!-- Consumertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    {!! Form::text('ConsumerType', null, ['class' => 'form-control','maxlength' => 15,'maxlength' => 15]) !!}
</div>

<!-- Accountstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountStatus', 'Accountstatus:') !!}
    {!! Form::text('AccountStatus', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Billingstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillingStatus', 'Billingstatus:') !!}
    {!! Form::text('BillingStatus', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>