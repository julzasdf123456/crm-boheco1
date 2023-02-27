<?php

namespace App\Repositories;

use App\Models\SMSNotifications;
use App\Repositories\BaseRepository;

/**
 * Class SMSNotificationsRepository
 * @package App\Repositories
 * @version February 27, 2023, 11:59 am PST
*/

class SMSNotificationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Source',
        'SourceId',
        'ContactNumber',
        'Message',
        'Status',
        'AIFacilitator',
        'Notes'
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
        return SMSNotifications::class;
    }
}
