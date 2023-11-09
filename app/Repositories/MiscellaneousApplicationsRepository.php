<?php

namespace App\Repositories;

use App\Models\MiscellaneousApplications;
use App\Repositories\BaseRepository;

/**
 * Class MiscellaneousApplicationsRepository
 * @package App\Repositories
 * @version November 9, 2023, 11:43 am PST
*/

class MiscellaneousApplicationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ConsumerName',
        'Town',
        'Barangay',
        'Sitio',
        'Application',
        'Notes',
        'Status',
        'ServiceDropLength',
        'TransformerLoad',
        'TicketId',
        'ServiceConnectionId',
        'AccountNumber',
        'UserId',
        'TotalAmount',
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
        return MiscellaneousApplications::class;
    }
}
