<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CRMQueue
 * @package App\Models
 * @version June 8, 2023, 11:25 am PST
 *
 * @property \Illuminate\Database\Eloquent\Collection $cRMDetails
 * @property string $ConsumerName
 * @property string $ConsumerAddress
 * @property string $TransactionPurpose
 * @property string $Source
 * @property string $SourceId
 * @property number $SubTotal
 * @property number $VAT
 * @property number $Total
 */
class CRMQueue extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRMQueue';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvaccounting";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ConsumerName' => 'string',
        'ConsumerAddress' => 'string',
        'TransactionPurpose' => 'string',
        'Source' => 'string',
        'SourceId' => 'string',
        'SubTotal' => 'decimal:2',
        'VAT' => 'decimal:2',
        'Total' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ConsumerName' => 'nullable|string|max:100',
        'ConsumerAddress' => 'nullable|string|max:200',
        'TransactionPurpose' => 'nullable|string|max:50',
        'Source' => 'nullable|string|max:50',
        'SourceId' => 'nullable|string|max:30',
        'SubTotal' => 'nullable|numeric',
        'VAT' => 'nullable|numeric',
        'Total' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cRMDetails()
    {
        return $this->hasMany(\App\Models\CRMDetail::class, 'ReferenceNo');
    }
}
