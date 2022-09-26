<?php

namespace App\Repositories;

use App\Models\BillDeposits;
use App\Repositories\BaseRepository;

/**
 * Class BillDepositsRepository
 * @package App\Repositories
 * @version August 4, 2022, 1:33 pm PST
*/

class BillDepositsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Load',
        'PowerFactor',
        'DemandFactor',
        'Hours',
        'AverageRate',
        'AverageTransmission',
        'AverageDemand',
        'BillDepositAmount'
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
        return BillDeposits::class;
    }
}
