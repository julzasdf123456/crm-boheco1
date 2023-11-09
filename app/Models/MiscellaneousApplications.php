<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MiscellaneousApplications
 * @package App\Models
 * @version November 9, 2023, 11:43 am PST
 *
 * @property string $ConsumerName
 * @property string $Town
 * @property string $Barangay
 * @property string $Sitio
 * @property string $Application
 * @property string $Notes
 * @property string $Status
 * @property number $ServiceDropLength
 * @property number $TransformerLoad
 * @property string $TicketId
 * @property string $ServiceConnectionId
 * @property string $AccountNumber
 * @property string $UserId
 * @property number $TotalAmount
 * @property string $ORNumber
 * @property string $ORDate
 */
class MiscellaneousApplications extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MiscellaneousApplications';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ConsumerName',
        'Town',
        'Barangay',
        'Sitio',
        'Application',
        'Notes',
        'Status',
        'ServiceDropLength',
        'TransformerLoad',
        'TicketId',
        'ServiceConnectionId',
        'AccountNumber',
        'UserId',
        'TotalAmount',
        'ORNumber',
        'ORDate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ConsumerName' => 'string',
        'Town' => 'string',
        'Barangay' => 'string',
        'Sitio' => 'string',
        'Application' => 'string',
        'Notes' => 'string',
        'Status' => 'string',
        'ServiceDropLength' => 'decimal:2',
        'TransformerLoad' => 'decimal:2',
        'TicketId' => 'string',
        'ServiceConnectionId' => 'string',
        'AccountNumber' => 'string',
        'UserId' => 'string',
        'TotalAmount' => 'decimal:2',
        'ORNumber' => 'string',
        'ORDate' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ConsumerName' => 'nullable|string|max:600',
        'Town' => 'nullable|string|max:50',
        'Barangay' => 'nullable|string|max:50',
        'Sitio' => 'nullable|string|max:300',
        'Application' => 'nullable|string|max:500',
        'Notes' => 'nullable|string|max:3000',
        'Status' => 'nullable|string|max:50',
        'ServiceDropLength' => 'nullable|numeric',
        'TransformerLoad' => 'nullable|numeric',
        'TicketId' => 'nullable|string|max:50',
        'ServiceConnectionId' => 'nullable|string|max:50',
        'AccountNumber' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:50',
        'TotalAmount' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ORNumber' => 'nullable|string|max:50',
        'ORDate' => 'nullable'
    ];

    public static function getAddress($misc) {
        if ($misc->Sitio==null && ($misc->Barangay!=null && $misc->Town!=null)) {
            return $misc->Barangay . ', ' . $misc->Town;
        } elseif($misc->Sitio!=null && ($misc->Barangay!=null && $misc->Town!=null)) {
            return $misc->Sitio . ', ' . $misc->Barangay . ', ' . $misc->Town;
        }
    }
}
