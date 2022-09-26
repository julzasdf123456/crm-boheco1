<?php

namespace App\Repositories;

use App\Models\UnbundledRatesExtension;
use App\Repositories\BaseRepository;

/**
 * Class UnbundledRatesExtensionRepository
 * @package App\Repositories
 * @version August 4, 2022, 8:28 am PST
*/

class UnbundledRatesExtensionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rowguid',
        'ServicePeriodEnd',
        'LCPerCustomer',
        'Item2',
        'Item3',
        'Item4',
        'Item11',
        'Item12',
        'Item13',
        'Item5',
        'Item6',
        'Item7',
        'Item8',
        'Item9',
        'Item10'
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
        return UnbundledRatesExtension::class;
    }
}
