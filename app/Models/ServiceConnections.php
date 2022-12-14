<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnections
 * @package App\Models
 * @version July 21, 2021, 6:12 am UTC
 *
 * @property string $MemberConsumerId
 * @property string $DateOfApplication
 * @property string $ServiceAccountName
 * @property integer $AccountCount
 * @property string $Sitio
 * @property string $Barangay
 * @property string $Town
 * @property string $ContactNumber
 * @property string $EmailAddress
 * @property string $AccountType
 * @property string $AccountOrganization
 * @property string $OrganizationAccountNumber
 * @property string $IsNIHE
 * @property string $AccountApplicationType
 * @property string $ConnectionApplicationType
 * @property string $Status
 * @property string $Notes
 */
class ServiceConnections extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnections';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $primaryKey = 'id';

    public $incrementing = false;


    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'MemberConsumerId',
        'DateOfApplication',
        'ServiceAccountName',
        'AccountCount',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumber',
        'EmailAddress',
        'AccountType',
        'AccountOrganization',
        'OrganizationAccountNumber',
        'IsNIHE',
        'AccountApplicationType',
        'ConnectionApplicationType',
        'BuildingType',
        'Status',
        'Notes',
        'Trash',
        'ORNumber',
        'ORDate',
        'DateTimeLinemenArrived',
        'DateTimeOfEnergization',
        'EnergizationOrderIssued',
        'DateTimeOfEnergizationIssue',
        'StationCrewAssigned',
        'LoadCategory',
        'TemporaryDurationInMonths',
        'LongSpan',
        'Office',
        'TypeOfOccupancy',
        'ResidenceNumber',
        'AccountNumber',
        'Indigent',
        'Phase',
        'ElectricianId',
        'ElectricianName',
        'ElectricianAddress',
        'ElectricianContactNo',
        'ElectricianAcredited',
        'DateTimeLinemanDownloaded'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'MemberConsumerId' => 'string',
        'DateOfApplication' => 'date',
        'ServiceAccountName' => 'string',
        'AccountCount' => 'integer',
        'Sitio' => 'string',
        'Barangay' => 'string',
        'Town' => 'string',
        'ContactNumber' => 'string',
        'EmailAddress' => 'string',
        'AccountType' => 'string',
        'AccountOrganization' => 'string',
        'OrganizationAccountNumber' => 'string',
        'IsNIHE' => 'string',
        'AccountApplicationType' => 'string',
        'ConnectionApplicationType' => 'string',
        'BuildingType' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'Trash' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'DateTimeLinemenArrived' => 'datetime',
        'DateTimeOfEnergization' => 'datetime',
        'EnergizationOrderIssued' => 'string',
        'DateTimeOfEnergizationIssue' => 'datetime',
        'StationCrewAssigned' => 'string',
        'LoadCategory' => 'string',
        'TemporaryDurationInMonths' => 'string',
        'LongSpan' => 'string',
        'Office' => 'string',
        'TypeOfOccupancy' => 'string',
        'ResidenceNumber' => 'string',
        'AccountNumber' => 'string',
        'Indigent' => 'string',
        'Phase' => 'string',
        'ElectricianId' => 'string',
        'ElectricianName' => 'string',
        'ElectricianAddress' => 'string',
        'ElectricianContactNo' => 'string',
        'ElectricianAcredited' => 'string',
        'DateTimeLinemanDownloaded' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'MemberConsumerId' => 'nullable|string|max:255',
        'DateOfApplication' => 'nullable',
        'ServiceAccountName' => 'required|string|max:255',
        'AccountCount' => 'nullable|integer',
        'Sitio' => 'nullable|string|max:1000',
        'Barangay' => 'required|string|max:10',
        'Town' => 'required|string|max:10',
        'ContactNumber' => 'required|string|max:500',
        'EmailAddress' => 'nullable|string|max:800',
        'AccountType' => 'nullable|string|max:100',
        'AccountOrganization' => 'nullable|string|max:100',
        'OrganizationAccountNumber' => 'nullable|string|max:100',
        'IsNIHE' => 'nullable|string|max:255',
        'AccountApplicationType' => 'nullable|string|max:100',
        'ConnectionApplicationType' => 'nullable|string|max:100',
        'BuildingType' => 'nullable|string',
        'Status' => 'nullable|string|max:100',
        'Notes' => 'nullable|string|max:2000',
        'Trash' => 'nullable|string',
        'ORNumber' => 'nullable|string',
        'ORDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DateTimeLinemenArrived' => 'nullable',
        'DateTimeOfEnergization' => 'nullable',
        'EnergizationOrderIssued' => 'nullable|string',
        'DateTimeOfEnergizationIssue' => 'nullable',
        'StationCrewAssigned' => 'nullable|string',
        'LoadCategory' => 'required|string',
        'TemporaryDurationInMonths' => 'nullable|string',
        'LongSpan' => 'nullable|string',
        'Office' => 'nullable|string',
        'TypeOfOccupancy' => 'nullable|string',
        'ResidenceNumber' => 'nullable|string',
        'AccountNumber' => 'nullable|string',
        'Indigent' => 'nullable|string',
        'Phase' => 'nullable|string',
        'ElectricianId' => 'nullable|string',
        'ElectricianName' => 'nullable|string',
        'ElectricianAddress' => 'nullable|string',
        'ElectricianContactNo' => 'nullable|string',
        'ElectricianAcredited' => 'nullable|string',
        'DateTimeLinemanDownloaded' => 'nullable|string'
    ];

    public static function getAccountCount($consumerId) {
        $sc = ServiceConnections::where('MemberConsumerId', $consumerId)->get();

        if ($sc == null) {
            return 0;
        } else {
            return count($sc);
        }
    }

    public static function getContactInfo($serviceConnections) {
        if ($serviceConnections->ContactNumber==null && $serviceConnections->EmailAddress==null) {
            return 'not specified';
        } elseif ($serviceConnections->ContactNumber==null && $serviceConnections->EmailAddress!=null) {
            return $serviceConnections->EmailAddress;
        } elseif ($serviceConnections->ContactNumber!=null && $serviceConnections->EmailAddress==null) {
            return $serviceConnections->ContactNumber;
        } else {
            return $serviceConnections->ContactNumber . ' | ' . $serviceConnections->EmailAddress;
        }
    }

    public static function getAddress($serviceConnections) {
        if ($serviceConnections->Sitio==null && ($serviceConnections->Barangay!=null && $serviceConnections->Town!=null)) {
            return $serviceConnections->Barangay . ', ' . $serviceConnections->Town;
        } elseif($serviceConnections->Sitio!=null && ($serviceConnections->Barangay!=null && $serviceConnections->Town!=null)) {
            return $serviceConnections->Sitio . ', ' . $serviceConnections->Barangay . ', ' . $serviceConnections->Town;
        }
    }

    public static function getResidentialId() {
        return '1627280880118';
    }

    public static function getStreetLightId() {
        return '1643002557527';
    }

    public static function getServiceConnectionFees($serviceConnections) {
        if (floatval($serviceConnections->Phase) == 3) {
            // HIGHER VOLTAGES
            if (floatval($serviceConnections->LoadCategory) >= 37.5 && floatval($serviceConnections->LoadCategory) < 75) {
                return 3575.00;
            } elseif (floatval($serviceConnections->LoadCategory) >= 75 && floatval($serviceConnections->LoadCategory) < 225) {
                return 5360.00;
            } elseif (floatval($serviceConnections->LoadCategory) >= 225) {
                return 9310.00;
            }
        } elseif (floatval($serviceConnections->Phase) == 1) {            
            if ($serviceConnections->AccountType==ServiceConnections::getResidentialId()) { 
                if ($serviceConnections->Indigent == 'Yes') {
                    return 95.00;
                } else {
                    // RESIDENTIAL
                    if (floatval($serviceConnections->LoadCategory) >= 5) {
                        return 1790.00;
                    } else {
                        return 595.00;
                    }
                }                
            } else {
                // NON RESIDENTIAL
                if (floatval($serviceConnections->LoadCategory) >= 5) {
                    return 1790.00;
                } else {
                    return 1190.00;
                }
            }
        } else {
            return 0;
        }
    }

    public static function getDemandFactor($consumerTypeAlias) {
        if ($consumerTypeAlias == 'B' | $consumerTypeAlias == 'E' | $consumerTypeAlias == 'RC' | $consumerTypeAlias == 'RI' | $consumerTypeAlias == 'RM') {
            return .15;
        } elseif ($consumerTypeAlias == 'S') {
            return .50;
        } else {
            return .30;
        }
    }

    public static function getBgStatus($status) {
        if ($status=='Energized' | $status=='Closed') {
            return 'bg-success';
        } elseif ($status=='For Inspection') {
            return 'bg-warning';
        } elseif ($status=='Approved' | $status=='Downloaded by Crew') {
            return 'bg-info';
        } elseif($status=='For Transformer and Pole Assigning' | $status=='Forwarded To Planning') {
            return 'bg-primary';
        } else {
            return 'bg-danger';
        }
    }

    public static function getProgressStatus($status) {
        if ($status=='For Inspection' || $status=='Re-Inspection') {
            return 14.28;
        } elseif ($status=='Approved') {
            return 28.56;
        } elseif($status=='Forwarded To Planning') {
            return 42.84;
        } elseif($status=='For Transformer and Pole Assigning') {
            return 57.12;
        } elseif ($status=='Downloaded by Crew') {
            return 71.4;
        } elseif($status=='Energized') {
            return 85.68;
        } elseif($status=='Closed') {
            return 100;
        }
    }

    public static function getOfficeBg($office) {
        if ($office == 'MAIN OFFICE') {
            return 'bg-primary';
        } elseif ($office == 'SUB-OFFICE') {
            return 'bg-danger';
        } else {
            return 'bg-info';
        }
    }
}
