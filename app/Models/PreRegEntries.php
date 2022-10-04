<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PreRegEntries
 * @package App\Models
 * @version October 4, 2022, 10:52 am PST
 *
 * @property string $AccountNumber
 * @property string $Name
 * @property string $Year
 * @property string $RegisteredVenue
 * @property string|\Carbon\Carbon $DateRegistered
 * @property string $Status
 * @property string $RegistrationMedium
 * @property string $ContactNumber
 * @property string $Email
 * @property string $Signature
 */
class PreRegEntries extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Entries';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvagma";

    public $fillable = [
        'AccountNumber',
        'Name',
        'Year',
        'RegisteredVenue',
        'DateRegistered',
        'Status',
        'RegistrationMedium',
        'ContactNumber',
        'Email',
        'Signature'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'Id' => 'integer',
        'AccountNumber' => 'string',
        'Name' => 'string',
        'Year' => 'string',
        'RegisteredVenue' => 'string',
        'DateRegistered' => 'datetime',
        'Status' => 'string',
        'RegistrationMedium' => 'string',
        'ContactNumber' => 'string',
        'Email' => 'string',
        'Signature' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AccountNumber' => 'nullable|string|max:50',
        'Name' => 'nullable|string|max:1000',
        'Year' => 'nullable|string|max:50',
        'RegisteredVenue' => 'nullable|string|max:500',
        'DateRegistered' => 'nullable',
        'Status' => 'nullable|string|max:90',
        'RegistrationMedium' => 'nullable|string|max:500',
        'ContactNumber' => 'nullable|string|max:50',
        'Email' => 'nullable|string|max:50',
        'Signature' => 'nullable|string'
    ];

    
}
