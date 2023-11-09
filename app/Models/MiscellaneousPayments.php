<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MiscellaneousPayments
 * @package App\Models
 * @version November 9, 2023, 11:45 am PST
 *
 * @property string $id
 * @property string $MiscellaneousId
 * @property string $GLCode
 * @property string $Description
 * @property number $Amount
 * @property string $Notes
 */
class MiscellaneousPayments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MiscellaneousPayments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'MiscellaneousId',
        'GLCode',
        'Description',
        'Amount',
        'Notes',
        'Unit',
        'Quantity',
        'PricePerQuantity'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'MiscellaneousId' => 'string',
        'GLCode' => 'string',
        'Description' => 'string',
        'Amount' => 'decimal:2',
        'Notes' => 'string',
        'Unit' => 'string',
        'Quantity' => 'string',
        'PricePerQuantity' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'MiscellaneousId' => 'nullable|string|max:50',
        'GLCode' => 'nullable|string|max:50',
        'Description' => 'nullable|string|max:1000',
        'Amount' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Unit' => 'nullable|string',
        'Quantity' => 'nullable|string',
        'PricePerQuantity' => 'nullable|string',
    ];

    
}
