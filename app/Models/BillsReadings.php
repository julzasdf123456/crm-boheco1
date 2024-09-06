<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillsReadings
 * @package App\Models
 * @version September 6, 2024, 8:20 am PST
 *
 * @property string $AccountNumber
 * @property string|\Carbon\Carbon $ReadingDate
 * @property string $ReadBy
 * @property number $PowerReadings
 * @property number $DemandReadings
 * @property string $FieldFindings
 * @property string $MissCodes
 * @property string $Remarks
 */
class BillsReadings extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Readings';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    // protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    
    protected $primaryKey = ['AccountNumber', 'ServicePeriodEnd'];

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = [
        'AccountNumber',
        'ReadingDate',
        'ReadBy',
        'PowerReadings',
        'DemandReadings',
        'FieldFindings',
        'MissCodes',
        'Remarks',
        'ServicePeriodEnd'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ServicePeriodEnd' => 'datetime',
        'AccountNumber' => 'string',
        'ReadingDate' => 'datetime',
        'ReadBy' => 'string',
        'PowerReadings' => 'decimal:2',
        'DemandReadings' => 'float',
        'FieldFindings' => 'string',
        'MissCodes' => 'string',
        'Remarks' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AccountNumber' => 'required|string|max:20',
        'ReadingDate' => 'nullable',
        'ReadBy' => 'nullable|string|max:50',
        'PowerReadings' => 'nullable|numeric',
        'DemandReadings' => 'nullable|numeric',
        'FieldFindings' => 'nullable|string|max:50',
        'MissCodes' => 'nullable|string|max:50',
        'Remarks' => 'nullable|string|max:255'
    ];

    
}
