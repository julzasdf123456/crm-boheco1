<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ChangeMeter
 * @package App\Models
 * @version July 19, 2023, 2:09 pm PST
 *
 * @property string $AccountNumber
 * @property string|\Carbon\Carbon $ChangeDate
 * @property string $OldMeter
 * @property string $NewMeter
 * @property number $PullOutReading
 * @property string $ReplaceBy
 * @property string $Remarks
 */
class ChangeMeter extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'ChangeMeter';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    public $fillable = [
        'AccountNumber',
        'ChangeDate',
        'OldMeter',
        'NewMeter',
        'PullOutReading',
        'ReplaceBy',
        'Remarks'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'AccountNumber' => 'string',
        'ChangeDate' => 'datetime',
        'OldMeter' => 'string',
        'NewMeter' => 'string',
        'PullOutReading' => 'float',
        'ReplaceBy' => 'string',
        'Remarks' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AccountNumber' => 'required|string|max:20',
        'ChangeDate' => 'nullable',
        'OldMeter' => 'nullable|string|max:20',
        'NewMeter' => 'nullable|string|max:20',
        'PullOutReading' => 'nullable|numeric',
        'ReplaceBy' => 'nullable|string|max:50',
        'Remarks' => 'nullable|string|max:50'
    ];

    
}
