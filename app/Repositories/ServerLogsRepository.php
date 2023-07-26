<?php

namespace App\Repositories;

use App\Models\ServerLogs;
use App\Repositories\BaseRepository;

/**
 * Class ServerLogsRepository
 * @package App\Repositories
 * @version July 26, 2023, 2:17 pm PST
*/

class ServerLogsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServerIpSource',
        'ServerNameSource',
        'Details'
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
        return ServerLogs::class;
    }
}
