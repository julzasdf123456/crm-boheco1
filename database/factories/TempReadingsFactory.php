<?php

namespace Database\Factories;

use App\Models\TempReadings;
use Illuminate\Database\Eloquent\Factories\Factory;

class TempReadingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TempReadings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServicePeriodEnd' => $this->faker->date('Y-m-d H:i:s'),
        'AccountNumber' => $this->faker->word,
        'Route' => $this->faker->word,
        'SequenceNumber' => $this->faker->randomDigitNotNull,
        'ConsumerName' => $this->faker->word,
        'ConsumerAddress' => $this->faker->word,
        'MeterNumber' => $this->faker->word,
        'PreviousReading2' => $this->faker->word,
        'PreviousReading1' => $this->faker->word,
        'PreviousReading' => $this->faker->word,
        'ReadingDate' => $this->faker->date('Y-m-d H:i:s'),
        'ReadBy' => $this->faker->word,
        'PowerReadings' => $this->faker->word,
        'DemandReadings' => $this->faker->randomDigitNotNull,
        'FieldFindings' => $this->faker->word,
        'MissCodes' => $this->faker->word,
        'Remarks' => $this->faker->word,
        'UpdateStatus' => $this->faker->word,
        'ConsumerType' => $this->faker->word,
        'AccountStatus' => $this->faker->word,
        'ShortAccountNumber' => $this->faker->word,
        'Multiplier' => $this->faker->randomDigitNotNull,
        'MeterDigits' => $this->faker->word,
        'Coreloss' => $this->faker->randomDigitNotNull,
        'CorelossKWHLimit' => $this->faker->randomDigitNotNull,
        'AdditionalKWH' => $this->faker->randomDigitNotNull,
        'TSFRental' => $this->faker->randomDigitNotNull,
        'SchoolTag' => $this->faker->word
        ];
    }
}
