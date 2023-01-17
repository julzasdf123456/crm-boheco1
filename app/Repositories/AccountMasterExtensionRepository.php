<?php

namespace App\Repositories;

use App\Models\AccountMasterExtension;
use App\Repositories\BaseRepository;

/**
 * Class AccountMasterExtensionRepository
 * @package App\Repositories
 * @version January 17, 2023, 9:56 am PST
*/

class AccountMasterExtensionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'PoleCode',
        'ServiceVoltage',
        'Phase',
        'PF',
        'Phasing',
        'SubstationID',
        'SDWType',
        'Item1',
        'Item2',
        'Item3',
        'Item4',
        'Item5'
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
        return AccountMasterExtension::class;
    }
}
