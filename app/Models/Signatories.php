<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Signatories
 * @package App\Models
 * @version January 9, 2023, 11:44 am PST
 *
 * @property string $Name
 * @property string $Designation
 * @property string $Office
 * @property string $Signature
 * @property string $Notes
 */
class Signatories extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Signatories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Name',
        'Designation',
        'Office',
        'Signature',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Name' => 'string',
        'Designation' => 'string',
        'Office' => 'string',
        'Signature' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Name' => 'nullable|string|max:300',
        'Designation' => 'nullable|string|max:500',
        'Office' => 'nullable|string|max:50',
        'Signature' => 'nullable|string',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
