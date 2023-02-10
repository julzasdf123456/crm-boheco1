<?php

namespace Database\Factories;

use App\Models\PaidBills;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaidBillsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaidBills::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'BillNumber' => $this->faker->word,
        'ServicePeriodEnd' => $this->faker->date('Y-m-d H:i:s'),
        'Power' => $this->faker->randomDigitNotNull,
        'Meter' => $this->faker->randomDigitNotNull,
        'PR' => $this->faker->randomDigitNotNull,
        'Others' => $this->faker->randomDigitNotNull,
        'NetAmount' => $this->faker->randomDigitNotNull,
        'PaymentType' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'Teller' => $this->faker->word,
        'DCRNumber' => $this->faker->word,
        'PostingDate' => $this->faker->date('Y-m-d H:i:s'),
        'PostingSequence' => $this->faker->randomDigitNotNull,
        'PromptPayment' => $this->faker->randomDigitNotNull,
        'Surcharge' => $this->faker->randomDigitNotNull,
        'SLAdjustment' => $this->faker->randomDigitNotNull,
        'OtherDeduction' => $this->faker->randomDigitNotNull,
        'ORDate' => $this->faker->date('Y-m-d H:i:s'),
        'MDRefund' => $this->faker->randomDigitNotNull,
        'Form2306' => $this->faker->word,
        'Form2307' => $this->faker->word,
        'Amount2306' => $this->faker->randomDigitNotNull,
        'Amount2307' => $this->faker->randomDigitNotNull,
        'ServiceFee' => $this->faker->randomDigitNotNull,
        'Others1' => $this->faker->randomDigitNotNull,
        'Others2' => $this->faker->randomDigitNotNull
        ];
    }
}
