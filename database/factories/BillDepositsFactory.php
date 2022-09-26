<?php

namespace Database\Factories;

use App\Models\BillDeposits;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillDepositsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillDeposits::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'Load' => $this->faker->word,
        'PowerFactor' => $this->faker->word,
        'DemandFactor' => $this->faker->word,
        'Hours' => $this->faker->word,
        'AverageRate' => $this->faker->word,
        'AverageTransmission' => $this->faker->word,
        'AverageDemand' => $this->faker->word,
        'BillDepositAmount' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
