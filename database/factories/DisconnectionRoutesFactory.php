<?php

namespace Database\Factories;

use App\Models\DisconnectionRoutes;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisconnectionRoutesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisconnectionRoutes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ScheduleId' => $this->faker->word,
        'Route' => $this->faker->word,
        'SequenceFrom' => $this->faker->word,
        'SequenceTo' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
