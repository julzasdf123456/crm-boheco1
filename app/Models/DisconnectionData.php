<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DisconnectionData
 * @package App\Models
 * @version July 7, 2023, 10:36 am PST
 *
 * @property string $ScheduleId
 * @property string $DisconnectorName
 * @property string $UserId
 * @property string $AccountNumber
 * @property string $ServicePeriodEnd
 * @property string $AccountCoordinates
 * @property string $Latitude
 * @property string $Longitude
 * @property string $Status
 * @property string $Notes
 * @property number $NetAmount
 * @property number $Surcharge
 * @property number $ServiceFee
 * @property number $Others
 * @property number $PaidAmount
 * @property string $ORNumber
 * @property string $ORDate
 */
class DisconnectionData extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'DisconnectionData';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ScheduleId',
        'DisconnectorName',
        'UserId',
        'AccountNumber',
        'ServicePeriodEnd',
        'AccountCoordinates',
        'Latitude',
        'Longitude',
        'Status',
        'Notes',
        'NetAmount',
        'Surcharge',
        'ServiceFee',
        'Others',
        'PaidAmount',
        'ORNumber',
        'ORDate',
        'ConsumerName',
        'ConsumerAddress',
        'MeterNumber',
        'PoleNumber',
        'DisconnectionDate',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ScheduleId' => 'string',
        'DisconnectorName' => 'string',
        'UserId' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriodEnd' => 'string',
        'AccountCoordinates' => 'string',
        'Latitude' => 'string',
        'Longitude' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'NetAmount' => 'decimal:2',
        'Surcharge' => 'decimal:2',
        'ServiceFee' => 'decimal:2',
        'Others' => 'decimal:2',
        'PaidAmount' => 'decimal:2',
        'ORNumber' => 'string',
        'ORDate' => 'string',
        'ConsumerName' => 'string',
        'ConsumerAddress' => 'string',
        'MeterNumber' => 'string',
        'PoleNumber' => 'string',
        'DisconnectionDate' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ScheduleId' => 'nullable|string|max:60',
        'DisconnectorName' => 'nullable|string|max:500',
        'UserId' => 'nullable|string|max:50',
        'AccountNumber' => 'nullable|string|max:50',
        'ServicePeriodEnd' => 'nullable',
        'AccountCoordinates' => 'nullable|string|max:60',
        'Latitude' => 'nullable|string|max:50',
        'Longitude' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1500',
        'NetAmount' => 'nullable|numeric',
        'Surcharge' => 'nullable|numeric',
        'ServiceFee' => 'nullable|numeric',
        'Others' => 'nullable|numeric',
        'PaidAmount' => 'nullable|numeric',
        'ORNumber' => 'nullable|string|max:50',
        'ORDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ConsumerName' => 'nullable|string',
        'ConsumerAddress' => 'nullable|string',
        'MeterNumber' => 'nullable|string',
        'PoleNumber' => 'nullable|string',
        'DisconnectionDate' => 'nullable|string',
    ];

    
}
