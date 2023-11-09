<?php

namespace App\Repositories;

use App\Models\MiscellaneousPayments;
use App\Repositories\BaseRepository;

/**
 * Class MiscellaneousPaymentsRepository
 * @package App\Repositories
 * @version November 9, 2023, 11:45 am PST
*/

class MiscellaneousPaymentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'MiscellaneousId',
        'GLCode',
        'Description',
        'Amount',
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
        return MiscellaneousPayments::class;
    }
}
