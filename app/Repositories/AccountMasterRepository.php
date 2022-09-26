<?php

namespace App\Repositories;

use App\Models\AccountMaster;
use App\Repositories\BaseRepository;

/**
 * Class AccountMasterRepository
 * @package App\Repositories
 * @version August 21, 2022, 9:57 am PST
*/

class AccountMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rowguid',
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AccountMaster::class;
    }
}
