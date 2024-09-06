<?php

namespace Database\Factories;

use App\Models\BillsReadings;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillsReadingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillsReadings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'ReadingDate' => $this->faker->date('Y-m-d H:i:s'),
        'ReadBy' => $this->faker->word,
        'PowerReadings' => $this->faker->word,
        'DemandReadings' => $this->faker->randomDigitNotNull,
        'FieldFindings' => $this->faker->word,
        'MissCodes' => $this->faker->word,
        'Remarks' => $this->faker->word
        ];
    }
}
