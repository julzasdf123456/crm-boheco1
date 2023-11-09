<?php

namespace Database\Factories;

use App\Models\MiscellaneousPayments;
use Illuminate\Database\Eloquent\Factories\Factory;

class MiscellaneousPaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MiscellaneousPayments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->word,
        'MiscellaneousId' => $this->faker->word,
        'GLCode' => $this->faker->word,
        'Description' => $this->faker->word,
        'Amount' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
