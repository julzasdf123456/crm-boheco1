<?php

namespace Database\Factories;

use App\Models\UnbundledRatesExtension;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnbundledRatesExtensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UnbundledRatesExtension::class;

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
        'LCPerCustomer' => $this->faker->randomDigitNotNull,
        'Item2' => $this->faker->randomDigitNotNull,
        'Item3' => $this->faker->randomDigitNotNull,
        'Item4' => $this->faker->randomDigitNotNull,
        'Item11' => $this->faker->randomDigitNotNull,
        'Item12' => $this->faker->randomDigitNotNull,
        'Item13' => $this->faker->randomDigitNotNull,
        'Item5' => $this->faker->randomDigitNotNull,
        'Item6' => $this->faker->randomDigitNotNull,
        'Item7' => $this->faker->randomDigitNotNull,
        'Item8' => $this->faker->randomDigitNotNull,
        'Item9' => $this->faker->randomDigitNotNull,
        'Item10' => $this->faker->randomDigitNotNull
        ];
    }
}
