<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Servers
 * @package App\Models
 * @version July 26, 2023, 2:14 pm PST
 *
 * @property string $ServerName
 * @property string $ServerIp
 * @property string $Status
 */
class Servers extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Servers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvtracey";

    public $fillable = [
        'id',
        'ServerName',
        'ServerIp',
        'Status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServerName' => 'string',
        'ServerIp' => 'string',
        'Status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServerName' => 'nullable|string|max:90',
        'ServerIp' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
