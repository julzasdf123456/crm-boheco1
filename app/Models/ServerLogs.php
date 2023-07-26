<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServerLogs
 * @package App\Models
 * @version July 26, 2023, 2:17 pm PST
 *
 * @property string $ServerIpSource
 * @property string $ServerNameSource
 * @property string $Details
 */
class ServerLogs extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvtracey";

    public $fillable = [
        'id',
        'ServerIpSource',
        'ServerNameSource',
        'Details'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServerIpSource' => 'string',
        'ServerNameSource' => 'string',
        'Details' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServerIpSource' => 'nullable|string|max:50',
        'ServerNameSource' => 'nullable|string|max:50',
        'Details' => 'nullable|string|max:5000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
