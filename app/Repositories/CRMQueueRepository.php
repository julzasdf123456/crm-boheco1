<?php

namespace App\Repositories;

use App\Models\CRMQueue;
use App\Repositories\BaseRepository;

/**
 * Class CRMQueueRepository
 * @package App\Repositories
 * @version June 8, 2023, 11:25 am PST
*/

class CRMQueueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ConsumerName',
        'ConsumerAddress',
        'TransactionPurpose',
        'Source',
        'SourceId',
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
        return CRMQueue::class;
    }
}
