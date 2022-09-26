<?php

namespace App\Repositories;

use App\Models\UnbundledRates;
use App\Repositories\BaseRepository;

/**
 * Class UnbundledRatesRepository
 * @package App\Repositories
 * @version August 4, 2022, 8:25 am PST
*/

class UnbundledRatesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rowguid',
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UnbundledRates::class;
    }
}
