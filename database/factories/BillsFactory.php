<?php

namespace Database\Factories;

use App\Models\Bills;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bills::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'PowerPreviousReading' => $this->faker->word,
        'PowerPresentReading' => $this->faker->word,
        'DemandPreviousReading' => $this->faker->randomDigitNotNull,
        'DemandPresentReading' => $this->faker->randomDigitNotNull,
        'AdditionalKWH' => $this->faker->randomDigitNotNull,
        'AdditionalKWDemand' => $this->faker->randomDigitNotNull,
        'PowerKWH' => $this->faker->word,
        'KWHAmount' => $this->faker->randomDigitNotNull,
        'DemandKW' => $this->faker->randomDigitNotNull,
        'KWAmount' => $this->faker->randomDigitNotNull,
        'Charges' => $this->faker->randomDigitNotNull,
        'Deductions' => $this->faker->randomDigitNotNull,
        'NetAmount' => $this->faker->randomDigitNotNull,
        'PowerRate' => $this->faker->randomDigitNotNull,
        'DemandRate' => $this->faker->randomDigitNotNull,
        'BillingDate' => $this->faker->date('Y-m-d H:i:s'),
        'ServiceDateFrom' => $this->faker->date('Y-m-d H:i:s'),
        'ServiceDateTo' => $this->faker->date('Y-m-d H:i:s'),
        'DueDate' => $this->faker->date('Y-m-d H:i:s'),
        'BillNumber' => $this->faker->word,
        'Remarks' => $this->faker->word,
        'AverageKWH' => $this->faker->randomDigitNotNull,
        'AverageKWDemand' => $this->faker->randomDigitNotNull,
        'CoreLoss' => $this->faker->randomDigitNotNull,
        'Meter' => $this->faker->randomDigitNotNull,
        'PR' => $this->faker->randomDigitNotNull,
        'SDW' => $this->faker->randomDigitNotNull,
        'Others' => $this->faker->randomDigitNotNull,
        'PPA' => $this->faker->randomDigitNotNull,
        'PPAAmount' => $this->faker->randomDigitNotNull,
        'BasicAmount' => $this->faker->randomDigitNotNull,
        'PRADiscount' => $this->faker->randomDigitNotNull,
        'PRAAmount' => $this->faker->randomDigitNotNull,
        'PPCADiscount' => $this->faker->randomDigitNotNull,
        'PPCAAmount' => $this->faker->randomDigitNotNull,
        'UCAmount' => $this->faker->randomDigitNotNull,
        'MeterNumber' => $this->faker->word,
        'ConsumerType' => $this->faker->word,
        'BillType' => $this->faker->word,
        'QCAmount' => $this->faker->randomDigitNotNull,
        'EPAmount' => $this->faker->randomDigitNotNull,
        'PCAmount' => $this->faker->randomDigitNotNull,
        'LoanCondonation' => $this->faker->randomDigitNotNull,
        'BillingPeriod' => $this->faker->date('Y-m-d H:i:s'),
        'UnbundledTag' => $this->faker->word,
        'GenerationSystemAmt' => $this->faker->randomDigitNotNull,
        'FBHCAmt' => $this->faker->randomDigitNotNull,
        'FPCAAdjustmentAmt' => $this->faker->randomDigitNotNull,
        'ForexAdjustmentAmt' => $this->faker->randomDigitNotNull,
        'TransmissionDemandAmt' => $this->faker->randomDigitNotNull,
        'TransmissionSystemAmt' => $this->faker->randomDigitNotNull,
        'DistributionDemandAmt' => $this->faker->randomDigitNotNull,
        'DistributionSystemAmt' => $this->faker->randomDigitNotNull,
        'SupplyRetailCustomerAmt' => $this->faker->randomDigitNotNull,
        'SupplySystemAmt' => $this->faker->randomDigitNotNull,
        'MeteringRetailCustomerAmt' => $this->faker->randomDigitNotNull,
        'MeteringSystemAmt' => $this->faker->randomDigitNotNull,
        'SystemLossAmt' => $this->faker->randomDigitNotNull,
        'CrossSubsidyCreditAmt' => $this->faker->randomDigitNotNull,
        'MissionaryElectrificationAmt' => $this->faker->randomDigitNotNull,
        'EnvironmentalAmt' => $this->faker->randomDigitNotNull,
        'LifelineSubsidyAmt' => $this->faker->randomDigitNotNull,
        'Item1' => $this->faker->randomDigitNotNull,
        'Item2' => $this->faker->randomDigitNotNull,
        'Item3' => $this->faker->randomDigitNotNull,
        'Item4' => $this->faker->randomDigitNotNull,
        'SeniorCitizenDiscount' => $this->faker->randomDigitNotNull,
        'SeniorCitizenSubsidy' => $this->faker->randomDigitNotNull,
        'UCMERefund' => $this->faker->randomDigitNotNull,
        'NetPrevReading' => $this->faker->word,
        'NetPresReading' => $this->faker->word,
        'NetPowerKWH' => $this->faker->word,
        'NetGenerationAmount' => $this->faker->word,
        'CreditKWH' => $this->faker->word,
        'CreditAmount' => $this->faker->word,
        'NetMeteringSystemAmt' => $this->faker->word,
        'DAA_GRAM' => $this->faker->word,
        'DAA_ICERA' => $this->faker->word,
        'ACRM_TAFPPCA' => $this->faker->word,
        'ACRM_TAFxA' => $this->faker->word,
        'DAA_VAT' => $this->faker->word,
        'ACRM_VAT' => $this->faker->word,
        'NetMeteringNetAmount' => $this->faker->randomDigitNotNull,
        'ReferenceNo' => $this->faker->word
        ];
    }
}
