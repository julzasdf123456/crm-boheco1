<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TempReadings
 * @package App\Models
 * @version August 29, 2023, 10:20 am PST
 *
 * @property string|\Carbon\Carbon $ServicePeriodEnd
 * @property string $AccountNumber
 * @property string $Route
 * @property integer $SequenceNumber
 * @property string $ConsumerName
 * @property string $ConsumerAddress
 * @property string $MeterNumber
 * @property number $PreviousReading2
 * @property number $PreviousReading1
 * @property number $PreviousReading
 * @property string|\Carbon\Carbon $ReadingDate
 * @property string $ReadBy
 * @property number $PowerReadings
 * @property number $DemandReadings
 * @property string $FieldFindings
 * @property string $MissCodes
 * @property string $Remarks
 * @property string $UpdateStatus
 * @property string $ConsumerType
 * @property string $AccountStatus
 * @property string $ShortAccountNumber
 * @property number $Multiplier
 * @property integer $MeterDigits
 * @property number $Coreloss
 * @property number $CorelossKWHLimit
 * @property number $AdditionalKWH
 * @property integer $TSFRental
 * @property string $SchoolTag
 */
class TempReadings extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'TempReadings';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = ['AccountNumber', 'ServicePeriodEnd'];

    public $incrementing = false;

    public $fillable = [
        'ServicePeriodEnd',
        'AccountNumber',
        'Route',
        'SequenceNumber',
        'ConsumerName',
        'ConsumerAddress',
        'MeterNumber',
        'PreviousReading2',
        'PreviousReading1',
        'PreviousReading',
        'ReadingDate',
        'ReadBy',
        'PowerReadings',
        'DemandReadings',
        'FieldFindings',
        'MissCodes',
        'Remarks',
        'UpdateStatus',
        'ConsumerType',
        'AccountStatus',
        'ShortAccountNumber',
        'Multiplier',
        'MeterDigits',
        'Coreloss',
        'CorelossKWHLimit',
        'AdditionalKWH',
        'TSFRental',
        'SchoolTag'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ServicePeriodEnd' => 'datetime',
        'AccountNumber' => 'string',
        'Route' => 'string',
        'SequenceNumber' => 'integer',
        'ConsumerName' => 'string',
        'ConsumerAddress' => 'string',
        'MeterNumber' => 'string',
        'PreviousReading2' => 'decimal:2',
        'PreviousReading1' => 'decimal:2',
        'PreviousReading' => 'decimal:2',
        'ReadingDate' => 'datetime',
        'ReadBy' => 'string',
        'PowerReadings' => 'decimal:2',
        'DemandReadings' => 'float',
        'FieldFindings' => 'string',
        'MissCodes' => 'string',
        'Remarks' => 'string',
        'UpdateStatus' => 'string',
        'ConsumerType' => 'string',
        'AccountStatus' => 'string',
        'ShortAccountNumber' => 'string',
        'Multiplier' => 'float',
        'MeterDigits' => 'integer',
        'Coreloss' => 'float',
        'CorelossKWHLimit' => 'float',
        'AdditionalKWH' => 'float',
        'TSFRental' => 'integer',
        'SchoolTag' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServicePeriodEnd' => 'nullable',
        'AccountNumber' => 'nullable|string|max:20',
        'Route' => 'nullable|string|max:10',
        'SequenceNumber' => 'nullable|integer',
        'ConsumerName' => 'nullable|string|max:100',
        'ConsumerAddress' => 'nullable|string|max:100',
        'MeterNumber' => 'nullable|string|max:20',
        'PreviousReading2' => 'nullable|numeric',
        'PreviousReading1' => 'nullable|numeric',
        'PreviousReading' => 'nullable|numeric',
        'ReadingDate' => 'nullable',
        'ReadBy' => 'nullable|string|max:10',
        'PowerReadings' => 'nullable|numeric',
        'DemandReadings' => 'nullable|numeric',
        'FieldFindings' => 'nullable|string|max:50',
        'MissCodes' => 'nullable|string|max:50',
        'Remarks' => 'nullable|string|max:150',
        'UpdateStatus' => 'nullable|string|max:10',
        'ConsumerType' => 'nullable|string|max:10',
        'AccountStatus' => 'nullable|string|max:20',
        'ShortAccountNumber' => 'nullable|string|max:10',
        'Multiplier' => 'nullable|numeric',
        'MeterDigits' => 'nullable',
        'Coreloss' => 'nullable|numeric',
        'CorelossKWHLimit' => 'nullable|numeric',
        'AdditionalKWH' => 'nullable|numeric',
        'TSFRental' => 'nullable|integer',
        'SchoolTag' => 'nullable|string|max:20'
    ];

    
}
