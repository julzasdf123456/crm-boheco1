<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UnbundledRatesExtension
 * @package App\Models
 * @version August 4, 2022, 8:28 am PST
 *
 * @property string $rowguid
 * @property string|\Carbon\Carbon $ServicePeriodEnd
 * @property float $LCPerCustomer
 * @property float $Item2
 * @property float $Item3
 * @property float $Item4
 * @property float $Item11
 * @property float $Item12
 * @property float $Item13
 * @property float $Item5
 * @property float $Item6
 * @property float $Item7
 * @property float $Item8
 * @property float $Item9
 * @property float $Item10
 */
class UnbundledRatesExtension extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'UnbundledRatesExtension';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = 'rowguid';

    public $incrementing = false;

    public $fillable = [
        'ServicePeriodEnd',
        'LCPerCustomer',
        'Item2',
        'Item3',
        'Item4',
        'Item11',
        'Item12',
        'Item13',
        'Item5',
        'Item6',
        'Item7',
        'Item8',
        'Item9',
        'Item10'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rowguid' => 'string',
        'ConsumerType' => 'string',
        'ServicePeriodEnd' => 'datetime',
        'LCPerCustomer' => 'float',
        'Item2' => 'float',
        'Item3' => 'float',
        'Item4' => 'float',
        'Item11' => 'float',
        'Item12' => 'float',
        'Item13' => 'float',
        'Item5' => 'float',
        'Item6' => 'float',
        'Item7' => 'float',
        'Item8' => 'float',
        'Item9' => 'float',
        'Item10' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rowguid' => 'required|string',
        'ServicePeriodEnd' => 'required',
        'LCPerCustomer' => 'nullable|float',
        'Item2' => 'nullable|float',
        'Item3' => 'nullable|float',
        'Item4' => 'nullable|float',
        'Item11' => 'nullable|float',
        'Item12' => 'nullable|float',
        'Item13' => 'nullable|float',
        'Item5' => 'nullable|float',
        'Item6' => 'nullable|float',
        'Item7' => 'nullable|float',
        'Item8' => 'nullable|float',
        'Item9' => 'nullable|float',
        'Item10' => 'nullable|float'
    ];

    
}
