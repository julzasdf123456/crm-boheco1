<?php

namespace App\Repositories;

use App\Models\Servers;
use App\Repositories\BaseRepository;

/**
 * Class ServersRepository
 * @package App\Repositories
 * @version July 26, 2023, 2:14 pm PST
*/

class ServersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServerName',
        'ServerIp',
        'Status'
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
        return Servers::class;
    }
}
