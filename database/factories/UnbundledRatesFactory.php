<?php

namespace Database\Factories;

use App\Models\UnbundledRates;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnbundledRatesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UnbundledRates::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rowguid' => $this->faker->word,
        'ServicePeriodEnd' => $this->faker->date('Y-m-d H:i:s'),
        'Description' => $this->faker->word,
        'LifelineLevel' => $this->faker->randomDigitNotNull,
        'GenerationSystemCharge' => $this->faker->randomDigitNotNull,
        'FBHCCharge' => $this->faker->randomDigitNotNull,
        'ACRM_TAFPPCACharge' => $this->faker->word,
        'ACRM_TAFxACharge' => $this->faker->word,
        'UploadedBy' => $this->faker->word,
        'DateUploaded' => $this->faker->date('Y-m-d H:i:s'),
        'PPARefund' => $this->faker->randomDigitNotNull,
        'SeniorCitizenSubsidyCharge' => $this->faker->randomDigitNotNull,
        'ACRM_VAT' => $this->faker->word,
        'DAA_VAT' => $this->faker->word,
        'DAA_GRAMCharge' => $this->faker->word,
        'DAA_ICERACharge' => $this->faker->word,
        'MissionaryElectrificationCharge' => $this->faker->randomDigitNotNull,
        'EnvironmentalCharge' => $this->faker->randomDigitNotNull,
        'LifelineSubsidyCharge' => $this->faker->randomDigitNotNull,
        'LoanCondonationCharge' => $this->faker->randomDigitNotNull,
        'MandatoryRateReductionCharge' => $this->faker->randomDigitNotNull,
        'MCC' => $this->faker->randomDigitNotNull,
        'SupplyRetailCustomerCharge' => $this->faker->randomDigitNotNull,
        'SupplySystemCharge' => $this->faker->randomDigitNotNull,
        'MeteringRetailCustomerCharge' => $this->faker->randomDigitNotNull,
        'MeteringSystemCharge' => $this->faker->randomDigitNotNull,
        'SystemLossCharge' => $this->faker->randomDigitNotNull,
        'CrossSubsidyCreditCharge' => $this->faker->randomDigitNotNull,
        'FPCAAdjustmentCharge' => $this->faker->randomDigitNotNull,
        'ForexAdjustmentCharge' => $this->faker->randomDigitNotNull,
        'TransmissionDemandCharge' => $this->faker->randomDigitNotNull,
        'TransmissionSystemCharge' => $this->faker->randomDigitNotNull,
        'DistributionDemandCharge' => $this->faker->randomDigitNotNull,
        'DistributionSystemCharge' => $this->faker->randomDigitNotNull
        ];
    }
}
