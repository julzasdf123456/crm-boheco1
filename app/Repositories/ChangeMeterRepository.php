<?php

namespace App\Repositories;

use App\Models\ChangeMeter;
use App\Repositories\BaseRepository;

/**
 * Class ChangeMeterRepository
 * @package App\Repositories
 * @version July 19, 2023, 2:09 pm PST
*/

class ChangeMeterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ChangeDate',
        'OldMeter',
        'NewMeter',
        'PullOutReading',
        'ReplaceBy',
        'Remarks'
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
        return ChangeMeter::class;
    }
}
