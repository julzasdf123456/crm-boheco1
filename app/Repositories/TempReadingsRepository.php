<?php

namespace App\Repositories;

use App\Models\TempReadings;
use App\Repositories\BaseRepository;

/**
 * Class TempReadingsRepository
 * @package App\Repositories
 * @version August 29, 2023, 10:20 am PST
*/

class TempReadingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServicePeriodEnd',
        'AccountNumber',
        'Route',
        'SequenceNumber',
        'ConsumerName',
        'ConsumerAddress',
        'MeterNumber',
        'PreviousReading2',
        'PreviousReading1',
        'PreviousReading',
        'ReadingDate',
        'ReadBy',
        'PowerReadings',
        'DemandReadings',
        'FieldFindings',
        'MissCodes',
        'Remarks',
        'UpdateStatus',
        'ConsumerType',
        'AccountStatus',
        'ShortAccountNumber',
        'Multiplier',
        'MeterDigits',
        'Coreloss',
        'CorelossKWHLimit',
        'AdditionalKWH',
        'TSFRental',
        'SchoolTag'
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
        return TempReadings::class;
    }
}
