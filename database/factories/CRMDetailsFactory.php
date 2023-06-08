<?php

namespace Database\Factories;

use App\Models\CRMDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class CRMDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CRMDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ReferenceNo' => $this->faker->word,
        'Particular' => $this->faker->word,
        'GLCode' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'VAT' => $this->faker->word,
        'Total' => $this->faker->word
        ];
    }
}
