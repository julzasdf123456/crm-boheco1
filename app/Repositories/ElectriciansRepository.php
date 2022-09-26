<?php

namespace App\Repositories;

use App\Models\Electricians;
use App\Repositories\BaseRepository;

/**
 * Class ElectriciansRepository
 * @package App\Repositories
 * @version August 3, 2022, 2:58 pm PST
*/

class ElectriciansRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'IDNumber',
        'Name',
        'Address',
        'ContactNumber',
        'BankNumber',
        'Bank'
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
        return Electricians::class;
    }
}
