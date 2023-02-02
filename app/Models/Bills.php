<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Bills
 * @package App\Models
 * @version February 1, 2023, 2:52 pm PST
 *
 * @property string $AccountNumber
 * @property number $PowerPreviousReading
 * @property number $PowerPresentReading
 * @property number $DemandPreviousReading
 * @property number $DemandPresentReading
 * @property number $AdditionalKWH
 * @property number $AdditionalKWDemand
 * @property number $PowerKWH
 * @property integer $KWHAmount
 * @property number $DemandKW
 * @property integer $KWAmount
 * @property integer $Charges
 * @property integer $Deductions
 * @property integer $NetAmount
 * @property integer $PowerRate
 * @property integer $DemandRate
 * @property string|\Carbon\Carbon $BillingDate
 * @property string|\Carbon\Carbon $ServiceDateFrom
 * @property string|\Carbon\Carbon $ServiceDateTo
 * @property string|\Carbon\Carbon $DueDate
 * @property string $BillNumber
 * @property string $Remarks
 * @property number $AverageKWH
 * @property number $AverageKWDemand
 * @property number $CoreLoss
 * @property integer $Meter
 * @property integer $PR
 * @property integer $SDW
 * @property integer $Others
 * @property integer $PPA
 * @property integer $PPAAmount
 * @property integer $BasicAmount
 * @property integer $PRADiscount
 * @property integer $PRAAmount
 * @property integer $PPCADiscount
 * @property integer $PPCAAmount
 * @property integer $UCAmount
 * @property string $MeterNumber
 * @property string $ConsumerType
 * @property string $BillType
 * @property integer $QCAmount
 * @property integer $EPAmount
 * @property integer $PCAmount
 * @property integer $LoanCondonation
 * @property string|\Carbon\Carbon $BillingPeriod
 * @property boolean $UnbundledTag
 * @property integer $GenerationSystemAmt
 * @property integer $FBHCAmt
 * @property integer $FPCAAdjustmentAmt
 * @property integer $ForexAdjustmentAmt
 * @property integer $TransmissionDemandAmt
 * @property integer $TransmissionSystemAmt
 * @property integer $DistributionDemandAmt
 * @property integer $DistributionSystemAmt
 * @property integer $SupplyRetailCustomerAmt
 * @property integer $SupplySystemAmt
 * @property integer $MeteringRetailCustomerAmt
 * @property integer $MeteringSystemAmt
 * @property integer $SystemLossAmt
 * @property integer $CrossSubsidyCreditAmt
 * @property integer $MissionaryElectrificationAmt
 * @property integer $EnvironmentalAmt
 * @property integer $LifelineSubsidyAmt
 * @property integer $Item1
 * @property integer $Item2
 * @property integer $Item3
 * @property integer $Item4
 * @property integer $SeniorCitizenDiscount
 * @property integer $SeniorCitizenSubsidy
 * @property integer $UCMERefund
 * @property number $NetPrevReading
 * @property number $NetPresReading
 * @property number $NetPowerKWH
 * @property number $NetGenerationAmount
 * @property number $CreditKWH
 * @property number $CreditAmount
 * @property number $NetMeteringSystemAmt
 * @property number $DAA_GRAM
 * @property number $DAA_ICERA
 * @property number $ACRM_TAFPPCA
 * @property number $ACRM_TAFxA
 * @property number $DAA_VAT
 * @property number $ACRM_VAT
 * @property integer $NetMeteringNetAmount
 * @property string $ReferenceNo
 */
class Bills extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Bills';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = ['AccountNumber', 'ServicePeriodEnd'];

    public $incrementing = false;

    public $fillable = [
        'ServicePeriodEnd',
        'AccountNumber',
        'PowerPreviousReading',
        'PowerPresentReading',
        'DemandPreviousReading',
        'DemandPresentReading',
        'AdditionalKWH',
        'AdditionalKWDemand',
        'PowerKWH',
        'KWHAmount',
        'DemandKW',
        'KWAmount',
        'Charges',
        'Deductions',
        'NetAmount',
        'PowerRate',
        'DemandRate',
        'BillingDate',
        'ServiceDateFrom',
        'ServiceDateTo',
        'DueDate',
        'BillNumber',
        'Remarks',
        'AverageKWH',
        'AverageKWDemand',
        'CoreLoss',
        'Meter',
        'PR',
        'SDW',
        'Others',
        'PPA',
        'PPAAmount',
        'BasicAmount',
        'PRADiscount',
        'PRAAmount',
        'PPCADiscount',
        'PPCAAmount',
        'UCAmount',
        'MeterNumber',
        'ConsumerType',
        'BillType',
        'QCAmount',
        'EPAmount',
        'PCAmount',
        'LoanCondonation',
        'BillingPeriod',
        'UnbundledTag',
        'GenerationSystemAmt',
        'FBHCAmt',
        'FPCAAdjustmentAmt',
        'ForexAdjustmentAmt',
        'TransmissionDemandAmt',
        'TransmissionSystemAmt',
        'DistributionDemandAmt',
        'DistributionSystemAmt',
        'SupplyRetailCustomerAmt',
        'SupplySystemAmt',
        'MeteringRetailCustomerAmt',
        'MeteringSystemAmt',
        'SystemLossAmt',
        'CrossSubsidyCreditAmt',
        'MissionaryElectrificationAmt',
        'EnvironmentalAmt',
        'LifelineSubsidyAmt',
        'Item1',
        'Item2',
        'Item3',
        'Item4',
        'SeniorCitizenDiscount',
        'SeniorCitizenSubsidy',
        'UCMERefund',
        'NetPrevReading',
        'NetPresReading',
        'NetPowerKWH',
        'NetGenerationAmount',
        'CreditKWH',
        'CreditAmount',
        'NetMeteringSystemAmt',
        'DAA_GRAM',
        'DAA_ICERA',
        'ACRM_TAFPPCA',
        'ACRM_TAFxA',
        'DAA_VAT',
        'ACRM_VAT',
        'NetMeteringNetAmount',
        'ReferenceNo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ServicePeriodEnd' => 'datetime',
        'AccountNumber' => 'string',
        'PowerPreviousReading' => 'decimal:2',
        'PowerPresentReading' => 'decimal:2',
        'DemandPreviousReading' => 'float',
        'DemandPresentReading' => 'float',
        'AdditionalKWH' => 'float',
        'AdditionalKWDemand' => 'float',
        'PowerKWH' => 'decimal:2',
        'KWHAmount' => 'integer',
        'DemandKW' => 'float',
        'KWAmount' => 'integer',
        'Charges' => 'integer',
        'Deductions' => 'integer',
        'NetAmount' => 'integer',
        'PowerRate' => 'integer',
        'DemandRate' => 'integer',
        'BillingDate' => 'datetime',
        'ServiceDateFrom' => 'datetime',
        'ServiceDateTo' => 'datetime',
        'DueDate' => 'datetime',
        'BillNumber' => 'string',
        'Remarks' => 'string',
        'AverageKWH' => 'float',
        'AverageKWDemand' => 'float',
        'CoreLoss' => 'float',
        'Meter' => 'integer',
        'PR' => 'integer',
        'SDW' => 'integer',
        'Others' => 'integer',
        'PPA' => 'integer',
        'PPAAmount' => 'integer',
        'BasicAmount' => 'integer',
        'PRADiscount' => 'integer',
        'PRAAmount' => 'integer',
        'PPCADiscount' => 'integer',
        'PPCAAmount' => 'integer',
        'UCAmount' => 'integer',
        'MeterNumber' => 'string',
        'ConsumerType' => 'string',
        'BillType' => 'string',
        'QCAmount' => 'integer',
        'EPAmount' => 'integer',
        'PCAmount' => 'integer',
        'LoanCondonation' => 'integer',
        'BillingPeriod' => 'datetime',
        'UnbundledTag' => 'boolean',
        'GenerationSystemAmt' => 'integer',
        'FBHCAmt' => 'integer',
        'FPCAAdjustmentAmt' => 'integer',
        'ForexAdjustmentAmt' => 'integer',
        'TransmissionDemandAmt' => 'integer',
        'TransmissionSystemAmt' => 'integer',
        'DistributionDemandAmt' => 'integer',
        'DistributionSystemAmt' => 'integer',
        'SupplyRetailCustomerAmt' => 'integer',
        'SupplySystemAmt' => 'integer',
        'MeteringRetailCustomerAmt' => 'integer',
        'MeteringSystemAmt' => 'integer',
        'SystemLossAmt' => 'integer',
        'CrossSubsidyCreditAmt' => 'integer',
        'MissionaryElectrificationAmt' => 'integer',
        'EnvironmentalAmt' => 'integer',
        'LifelineSubsidyAmt' => 'integer',
        'Item1' => 'integer',
        'Item2' => 'integer',
        'Item3' => 'integer',
        'Item4' => 'integer',
        'SeniorCitizenDiscount' => 'integer',
        'SeniorCitizenSubsidy' => 'integer',
        'UCMERefund' => 'integer',
        'NetPrevReading' => 'decimal:2',
        'NetPresReading' => 'decimal:2',
        'NetPowerKWH' => 'decimal:2',
        'NetGenerationAmount' => 'decimal:2',
        'CreditKWH' => 'decimal:2',
        'CreditAmount' => 'decimal:2',
        'NetMeteringSystemAmt' => 'decimal:2',
        'DAA_GRAM' => 'decimal:2',
        'DAA_ICERA' => 'decimal:2',
        'ACRM_TAFPPCA' => 'decimal:2',
        'ACRM_TAFxA' => 'decimal:2',
        'DAA_VAT' => 'decimal:2',
        'ACRM_VAT' => 'decimal:2',
        'NetMeteringNetAmount' => 'integer',
        'ReferenceNo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AccountNumber' => 'required|string|max:20',
        'PowerPreviousReading' => 'nullable|numeric',
        'PowerPresentReading' => 'nullable|numeric',
        'DemandPreviousReading' => 'nullable|numeric',
        'DemandPresentReading' => 'nullable|numeric',
        'AdditionalKWH' => 'nullable|numeric',
        'AdditionalKWDemand' => 'nullable|numeric',
        'PowerKWH' => 'nullable|numeric',
        'KWHAmount' => 'nullable|integer',
        'DemandKW' => 'nullable|numeric',
        'KWAmount' => 'nullable|integer',
        'Charges' => 'nullable|integer',
        'Deductions' => 'nullable|integer',
        'NetAmount' => 'nullable|integer',
        'PowerRate' => 'nullable|integer',
        'DemandRate' => 'nullable|integer',
        'BillingDate' => 'nullable',
        'ServiceDateFrom' => 'nullable',
        'ServiceDateTo' => 'nullable',
        'DueDate' => 'nullable',
        'BillNumber' => 'nullable|string|max:10',
        'Remarks' => 'nullable|string|max:128',
        'AverageKWH' => 'nullable|numeric',
        'AverageKWDemand' => 'nullable|numeric',
        'CoreLoss' => 'nullable|numeric',
        'Meter' => 'nullable|integer',
        'PR' => 'nullable|integer',
        'SDW' => 'nullable|integer',
        'Others' => 'nullable|integer',
        'PPA' => 'nullable|integer',
        'PPAAmount' => 'nullable|integer',
        'BasicAmount' => 'nullable|integer',
        'PRADiscount' => 'nullable|integer',
        'PRAAmount' => 'nullable|integer',
        'PPCADiscount' => 'nullable|integer',
        'PPCAAmount' => 'nullable|integer',
        'UCAmount' => 'nullable|integer',
        'MeterNumber' => 'nullable|string|max:20',
        'ConsumerType' => 'nullable|string|max:20',
        'BillType' => 'nullable|string|max:10',
        'QCAmount' => 'nullable|integer',
        'EPAmount' => 'nullable|integer',
        'PCAmount' => 'nullable|integer',
        'LoanCondonation' => 'nullable|integer',
        'BillingPeriod' => 'nullable',
        'UnbundledTag' => 'nullable|boolean',
        'GenerationSystemAmt' => 'nullable|integer',
        'FBHCAmt' => 'nullable|integer',
        'FPCAAdjustmentAmt' => 'nullable|integer',
        'ForexAdjustmentAmt' => 'nullable|integer',
        'TransmissionDemandAmt' => 'nullable|integer',
        'TransmissionSystemAmt' => 'nullable|integer',
        'DistributionDemandAmt' => 'nullable|integer',
        'DistributionSystemAmt' => 'nullable|integer',
        'SupplyRetailCustomerAmt' => 'nullable|integer',
        'SupplySystemAmt' => 'nullable|integer',
        'MeteringRetailCustomerAmt' => 'nullable|integer',
        'MeteringSystemAmt' => 'nullable|integer',
        'SystemLossAmt' => 'nullable|integer',
        'CrossSubsidyCreditAmt' => 'nullable|integer',
        'MissionaryElectrificationAmt' => 'nullable|integer',
        'EnvironmentalAmt' => 'nullable|integer',
        'LifelineSubsidyAmt' => 'nullable|integer',
        'Item1' => 'nullable|integer',
        'Item2' => 'nullable|integer',
        'Item3' => 'nullable|integer',
        'Item4' => 'nullable|integer',
        'SeniorCitizenDiscount' => 'nullable|integer',
        'SeniorCitizenSubsidy' => 'nullable|integer',
        'UCMERefund' => 'nullable|integer',
        'NetPrevReading' => 'nullable|numeric',
        'NetPresReading' => 'nullable|numeric',
        'NetPowerKWH' => 'nullable|numeric',
        'NetGenerationAmount' => 'nullable|numeric',
        'CreditKWH' => 'nullable|numeric',
        'CreditAmount' => 'nullable|numeric',
        'NetMeteringSystemAmt' => 'nullable|numeric',
        'DAA_GRAM' => 'nullable|numeric',
        'DAA_ICERA' => 'nullable|numeric',
        'ACRM_TAFPPCA' => 'nullable|numeric',
        'ACRM_TAFxA' => 'nullable|numeric',
        'DAA_VAT' => 'nullable|numeric',
        'ACRM_VAT' => 'nullable|numeric',
        'NetMeteringNetAmount' => 'nullable|integer',
        'ReferenceNo' => 'nullable|string|max:30'
    ];

    
}
