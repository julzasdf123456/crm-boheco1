<?php

namespace Database\Factories;

use App\Models\Meters;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meters::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'RecordStatus' => $this->faker->word,
        'ChangeDate' => $this->faker->date('Y-m-d H:i:s'),
        'MeterDigits' => $this->faker->word,
        'Multiplier' => $this->faker->randomDigitNotNull,
        'ChargingMode' => $this->faker->word,
        'DemandType' => $this->faker->word,
        'Make' => $this->faker->word,
        'SerialNumber' => $this->faker->word,
        'CalibrationDate' => $this->faker->date('Y-m-d H:i:s'),
        'MeterStatus' => $this->faker->word,
        'InitialReading' => $this->faker->randomDigitNotNull,
        'InitialDemand' => $this->faker->randomDigitNotNull,
        'Remarks' => $this->faker->word
        ];
    }
}
