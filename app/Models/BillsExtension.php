<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillsExtension
 * @package App\Models
 * @version February 7, 2023, 10:04 am PST
 *
 * @property string|\Carbon\Carbon $ServicePeriodEnd
 * @property float $GenerationVAT
 * @property float $TransmissionVAT
 * @property float $SLVAT
 * @property float $DistributionVAT
 * @property float $OthersVAT
 * @property float $Item5
 * @property float $Item6
 * @property float $Item7
 * @property string $Item8
 * @property float $Item9
 * @property float $Item10
 * @property float $Item11
 * @property float $Item12
 * @property float $Item13
 * @property float $Item14
 * @property float $Item15
 * @property float $Item16
 * @property float $Item17
 * @property float $Item18
 * @property float $Item19
 * @property float $Item20
 * @property float $Item21
 * @property float $Item22
 * @property float $Item23
 * @property float $Item24
 */
class BillsExtension extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'BillsExtension';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = ['AccountNumber', 'ServicePeriodEnd'];

    public $incrementing = false;

    public $fillable = [
        'AccountNumber',
        'ServicePeriodEnd',
        'GenerationVAT',
        'TransmissionVAT',
        'SLVAT',
        'DistributionVAT',
        'OthersVAT',
        'Item5',
        'Item6',
        'Item7',
        'Item8',
        'Item9',
        'Item10',
        'Item11',
        'Item12',
        'Item13',
        'Item14',
        'Item15',
        'Item16',
        'Item17',
        'Item18',
        'Item19',
        'Item20',
        'Item21',
        'Item22',
        'Item23',
        'Item24'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'AccountNumber' => 'string',
        'ServicePeriodEnd' => 'datetime',
        'GenerationVAT' => 'float',
        'TransmissionVAT' => 'float',
        'SLVAT' => 'float',
        'DistributionVAT' => 'float',
        'OthersVAT' => 'float',
        'Item5' => 'float',
        'Item6' => 'float',
        'Item7' => 'float',
        'Item8' => 'string',
        'Item9' => 'float',
        'Item10' => 'float',
        'Item11' => 'float',
        'Item12' => 'float',
        'Item13' => 'float',
        'Item14' => 'float',
        'Item15' => 'float',
        'Item16' => 'float',
        'Item17' => 'float',
        'Item18' => 'float',
        'Item19' => 'float',
        'Item20' => 'float',
        'Item21' => 'float',
        'Item22' => 'float',
        'Item23' => 'float',
        'Item24' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'GenerationVAT' => 'nullable|float',
        'TransmissionVAT' => 'nullable|float',
        'SLVAT' => 'nullable|float',
        'DistributionVAT' => 'nullable|float',
        'OthersVAT' => 'nullable|float',
        'Item5' => 'nullable|float',
        'Item6' => 'nullable|float',
        'Item7' => 'nullable|float',
        'Item8' => 'nullable|string|max:10',
        'Item9' => 'nullable|float',
        'Item10' => 'nullable|float',
        'Item11' => 'nullable|float',
        'Item12' => 'nullable|float',
        'Item13' => 'nullable|float',
        'Item14' => 'nullable|float',
        'Item15' => 'nullable|float',
        'Item16' => 'nullable|float',
        'Item17' => 'nullable|float',
        'Item18' => 'nullable|float',
        'Item19' => 'nullable|float',
        'Item20' => 'nullable|float',
        'Item21' => 'nullable|float',
        'Item22' => 'nullable|float',
        'Item23' => 'nullable|float',
        'Item24' => 'nullable|float'
    ];

    
}
