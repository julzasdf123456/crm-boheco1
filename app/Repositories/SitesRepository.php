<?php

namespace App\Repositories;

use App\Models\Sites;
use App\Repositories\BaseRepository;

/**
 * Class SitesRepository
 * @package App\Repositories
 * @version August 29, 2023, 2:14 pm PST
*/

class SitesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'URL',
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
        return Sites::class;
    }
}
