<?php

namespace Database\Factories;

use App\Models\BillsExtension;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillsExtensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillsExtension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServicePeriodEnd' => $this->faker->date('Y-m-d H:i:s'),
        'GenerationVAT' => $this->faker->randomDigitNotNull,
        'TransmissionVAT' => $this->faker->randomDigitNotNull,
        'SLVAT' => $this->faker->randomDigitNotNull,
        'DistributionVAT' => $this->faker->randomDigitNotNull,
        'OthersVAT' => $this->faker->randomDigitNotNull,
        'Item5' => $this->faker->randomDigitNotNull,
        'Item6' => $this->faker->randomDigitNotNull,
        'Item7' => $this->faker->randomDigitNotNull,
        'Item8' => $this->faker->word,
        'Item9' => $this->faker->randomDigitNotNull,
        'Item10' => $this->faker->randomDigitNotNull,
        'Item11' => $this->faker->randomDigitNotNull,
        'Item12' => $this->faker->randomDigitNotNull,
        'Item13' => $this->faker->randomDigitNotNull,
        'Item14' => $this->faker->randomDigitNotNull,
        'Item15' => $this->faker->randomDigitNotNull,
        'Item16' => $this->faker->randomDigitNotNull,
        'Item17' => $this->faker->randomDigitNotNull,
        'Item18' => $this->faker->randomDigitNotNull,
        'Item19' => $this->faker->randomDigitNotNull,
        'Item20' => $this->faker->randomDigitNotNull,
        'Item21' => $this->faker->randomDigitNotNull,
        'Item22' => $this->faker->randomDigitNotNull,
        'Item23' => $this->faker->randomDigitNotNull,
        'Item24' => $this->faker->randomDigitNotNull
        ];
    }
}
