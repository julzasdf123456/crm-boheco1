<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Electricians
 * @package App\Models
 * @version August 3, 2022, 2:58 pm PST
 *
 * @property string $IDNumber
 * @property string $Name
 * @property string $Address
 * @property string $ContactNumber
 * @property string $BankNumber
 * @property string $Bank
 */
class Electricians extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Electricians';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'IDNumber',
        'Name',
        'Address',
        'ContactNumber',
        'BankNumber',
        'Bank',
        'Town',
        'Barangay',
        'District'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'IDNumber' => 'string',
        'Name' => 'string',
        'Address' => 'string',
        'ContactNumber' => 'string',
        'BankNumber' => 'string',
        'Bank' => 'string',
        'Town' => 'string',
        'Barangay' => 'string',
        'District' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'IDNumber' => 'nullable|string|max:500',
        'Name' => 'nullable|string|max:500',
        'Address' => 'nullable|string|max:500',
        'ContactNumber' => 'nullable|string|max:500',
        'BankNumber' => 'nullable|string|max:500',
        'Bank' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Town' => 'nullable|string',
        'Barangay' => 'nullable|string',
        'District' => 'nullable|string',
    ];

    
}
