<?php

namespace App\Repositories;

use App\Models\DisconnectionSchedules;
use App\Repositories\BaseRepository;

/**
 * Class DisconnectionSchedulesRepository
 * @package App\Repositories
 * @version July 7, 2023, 10:34 am PST
*/

class DisconnectionSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'DisconnectorName',
        'DisconnectorId',
        'Day',
        'ServicePeriodEnd',
        'Routes',
        'SequenceFrom',
        'SequenceTo',
        'Status',
        'DatetimeDownloaded',
        'PhoneModel',
        'UserId'
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
        return DisconnectionSchedules::class;
    }
}
