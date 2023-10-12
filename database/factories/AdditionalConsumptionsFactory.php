<?php

namespace Database\Factories;

use App\Models\AdditionalConsumptions;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionalConsumptionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdditionalConsumptions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'AdditionalKWH' => $this->faker->randomDigitNotNull,
        'AdditionalKW' => $this->faker->randomDigitNotNull,
        'Remarks' => $this->faker->word
        ];
    }
}
