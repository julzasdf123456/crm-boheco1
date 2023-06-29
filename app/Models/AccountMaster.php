<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AccountMaster
 * @package App\Models
 * @version August 21, 2022, 9:57 am PST
 *
 * @property string $rowguid
 * @property string $ComputeMode
 * @property string $RecordStatus
 * @property string|\Carbon\Carbon $ChangeDate
 * @property string $Area
 * @property string $Route
 * @property integer $SequenceNumber
 * @property string $UniqueID
 * @property string $TemporaryStatus
 * @property string $Email
 * @property string $ContactNumber
 * @property string $MReader
 * @property string $AccountID
 * @property string $Item1
 * @property string $DepositORNumber
 * @property string $TINNo
 * @property string $cust_id
 * @property string|\Carbon\Carbon $DateEntry
 * @property string $Municipal
 * @property string $Barangay
 * @property string $SApprovedBy
 * @property string $SDiscountStatus
 * @property string $SRemarks
 * @property string $BAPAECACode
 * @property integer $BillDeposit
 * @property string|\Carbon\Carbon $DepositDate
 * @property integer $TSFRental
 * @property string $Save5
 * @property string|\Carbon\Carbon $SApplicationDate
 * @property string|\Carbon\Carbon $SDiscountExpiry
 * @property string|\Carbon\Carbon $SDateOfBirth
 * @property string $SDocument
 * @property string $SOATag
 * @property string $OldRoute
 * @property string $UserName
 * @property string|\Carbon\Carbon $DeletedDate
 * @property string $GroupTag
 * @property string $SchoolTag
 * @property number $SDWLength
 * @property string $Feeder
 * @property number $CoreLoss
 * @property number $CoreLossKWHUpperLimit
 * @property string|\Carbon\Carbon $ReadDate
 * @property string|\Carbon\Carbon $UnreadDate
 * @property string $PrivilegeType
 * @property string $InstalledBy
 * @property string $InstallationType
 * @property string $RateGroup
 * @property string|\Carbon\Carbon $DisconnectionDate
 * @property string $Remarks
 * @property string $MeterNumber
 * @property string $MemberType
 * @property string $Transformer
 * @property string $Pole
 * @property string|\Carbon\Carbon $ConnectionDate
 * @property string $TurnOnNumber
 * @property string $CIFKey
 * @property string $ConsumerName
 * @property string $ConsumerAddress
 * @property string $ConsumerType
 * @property string $AccountStatus
 * @property string $BillingStatus
 */
class AccountMaster extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'AccountMaster';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'AccountNumber';

    public $incrementing = false;

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public $connection = "sqlsrvbilling";

    public $fillable = [
        'AccountNumber',
        'ComputeMode',
        'RecordStatus',
        'ChangeDate',
        'Area',
        'Route',
        'SequenceNumber',
        'UniqueID',
        'TemporaryStatus',
        'Email',
        'ContactNumber',
        'MReader',
        'AccountID',
        'Item1',
        'DepositORNumber',
        'TINNo',
        'cust_id',
        'DateEntry',
        'Municipal',
        'Barangay',
        'SApprovedBy',
        'SDiscountStatus',
        'SRemarks',
        'BAPAECACode',
        'BillDeposit',
        'DepositDate',
        'TSFRental',
        'Save5',
        'SApplicationDate',
        'SDiscountExpiry',
        'SDateOfBirth',
        'SDocument',
        'SOATag',
        'OldRoute',
        'UserName',
        'DeletedDate',
        'GroupTag',
        'SchoolTag',
        'SDWLength',
        'Feeder',
        'CoreLoss',
        'CoreLossKWHUpperLimit',
        'ReadDate',
        'UnreadDate',
        'PrivilegeType',
        'InstalledBy',
        'InstallationType',
        'RateGroup',
        'DisconnectionDate',
        'Remarks',
        'MeterNumber',
        'MemberType',
        'Transformer',
        'Pole',
        'ConnectionDate',
        'TurnOnNumber',
        'CIFKey',
        'ConsumerName',
        'ConsumerAddress',
        'ConsumerType',
        'AccountStatus',
        'BillingStatus'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rowguid' => 'string',
        'ComputeMode' => 'string',
        'AccountNumber' => 'string',
        'RecordStatus' => 'string',
        'ChangeDate' => 'datetime',
        'Area' => 'string',
        'Route' => 'string',
        'SequenceNumber' => 'integer',
        'UniqueID' => 'string',
        'TemporaryStatus' => 'string',
        'Email' => 'string',
        'ContactNumber' => 'string',
        'MReader' => 'string',
        'AccountID' => 'string',
        'Item1' => 'string',
        'DepositORNumber' => 'string',
        'TINNo' => 'string',
        'cust_id' => 'string',
        'DateEntry' => 'datetime',
        'Municipal' => 'string',
        'Barangay' => 'string',
        'SApprovedBy' => 'string',
        'SDiscountStatus' => 'string',
        'SRemarks' => 'string',
        'BAPAECACode' => 'string',
        'BillDeposit' => 'integer',
        'DepositDate' => 'datetime',
        'TSFRental' => 'integer',
        'Save5' => 'string',
        'SApplicationDate' => 'datetime',
        'SDiscountExpiry' => 'datetime',
        'SDateOfBirth' => 'datetime',
        'SDocument' => 'string',
        'SOATag' => 'string',
        'OldRoute' => 'string',
        'UserName' => 'string',
        'DeletedDate' => 'datetime',
        'GroupTag' => 'string',
        'SchoolTag' => 'string',
        'SDWLength' => 'decimal:0',
        'Feeder' => 'string',
        'CoreLoss' => 'float',
        'CoreLossKWHUpperLimit' => 'float',
        'ReadDate' => 'datetime',
        'UnreadDate' => 'datetime',
        'PrivilegeType' => 'string',
        'InstalledBy' => 'string',
        'InstallationType' => 'string',
        'RateGroup' => 'string',
        'DisconnectionDate' => 'datetime',
        'Remarks' => 'string',
        'MeterNumber' => 'string',
        'MemberType' => 'string',
        'Transformer' => 'string',
        'Pole' => 'string',
        'ConnectionDate' => 'datetime',
        'TurnOnNumber' => 'string',
        'CIFKey' => 'string',
        'ConsumerName' => 'string',
        'ConsumerAddress' => 'string',
        'ConsumerType' => 'string',
        'AccountStatus' => 'string',
        'BillingStatus' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ComputeMode' => 'nullable|string|max:20',
        'RecordStatus' => 'nullable|string|max:1',
        'ChangeDate' => 'nullable',
        'Area' => 'nullable|string|max:10',
        'Route' => 'nullable|string|max:10',
        'SequenceNumber' => 'nullable|integer',
        'UniqueID' => 'nullable|string|max:50',
        'TemporaryStatus' => 'nullable|string|max:3',
        'Email' => 'nullable|string|max:200',
        'ContactNumber' => 'nullable|string|max:50',
        'MReader' => 'nullable|string|max:20',
        'AccountID' => 'nullable|string|max:20',
        'Item1' => 'nullable|string|max:100',
        'DepositORNumber' => 'nullable|string|max:50',
        'TINNo' => 'nullable|string',
        'cust_id' => 'nullable|string|max:12',
        'DateEntry' => 'nullable',
        'Municipal' => 'nullable|string|max:50',
        'Barangay' => 'nullable|string|max:50',
        'SApprovedBy' => 'nullable|string|max:100',
        'SDiscountStatus' => 'nullable|string|max:50',
        'SRemarks' => 'nullable|string|max:100',
        'BAPAECACode' => 'nullable|string|max:50',
        'BillDeposit' => 'nullable|integer',
        'DepositDate' => 'nullable',
        'TSFRental' => 'nullable|integer',
        'Save5' => 'nullable|string|max:20',
        'SApplicationDate' => 'nullable',
        'SDiscountExpiry' => 'nullable',
        'SDateOfBirth' => 'nullable',
        'SDocument' => 'nullable|string|max:200',
        'SOATag' => 'nullable|string|max:20',
        'OldRoute' => 'nullable|string|max:10',
        'UserName' => 'nullable|string|max:50',
        'DeletedDate' => 'nullable',
        'GroupTag' => 'nullable|string|max:20',
        'SchoolTag' => 'nullable|string|max:20',
        'SDWLength' => 'nullable|numeric',
        'Feeder' => 'nullable|string|max:40',
        'CoreLoss' => 'nullable|numeric',
        'CoreLossKWHUpperLimit' => 'nullable|numeric',
        'ReadDate' => 'nullable',
        'UnreadDate' => 'nullable',
        'PrivilegeType' => 'nullable|string|max:10',
        'InstalledBy' => 'nullable|string|max:30',
        'InstallationType' => 'nullable|string|max:10',
        'RateGroup' => 'nullable|string|max:10',
        'DisconnectionDate' => 'nullable',
        'Remarks' => 'nullable|string|max:50',
        'MeterNumber' => 'nullable|string|max:20',
        'MemberType' => 'nullable|string|max:20',
        'Transformer' => 'nullable|string|max:20',
        'Pole' => 'nullable|string|max:20',
        'ConnectionDate' => 'nullable',
        'TurnOnNumber' => 'nullable|string|max:10',
        'CIFKey' => 'nullable|string|max:10',
        'ConsumerName' => 'nullable|string|max:128',
        'ConsumerAddress' => 'nullable|string|max:128',
        'ConsumerType' => 'nullable|string|max:15',
        'AccountStatus' => 'nullable|string|max:20',
        'BillingStatus' => 'nullable|string|max:20'
    ];

    public static function getTypeByAlias($alias) {
        if ($alias=='CL' || $alias=='CS') {
            return 'C';
        } elseif ($alias=='RM' || $alias=='RI') {
            return 'R';
        } else {
            return $alias;
        }
    }

    public static function getInstallationType($type) {
        if ($type=='Non-Concrete') {
            return 'conc';
        } else {
            return 'non-conc';
        }
    }

    public static function PNColors($value) {
        $value = floatval($value);

        if ($value >= 0) {
            return 'text-success';
        } else {
            return 'text-danger';
        }
    }

    public static function PNIcons($value) {
        $value = floatval($value);

        if ($value >= 0) {
            return 'fa-caret-up';
        } else {
            return 'fa-caret-down';
        }
    }

    public static function getGroupTag($acctType) {
        if ($acctType=='1627280880118') {
            return 'M';
        } elseif ($acctType=='1627281051251') {
            return 'L';
        } elseif ($acctType=='1659574401785') {
            return 'S';
        } else {
            return null;
        }
    }
}
