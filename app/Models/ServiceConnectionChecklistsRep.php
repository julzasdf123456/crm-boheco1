<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionChecklistsRep
 * @package App\Models
 * @version August 24, 2021, 1:30 pm PST
 *
 * @property string $Checklist
 * @property string $Notes
 */
class ServiceConnectionChecklistsRep extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionChecklistRepository';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Checklist',
        'Notes',
        'Minimum'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Checklist' => 'string',
        'Notes' => 'string',
        'Minimum' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'Checklist' => 'required|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Minimum' => 'nullable|string',
    ];

    
}
