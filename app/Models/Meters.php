<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Meters
 * @package App\Models
 * @version January 17, 2023, 9:59 am PST
 *
 * @property string $RecordStatus
 * @property string|\Carbon\Carbon $ChangeDate
 * @property integer $MeterDigits
 * @property number $Multiplier
 * @property string $ChargingMode
 * @property string $DemandType
 * @property string $Make
 * @property string $SerialNumber
 * @property string|\Carbon\Carbon $CalibrationDate
 * @property string $MeterStatus
 * @property number $InitialReading
 * @property number $InitialDemand
 * @property string $Remarks
 */
class Meters extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Meter';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'MeterNumber';

    public $incrementing = false;

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    public $fillable = [
        'MeterNumber',
        'RecordStatus',
        'ChangeDate',
        'MeterDigits',
        'Multiplier',
        'ChargingMode',
        'DemandType',
        'Make',
        'SerialNumber',
        'CalibrationDate',
        'MeterStatus',
        'InitialReading',
        'InitialDemand',
        'Remarks'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'MeterNumber' => 'string',
        'RecordStatus' => 'string',
        'ChangeDate' => 'datetime',
        'MeterDigits' => 'integer',
        'Multiplier' => 'float',
        'ChargingMode' => 'string',
        'DemandType' => 'string',
        'Make' => 'string',
        'SerialNumber' => 'string',
        'CalibrationDate' => 'datetime',
        'MeterStatus' => 'string',
        'InitialReading' => 'float',
        'InitialDemand' => 'float',
        'Remarks' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'RecordStatus' => 'nullable|string|max:1',
        'ChangeDate' => 'nullable',
        'MeterDigits' => 'nullable',
        'Multiplier' => 'nullable|numeric',
        'ChargingMode' => 'nullable|string|max:10',
        'DemandType' => 'nullable|string|max:10',
        'Make' => 'nullable|string|max:20',
        'SerialNumber' => 'nullable|string|max:20',
        'CalibrationDate' => 'nullable',
        'MeterStatus' => 'nullable|string|max:10',
        'InitialReading' => 'nullable|numeric',
        'InitialDemand' => 'nullable|numeric',
        'Remarks' => 'nullable|string|max:255'
    ];

    public static function registeredMeters() {
        return [
            '' => '',
            'ACCURA' => 'ACCURA',
            'ACLARA KV2C' => 'ACLARA KV2C',
            'AMERICO' => 'AMERICO',
            'BAZ' => 'BAZ',
            'CHINT' => 'CHINT',
            'EDMI MK10E' => 'EDMI MK10E',
            'EDMI MK6N' => 'EDMI MK6N',
            'EDMI MK6E' => 'EDMI MK6E',
            'EDMI MK7C' => 'EDMI MK7C',
            'ELETRA' => 'ELETRA',
            'EVER' => 'EVER',
            'FUJI' => 'FUJI',
            'GE' => 'GE',
            'GE I210' => 'GE I210',
            'GE KV2C' => 'GE KV2C',
            'GOLDSTAR' => 'GOLDSTAR',
            'HARBIN' => 'HARBIN',
            'HEAG' => 'HEAG',
            'INTECH' => 'INTECH', 
            'ITECHENE' => 'ITECHENE',
            'LANDIS' => 'LANDIS',
            'LANDIS FOCUS' => 'LANDIS FOCUS',
            'MITSUBISHI' => 'MITSUBISHI',
            'ORIENTAL' => 'ORIENTAL',
            'SAFARI' => 'SAFARI',
            'SANGAMO' => 'SANGOMO',
            'SUZSOU' => 'SUZSOU',
            'TATUNG' => 'TATUNG',
            'TECHEN' => 'TECHEN',
            'TOSHIBA' => 'TOSHIBA',
            'WARBURTON' => 'WARBURTON',
        ];
    }
}
