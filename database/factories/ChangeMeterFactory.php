<?php

namespace Database\Factories;

use App\Models\ChangeMeter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChangeMeterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChangeMeter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'ChangeDate' => $this->faker->date('Y-m-d H:i:s'),
        'OldMeter' => $this->faker->word,
        'NewMeter' => $this->faker->word,
        'PullOutReading' => $this->faker->randomDigitNotNull,
        'ReplaceBy' => $this->faker->word,
        'Remarks' => $this->faker->word
        ];
    }
}
