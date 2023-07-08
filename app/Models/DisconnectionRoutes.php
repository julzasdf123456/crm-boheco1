<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DisconnectionRoutes
 * @package App\Models
 * @version July 7, 2023, 2:04 pm PST
 *
 * @property string $ScheduleId
 * @property string $Route
 * @property string $SequenceFrom
 * @property string $SequenceTo
 * @property string $Notes
 */
class DisconnectionRoutes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'DisconnectionRoutes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ScheduleId',
        'Route',
        'SequenceFrom',
        'SequenceTo',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ScheduleId' => 'string',
        'Route' => 'string',
        'SequenceFrom' => 'string',
        'SequenceTo' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ScheduleId' => 'nullable|string|max:60',
        'Route' => 'nullable|string|max:50',
        'SequenceFrom' => 'nullable|string|max:50',
        'SequenceTo' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
