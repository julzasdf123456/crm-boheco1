<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AdditionalConsumptions
 * @package App\Models
 * @version October 12, 2023, 9:12 am PST
 *
 * @property string $AccountNumber
 * @property number $AdditionalKWH
 * @property number $AdditionalKW
 * @property string $Remarks
 */
class AdditionalConsumptions extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'AdditionalConsumptions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = ['AccountNumber', 'ServicePeriodEnd'];

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = [
        'ServicePeriodEnd',
        'AccountNumber',
        'AdditionalKWH',
        'AdditionalKW',
        'Remarks'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ServicePeriodEnd' => 'string',
        'AccountNumber' => 'string',
        'AdditionalKWH' => 'float',
        'AdditionalKW' => 'float',
        'Remarks' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServicePeriodEnd' => 'required|string',
        'AccountNumber' => 'required|string|max:20',
        'AdditionalKWH' => 'nullable|numeric',
        'AdditionalKW' => 'nullable|numeric',
        'Remarks' => 'nullable|string|max:255'
    ];

    
}
