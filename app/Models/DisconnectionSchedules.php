<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DisconnectionSchedules
 * @package App\Models
 * @version July 7, 2023, 10:34 am PST
 *
 * @property string $DisconnectorName
 * @property string $DisconnectorId
 * @property string $Day
 * @property string $ServicePeriodEnd
 * @property string $Routes
 * @property string $SequenceFrom
 * @property string $SequenceTo
 * @property string $Status
 * @property string|\Carbon\Carbon $DatetimeDownloaded
 * @property string $PhoneModel
 * @property string $UserId
 */
class DisconnectionSchedules extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'DisconnectionSchedules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'DisconnectorName',
        'DisconnectorId',
        'Day',
        'ServicePeriodEnd',
        'Routes',
        'SequenceFrom',
        'SequenceTo',
        'Status',
        'DatetimeDownloaded',
        'PhoneModel',
        'UserId',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'DisconnectorName' => 'string',
        'DisconnectorId' => 'string',
        'Day' => 'string',
        'ServicePeriodEnd' => 'string',
        'Routes' => 'string',
        'SequenceFrom' => 'string',
        'SequenceTo' => 'string',
        'Status' => 'string',
        'DatetimeDownloaded' => 'string',
        'PhoneModel' => 'string',
        'UserId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'DisconnectorName' => 'nullable|string|max:500',
        'DisconnectorId' => 'nullable|string|max:50',
        'Day' => 'nullable',
        'ServicePeriodEnd' => 'nullable',
        'Routes' => 'nullable|string|max:500',
        'SequenceFrom' => 'nullable|string|max:50',
        'SequenceTo' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'DatetimeDownloaded' => 'nullable',
        'PhoneModel' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function bgStatus($status) {
        if ($status == 'Disconnected') {
            return 'bg-danger';
        } elseif ($status == 'Promised') {
            return 'bg-warning';
        } elseif ($status == 'Paid') {
            return 'bg-success';
        } elseif ($status == 'Bereavement/Funeral Wake' | $status == 'In The Hospital') {
            return 'bg-primary';
        } else {
            return 'bg-info';
        }
    }
}
