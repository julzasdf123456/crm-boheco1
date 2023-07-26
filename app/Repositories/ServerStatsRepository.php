<?php

namespace App\Repositories;

use App\Models\ServerStats;
use App\Repositories\BaseRepository;

/**
 * Class ServerStatsRepository
 * @package App\Repositories
 * @version July 26, 2023, 2:16 pm PST
*/

class ServerStatsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServerId',
        'CpuPercentage',
        'MemoryPercentage',
        'DiskPercentage',
        'TotalMemory'
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
        return ServerStats::class;
    }
}
