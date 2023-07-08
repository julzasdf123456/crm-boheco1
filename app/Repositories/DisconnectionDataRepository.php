<?php

namespace App\Repositories;

use App\Models\DisconnectionData;
use App\Repositories\BaseRepository;

/**
 * Class DisconnectionDataRepository
 * @package App\Repositories
 * @version July 7, 2023, 10:36 am PST
*/

class DisconnectionDataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ScheduleId',
        'DisconnectorName',
        'UserId',
        'AccountNumber',
        'ServicePeriodEnd',
        'AccountCoordinates',
        'Latitude',
        'Longitude',
        'Status',
        'Notes',
        'NetAmount',
        'Surcharge',
        'ServiceFee',
        'Others',
        'PaidAmount',
        'ORNumber',
        'ORDate'
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
        return DisconnectionData::class;
    }
}
