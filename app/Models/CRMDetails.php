<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CRMDetails
 * @package App\Models
 * @version June 8, 2023, 11:24 am PST
 *
 * @property \App\Models\CRMQueue $referenceno
 * @property string $ReferenceNo
 * @property string $Particular
 * @property string $GLCode
 * @property number $SubTotal
 * @property number $VAT
 * @property number $Total
 */
class CRMDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRMDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvaccounting";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ReferenceNo',
        'Particular',
        'GLCode',
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
        'ReferenceNo' => 'string',
        'Particular' => 'string',
        'GLCode' => 'string',
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
        'ReferenceNo' => 'required|string|max:30',
        'Particular' => 'nullable|string|max:50',
        'GLCode' => 'nullable|string|max:10',
        'SubTotal' => 'nullable|numeric',
        'VAT' => 'nullable|numeric',
        'Total' => 'nullable|numeric'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function referenceno()
    {
        return $this->belongsTo(\App\Models\CRMQueue::class, 'ReferenceNo');
    }
}
