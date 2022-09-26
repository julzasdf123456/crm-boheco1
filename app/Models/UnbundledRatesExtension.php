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
 * @property integer $LCPerCustomer
 * @property integer $Item2
 * @property integer $Item3
 * @property integer $Item4
 * @property integer $Item11
 * @property integer $Item12
 * @property integer $Item13
 * @property integer $Item5
 * @property integer $Item6
 * @property integer $Item7
 * @property integer $Item8
 * @property integer $Item9
 * @property integer $Item10
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
        'LCPerCustomer' => 'integer',
        'Item2' => 'integer',
        'Item3' => 'integer',
        'Item4' => 'integer',
        'Item11' => 'integer',
        'Item12' => 'integer',
        'Item13' => 'integer',
        'Item5' => 'integer',
        'Item6' => 'integer',
        'Item7' => 'integer',
        'Item8' => 'integer',
        'Item9' => 'integer',
        'Item10' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rowguid' => 'required|string',
        'ServicePeriodEnd' => 'required',
        'LCPerCustomer' => 'nullable|integer',
        'Item2' => 'nullable|integer',
        'Item3' => 'nullable|integer',
        'Item4' => 'nullable|integer',
        'Item11' => 'nullable|integer',
        'Item12' => 'nullable|integer',
        'Item13' => 'nullable|integer',
        'Item5' => 'nullable|integer',
        'Item6' => 'nullable|integer',
        'Item7' => 'nullable|integer',
        'Item8' => 'nullable|integer',
        'Item9' => 'nullable|integer',
        'Item10' => 'nullable|integer'
    ];

    
}
