<?php

namespace App\Repositories;

use App\Models\Meters;
use App\Repositories\BaseRepository;

/**
 * Class MetersRepository
 * @package App\Repositories
 * @version January 17, 2023, 9:59 am PST
*/

class MetersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'RecordStatus',
        'ChangeDate',
        'MeterDigits',
        'Multiplier',
        'ChargingMode',
        'DemandType',
        'Make',
        'SerialNumber',
        'CalibrationDate',
        'MeterStatus',
        'InitialReading',
        'InitialDemand',
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
        return Meters::class;
    }
}
