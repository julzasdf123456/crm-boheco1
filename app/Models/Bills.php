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
 * @property float $KWHAmount
 * @property number $DemandKW
 * @property float $KWAmount
 * @property float $Charges
 * @property float $Deductions
 * @property float $NetAmount
 * @property float $PowerRate
 * @property float $DemandRate
 * @property string|\Carbon\Carbon $BillingDate
 * @property string|\Carbon\Carbon $ServiceDateFrom
 * @property string|\Carbon\Carbon $ServiceDateTo
 * @property string|\Carbon\Carbon $DueDate
 * @property string $BillNumber
 * @property string $Remarks
 * @property number $AverageKWH
 * @property number $AverageKWDemand
 * @property number $CoreLoss
 * @property float $Meter
 * @property float $PR
 * @property float $SDW
 * @property float $Others
 * @property float $PPA
 * @property float $PPAAmount
 * @property float $BasicAmount
 * @property float $PRADiscount
 * @property float $PRAAmount
 * @property float $PPCADiscount
 * @property float $PPCAAmount
 * @property float $UCAmount
 * @property string $MeterNumber
 * @property string $ConsumerType
 * @property string $BillType
 * @property float $QCAmount
 * @property float $EPAmount
 * @property float $PCAmount
 * @property float $LoanCondonation
 * @property string|\Carbon\Carbon $BillingPeriod
 * @property boolean $UnbundledTag
 * @property float $GenerationSystemAmt
 * @property float $FBHCAmt
 * @property float $FPCAAdjustmentAmt
 * @property float $ForexAdjustmentAmt
 * @property float $TransmissionDemandAmt
 * @property float $TransmissionSystemAmt
 * @property float $DistributionDemandAmt
 * @property float $DistributionSystemAmt
 * @property float $SupplyRetailCustomerAmt
 * @property float $SupplySystemAmt
 * @property float $MeteringRetailCustomerAmt
 * @property float $MeteringSystemAmt
 * @property float $SystemLossAmt
 * @property float $CrossSubsidyCreditAmt
 * @property float $MissionaryElectrificationAmt
 * @property float $EnvironmentalAmt
 * @property float $LifelineSubsidyAmt
 * @property float $Item1
 * @property float $Item2
 * @property float $Item3
 * @property float $Item4
 * @property float $SeniorCitizenDiscount
 * @property float $SeniorCitizenSubsidy
 * @property float $UCMERefund
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
 * @property float $NetMeteringNetAmount
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
        'KWHAmount' => 'float',
        'DemandKW' => 'float',
        'KWAmount' => 'float',
        'Charges' => 'float',
        'Deductions' => 'float',
        'NetAmount' => 'float',
        'PowerRate' => 'float',
        'DemandRate' => 'float',
        'BillingDate' => 'datetime',
        'ServiceDateFrom' => 'datetime',
        'ServiceDateTo' => 'datetime',
        'DueDate' => 'datetime',
        'BillNumber' => 'string',
        'Remarks' => 'string',
        'AverageKWH' => 'float',
        'AverageKWDemand' => 'float',
        'CoreLoss' => 'float',
        'Meter' => 'float',
        'PR' => 'float',
        'SDW' => 'float',
        'Others' => 'float',
        'PPA' => 'float',
        'PPAAmount' => 'float',
        'BasicAmount' => 'float',
        'PRADiscount' => 'float',
        'PRAAmount' => 'float',
        'PPCADiscount' => 'float',
        'PPCAAmount' => 'float',
        'UCAmount' => 'float',
        'MeterNumber' => 'string',
        'ConsumerType' => 'string',
        'BillType' => 'string',
        'QCAmount' => 'float',
        'EPAmount' => 'float',
        'PCAmount' => 'float',
        'LoanCondonation' => 'float',
        'BillingPeriod' => 'datetime',
        'UnbundledTag' => 'boolean',
        'GenerationSystemAmt' => 'float',
        'FBHCAmt' => 'float',
        'FPCAAdjustmentAmt' => 'float',
        'ForexAdjustmentAmt' => 'float',
        'TransmissionDemandAmt' => 'float',
        'TransmissionSystemAmt' => 'float',
        'DistributionDemandAmt' => 'float',
        'DistributionSystemAmt' => 'float',
        'SupplyRetailCustomerAmt' => 'float',
        'SupplySystemAmt' => 'float',
        'MeteringRetailCustomerAmt' => 'float',
        'MeteringSystemAmt' => 'float',
        'SystemLossAmt' => 'float',
        'CrossSubsidyCreditAmt' => 'float',
        'MissionaryElectrificationAmt' => 'float',
        'EnvironmentalAmt' => 'float',
        'LifelineSubsidyAmt' => 'float',
        'Item1' => 'float',
        'Item2' => 'float',
        'Item3' => 'float',
        'Item4' => 'float',
        'SeniorCitizenDiscount' => 'float',
        'SeniorCitizenSubsidy' => 'float',
        'UCMERefund' => 'float',
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
        'NetMeteringNetAmount' => 'float',
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
        'KWHAmount' => 'nullable|float',
        'DemandKW' => 'nullable|numeric',
        'KWAmount' => 'nullable|float',
        'Charges' => 'nullable|float',
        'Deductions' => 'nullable|float',
        'NetAmount' => 'nullable|float',
        'PowerRate' => 'nullable|float',
        'DemandRate' => 'nullable|float',
        'BillingDate' => 'nullable',
        'ServiceDateFrom' => 'nullable',
        'ServiceDateTo' => 'nullable',
        'DueDate' => 'nullable',
        'BillNumber' => 'nullable|string|max:10',
        'Remarks' => 'nullable|string|max:128',
        'AverageKWH' => 'nullable|numeric',
        'AverageKWDemand' => 'nullable|numeric',
        'CoreLoss' => 'nullable|numeric',
        'Meter' => 'nullable|float',
        'PR' => 'nullable|float',
        'SDW' => 'nullable|float',
        'Others' => 'nullable|float',
        'PPA' => 'nullable|float',
        'PPAAmount' => 'nullable|float',
        'BasicAmount' => 'nullable|float',
        'PRADiscount' => 'nullable|float',
        'PRAAmount' => 'nullable|float',
        'PPCADiscount' => 'nullable|float',
        'PPCAAmount' => 'nullable|float',
        'UCAmount' => 'nullable|float',
        'MeterNumber' => 'nullable|string|max:20',
        'ConsumerType' => 'nullable|string|max:20',
        'BillType' => 'nullable|string|max:10',
        'QCAmount' => 'nullable|float',
        'EPAmount' => 'nullable|float',
        'PCAmount' => 'nullable|float',
        'LoanCondonation' => 'nullable|float',
        'BillingPeriod' => 'nullable',
        'UnbundledTag' => 'nullable|boolean',
        'GenerationSystemAmt' => 'nullable|float',
        'FBHCAmt' => 'nullable|float',
        'FPCAAdjustmentAmt' => 'nullable|float',
        'ForexAdjustmentAmt' => 'nullable|float',
        'TransmissionDemandAmt' => 'nullable|float',
        'TransmissionSystemAmt' => 'nullable|float',
        'DistributionDemandAmt' => 'nullable|float',
        'DistributionSystemAmt' => 'nullable|float',
        'SupplyRetailCustomerAmt' => 'nullable|float',
        'SupplySystemAmt' => 'nullable|float',
        'MeteringRetailCustomerAmt' => 'nullable|float',
        'MeteringSystemAmt' => 'nullable|float',
        'SystemLossAmt' => 'nullable|float',
        'CrossSubsidyCreditAmt' => 'nullable|float',
        'MissionaryElectrificationAmt' => 'nullable|float',
        'EnvironmentalAmt' => 'nullable|float',
        'LifelineSubsidyAmt' => 'nullable|float',
        'Item1' => 'nullable|float',
        'Item2' => 'nullable|float',
        'Item3' => 'nullable|float',
        'Item4' => 'nullable|float',
        'SeniorCitizenDiscount' => 'nullable|float',
        'SeniorCitizenSubsidy' => 'nullable|float',
        'UCMERefund' => 'nullable|float',
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
        'NetMeteringNetAmount' => 'nullable|float',
        'ReferenceNo' => 'nullable|string|max:30'
    ];

}
