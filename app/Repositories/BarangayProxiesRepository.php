<?php

namespace App\Repositories;

use App\Models\BarangayProxies;
use App\Repositories\BaseRepository;

/**
 * Class BarangayProxiesRepository
 * @package App\Repositories
 * @version August 21, 2022, 10:35 am PST
*/

class BarangayProxiesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Barangay',
        'TownId',
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
        return BarangayProxies::class;
    }
}
