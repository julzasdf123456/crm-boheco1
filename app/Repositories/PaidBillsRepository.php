<?php

namespace App\Repositories;

use App\Models\PaidBills;
use App\Repositories\BaseRepository;

/**
 * Class PaidBillsRepository
 * @package App\Repositories
 * @version February 7, 2023, 10:38 am PST
*/

class PaidBillsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'BillNumber',
        'ServicePeriodEnd',
        'Power',
        'Meter',
        'PR',
        'Others',
        'NetAmount',
        'PaymentType',
        'ORNumber',
        'Teller',
        'DCRNumber',
        'PostingDate',
        'PostingSequence',
        'PromptPayment',
        'Surcharge',
        'SLAdjustment',
        'OtherDeduction',
        'ORDate',
        'MDRefund',
        'Form2306',
        'Form2307',
        'Amount2306',
        'Amount2307',
        'ServiceFee',
        'Others1',
        'Others2'
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
        return PaidBills::class;
    }
}
