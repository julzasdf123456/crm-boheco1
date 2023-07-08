<?php

namespace App\Repositories;

use App\Models\DisconnectionRoutes;
use App\Repositories\BaseRepository;

/**
 * Class DisconnectionRoutesRepository
 * @package App\Repositories
 * @version July 7, 2023, 2:04 pm PST
*/

class DisconnectionRoutesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ScheduleId',
        'Route',
        'SequenceFrom',
        'SequenceTo',
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
        return DisconnectionRoutes::class;
    }
}
