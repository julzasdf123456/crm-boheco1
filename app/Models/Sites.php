<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Sites
 * @package App\Models
 * @version August 29, 2023, 2:14 pm PST
 *
 * @property string $URL
 * @property string $Notes
 */
class Sites extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Sites';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvtracey";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'URL',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'URL' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'URL' => 'nullable|string|max:600',
        'Notes' => 'nullable|string|max:600',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
