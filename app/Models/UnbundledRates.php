<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\UnbundledRatesExtension;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * Class UnbundledRates
 * @package App\Models
 * @version August 4, 2022, 8:25 am PST
 *
 * @property string $rowguid
 * @property string|\Carbon\Carbon $ServicePeriodEnd
 * @property string $Description
 * @property integer $LifelineLevel
 * @property integer $GenerationSystemCharge
 * @property integer $FBHCCharge
 * @property number $ACRM_TAFPPCACharge
 * @property number $ACRM_TAFxACharge
 * @property string $UploadedBy
 * @property string|\Carbon\Carbon $DateUploaded
 * @property integer $PPARefund
 * @property integer $SeniorCitizenSubsidyCharge
 * @property number $ACRM_VAT
 * @property number $DAA_VAT
 * @property number $DAA_GRAMCharge
 * @property number $DAA_ICERACharge
 * @property integer $MissionaryElectrificationCharge
 * @property integer $EnvironmentalCharge
 * @property integer $LifelineSubsidyCharge
 * @property integer $LoanCondonationCharge
 * @property integer $MandatoryRateReductionCharge
 * @property integer $MCC
 * @property integer $SupplyRetailCustomerCharge
 * @property integer $SupplySystemCharge
 * @property integer $MeteringRetailCustomerCharge
 * @property integer $MeteringSystemCharge
 * @property integer $SystemLossCharge
 * @property integer $CrossSubsidyCreditCharge
 * @property integer $FPCAAdjustmentCharge
 * @property integer $ForexAdjustmentCharge
 * @property integer $TransmissionDemandCharge
 * @property integer $TransmissionSystemCharge
 * @property integer $DistributionDemandCharge
 * @property integer $DistributionSystemCharge
 */
class UnbundledRates extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'UnbundledRates';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = 'rowguid';

    public $incrementing = false;

    public $fillable = [
        'ServicePeriodEnd',
        'Description',
        'LifelineLevel',
        'GenerationSystemCharge',
        'FBHCCharge',
        'ACRM_TAFPPCACharge',
        'ACRM_TAFxACharge',
        'UploadedBy',
        'DateUploaded',
        'PPARefund',
        'SeniorCitizenSubsidyCharge',
        'ACRM_VAT',
        'DAA_VAT',
        'DAA_GRAMCharge',
        'DAA_ICERACharge',
        'MissionaryElectrificationCharge',
        'EnvironmentalCharge',
        'LifelineSubsidyCharge',
        'LoanCondonationCharge',
        'MandatoryRateReductionCharge',
        'MCC',
        'SupplyRetailCustomerCharge',
        'SupplySystemCharge',
        'MeteringRetailCustomerCharge',
        'MeteringSystemCharge',
        'SystemLossCharge',
        'CrossSubsidyCreditCharge',
        'FPCAAdjustmentCharge',
        'ForexAdjustmentCharge',
        'TransmissionDemandCharge',
        'TransmissionSystemCharge',
        'DistributionDemandCharge',
        'DistributionSystemCharge'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rowguid' => 'string',
        'ConsumerType' => 'string',
        'ServicePeriodEnd' => 'datetime',
        'Description' => 'string',
        'LifelineLevel' => 'integer',
        'GenerationSystemCharge' => 'integer',
        'FBHCCharge' => 'integer',
        'ACRM_TAFPPCACharge' => 'decimal:6',
        'ACRM_TAFxACharge' => 'decimal:6',
        'UploadedBy' => 'string',
        'DateUploaded' => 'datetime',
        'PPARefund' => 'integer',
        'SeniorCitizenSubsidyCharge' => 'integer',
        'ACRM_VAT' => 'decimal:6',
        'DAA_VAT' => 'decimal:6',
        'DAA_GRAMCharge' => 'decimal:6',
        'DAA_ICERACharge' => 'decimal:6',
        'MissionaryElectrificationCharge' => 'integer',
        'EnvironmentalCharge' => 'integer',
        'LifelineSubsidyCharge' => 'integer',
        'LoanCondonationCharge' => 'integer',
        'MandatoryRateReductionCharge' => 'integer',
        'MCC' => 'integer',
        'SupplyRetailCustomerCharge' => 'integer',
        'SupplySystemCharge' => 'integer',
        'MeteringRetailCustomerCharge' => 'integer',
        'MeteringSystemCharge' => 'integer',
        'SystemLossCharge' => 'integer',
        'CrossSubsidyCreditCharge' => 'integer',
        'FPCAAdjustmentCharge' => 'integer',
        'ForexAdjustmentCharge' => 'integer',
        'TransmissionDemandCharge' => 'integer',
        'TransmissionSystemCharge' => 'integer',
        'DistributionDemandCharge' => 'integer',
        'DistributionSystemCharge' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rowguid' => 'required|string',
        'ServicePeriodEnd' => 'required',
        'Description' => 'nullable|string|max:50',
        'LifelineLevel' => 'nullable|integer',
        'GenerationSystemCharge' => 'nullable|integer',
        'FBHCCharge' => 'nullable|integer',
        'ACRM_TAFPPCACharge' => 'nullable|numeric',
        'ACRM_TAFxACharge' => 'nullable|numeric',
        'UploadedBy' => 'nullable|string|max:30',
        'DateUploaded' => 'nullable',
        'PPARefund' => 'nullable|integer',
        'SeniorCitizenSubsidyCharge' => 'nullable|integer',
        'ACRM_VAT' => 'nullable|numeric',
        'DAA_VAT' => 'nullable|numeric',
        'DAA_GRAMCharge' => 'nullable|numeric',
        'DAA_ICERACharge' => 'nullable|numeric',
        'MissionaryElectrificationCharge' => 'nullable|integer',
        'EnvironmentalCharge' => 'nullable|integer',
        'LifelineSubsidyCharge' => 'nullable|integer',
        'LoanCondonationCharge' => 'nullable|integer',
        'MandatoryRateReductionCharge' => 'nullable|integer',
        'MCC' => 'nullable|integer',
        'SupplyRetailCustomerCharge' => 'nullable|integer',
        'SupplySystemCharge' => 'nullable|integer',
        'MeteringRetailCustomerCharge' => 'nullable|integer',
        'MeteringSystemCharge' => 'nullable|integer',
        'SystemLossCharge' => 'nullable|integer',
        'CrossSubsidyCreditCharge' => 'nullable|integer',
        'FPCAAdjustmentCharge' => 'nullable|integer',
        'ForexAdjustmentCharge' => 'nullable|integer',
        'TransmissionDemandCharge' => 'nullable|integer',
        'TransmissionSystemCharge' => 'nullable|integer',
        'DistributionDemandCharge' => 'nullable|integer',
        'DistributionSystemCharge' => 'nullable|integer'
    ];

    public static function getOneYearAverageRate($alias) {
        $rates = DB::connection('sqlsrvbilling')->table('UnbundledRates')
            ->leftJoin('UnbundledRatesExtension', function($join){
                $join->on('UnbundledRates.ConsumerType', '=', 'UnbundledRatesExtension.ConsumerType');
                $join->on('UnbundledRates.ServicePeriodEnd', '=', 'UnbundledRatesExtension.ServicePeriodEnd'); 
            })
            ->where('UnbundledRates.ConsumerType', $alias)
            ->whereNotNull('UnbundledRatesExtension.Item12')
            ->select(
                'UnbundledRatesExtension.Item12',
                // 'UnbundledRatesExtension.Item2',
                // 'UnbundledRatesExtension.Item3',
                // 'UnbundledRatesExtension.Item4',
                // 'UnbundledRatesExtension.Item5',
                // 'UnbundledRatesExtension.Item6',
                // 'UnbundledRatesExtension.Item7',
                // 'UnbundledRatesExtension.Item8',
                // 'UnbundledRatesExtension.Item9',
                // 'UnbundledRatesExtension.Item10',
                // 'UnbundledRatesExtension.Item11',
                // 'UnbundledRatesExtension.Item12',
                // 'UnbundledRatesExtension.Item13',
                // 'UnbundledRates.GenerationSystemCharge',
                // 'UnbundledRates.FBHCCharge',
                // 'UnbundledRates.FPCAAdjustmentCharge',
                // 'UnbundledRates.ForexAdjustmentCharge',
                // 'UnbundledRates.TransmissionSystemCharge',
                // 'UnbundledRates.DistributionSystemCharge',
                // 'UnbundledRates.SupplySystemCharge',
                // 'UnbundledRates.MeteringSystemCharge',
                // 'UnbundledRates.SystemLossCharge',
                // 'UnbundledRates.CrossSubsidyCreditCharge',
                // 'UnbundledRates.MissionaryElectrificationCharge',
                // 'UnbundledRates.EnvironmentalCharge',
                // 'UnbundledRates.LifelineSubsidyCharge',
                // 'UnbundledRates.LoanCondonationCharge',
                // 'UnbundledRates.MandatoryRateReductionCharge',
                // 'UnbundledRates.MCC',
                // 'UnbundledRates.PPARefund',
                // 'UnbundledRates.SeniorCitizenSubsidyCharge',
                // 'UnbundledRates.ACRM_VAT',
                // 'UnbundledRates.DAA_VAT',
                // 'UnbundledRates.DAA_GRAMCharge',
                // 'UnbundledRates.DAA_ICERACharge',
                // 'UnbundledRates.ACRM_TAFPPCACharge',
                // 'UnbundledRates.ACRM_TAFxACharge'
            )
            ->orderByDesc('UnbundledRates.ServicePeriodEnd')
            ->limit(12)
            ->get();

        $ave = 0;        
        foreach($rates as $item) {
            $total = floatval($item->Item12);
                // floatval($item->Item3) +
                // floatval($item->Item4) +
                // floatval($item->Item5) +
                // floatval($item->Item6) +
                // floatval($item->Item7) +
                // // floatval($item->Item8) +
                // floatval($item->Item9) +
                // floatval($item->Item10) +
                // floatval($item->Item11) +
                // floatval($item->Item12) +
                // floatval($item->Item13) +
                // floatval($item->GenerationSystemCharge) +
                // floatval($item->FBHCCharge) +
                // floatval($item->FPCAAdjustmentCharge) +
                // floatval($item->ForexAdjustmentCharge) +
                // floatval($item->TransmissionSystemCharge) +
                // floatval($item->DistributionSystemCharge) +
                // floatval($item->SupplySystemCharge) +
                // floatval($item->MeteringSystemCharge) +
                // floatval($item->SystemLossCharge) +
                // floatval($item->CrossSubsidyCreditCharge) +
                // floatval($item->MissionaryElectrificationCharge) +
                // floatval($item->EnvironmentalCharge) +
                // floatval($item->LifelineSubsidyCharge) +
                // floatval($item->LoanCondonationCharge) +
                // floatval($item->MandatoryRateReductionCharge) +
                // floatval($item->MCC) +
                // floatval($item->PPARefund) +
                // floatval($item->SeniorCitizenSubsidyCharge) +
                // floatval($item->ACRM_VAT) +
                // floatval($item->DAA_VAT) +
                // floatval($item->DAA_GRAMCharge) +
                // floatval($item->DAA_ICERACharge) +
                // floatval($item->ACRM_TAFPPCACharge) +
                // floatval($item->ACRM_TAFxACharge);

            $ave += $total;
        }

        return round($ave/count($rates), 4);
    }

    public static function getOneYearAverageTransAndDist($alias) {
        $rates = DB::connection('sqlsrvbilling')->table('UnbundledRates')
            ->where('UnbundledRates.ConsumerType', $alias)
            ->select(
                'UnbundledRates.TransmissionDemandCharge',
                'UnbundledRates.DistributionDemandCharge'
            )
            ->orderByDesc('UnbundledRates.ServicePeriodEnd')
            ->limit(12)
            ->get();

        $ave = 0;        
        foreach($rates as $item) {
            $total = floatval($item->TransmissionDemandCharge) +
                floatval($item->DistributionDemandCharge);

            $ave += $total;
        }

        return round($ave/count($rates), 4);
    }
}
