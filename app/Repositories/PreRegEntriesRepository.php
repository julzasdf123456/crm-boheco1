<?php

namespace App\Repositories;

use App\Models\PreRegEntries;
use App\Repositories\BaseRepository;

/**
 * Class PreRegEntriesRepository
 * @package App\Repositories
 * @version October 4, 2022, 10:52 am PST
*/

class PreRegEntriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'Name',
        'Year',
        'RegisteredVenue',
        'DateRegistered',
        'Status',
        'RegistrationMedium',
        'ContactNumber',
        'Email',
        'Signature'
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
        return PreRegEntries::class;
    }
}
