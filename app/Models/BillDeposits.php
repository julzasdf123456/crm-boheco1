<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillDeposits
 * @package App\Models
 * @version August 4, 2022, 1:33 pm PST
 *
 * @property string $ServiceConnectionId
 * @property string $Load
 * @property string $PowerFactor
 * @property string $DemandFactor
 * @property string $Hours
 * @property string $AverageRate
 * @property string $AverageTransmission
 * @property string $AverageDemand
 * @property string $BillDepositAmount
 */
class BillDeposits extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_BillDeposits';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrv";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Load',
        'PowerFactor',
        'DemandFactor',
        'Hours',
        'AverageRate',
        'AverageTransmission',
        'AverageDemand',
        'BillDepositAmount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'Load' => 'string',
        'PowerFactor' => 'string',
        'DemandFactor' => 'string',
        'Hours' => 'string',
        'AverageRate' => 'string',
        'AverageTransmission' => 'string',
        'AverageDemand' => 'string',
        'BillDepositAmount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'Load' => 'nullable|string|max:255',
        'PowerFactor' => 'nullable|string|max:255',
        'DemandFactor' => 'nullable|string|max:255',
        'Hours' => 'nullable|string|max:255',
        'AverageRate' => 'nullable|string|max:255',
        'AverageTransmission' => 'nullable|string|max:255',
        'AverageDemand' => 'nullable|string|max:255',
        'BillDepositAmount' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
