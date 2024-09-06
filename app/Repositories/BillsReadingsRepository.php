<?php

namespace App\Repositories;

use App\Models\BillsReadings;
use App\Repositories\BaseRepository;

/**
 * Class BillsReadingsRepository
 * @package App\Repositories
 * @version September 6, 2024, 8:20 am PST
*/

class BillsReadingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ReadingDate',
        'ReadBy',
        'PowerReadings',
        'DemandReadings',
        'FieldFindings',
        'MissCodes',
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
        return BillsReadings::class;
    }
}
