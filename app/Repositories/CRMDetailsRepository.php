<?php

namespace App\Repositories;

use App\Models\CRMDetails;
use App\Repositories\BaseRepository;

/**
 * Class CRMDetailsRepository
 * @package App\Repositories
 * @version June 8, 2023, 11:24 am PST
*/

class CRMDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ReferenceNo',
        'Particular',
        'GLCode',
        'SubTotal',
        'VAT',
        'Total'
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
        return CRMDetails::class;
    }
}
