<?php

namespace App\Repositories;

use App\Models\AdditionalConsumptions;
use App\Repositories\BaseRepository;

/**
 * Class AdditionalConsumptionsRepository
 * @package App\Repositories
 * @version October 12, 2023, 9:12 am PST
*/

class AdditionalConsumptionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'AdditionalKWH',
        'AdditionalKW',
        'Remarks'
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
        return AdditionalConsumptions::class;
    }
}
