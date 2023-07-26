<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServerStats
 * @package App\Models
 * @version July 26, 2023, 2:16 pm PST
 *
 * @property string $ServerId
 * @property number $CpuPercentage
 * @property number $MemoryPercentage
 * @property number $DiskPercentage
 * @property number $TotalMemory
 */
class ServerStats extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'ServerStats';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvtracey";

    public $fillable = [
        'id',
        'ServerId',
        'CpuPercentage',
        'MemoryPercentage',
        'DiskPercentage',
        'TotalMemory'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServerId' => 'string',
        'CpuPercentage' => 'decimal:2',
        'MemoryPercentage' => 'decimal:2',
        'DiskPercentage' => 'decimal:2',
        'TotalMemory' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServerId' => 'nullable|string|max:50',
        'CpuPercentage' => 'nullable|numeric',
        'MemoryPercentage' => 'nullable|numeric',
        'DiskPercentage' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'TotalMemory' => 'nullable|numeric'
    ];

    
}
