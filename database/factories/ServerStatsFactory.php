<?php

namespace Database\Factories;

use App\Models\ServerStats;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerStatsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServerStats::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServerId' => $this->faker->word,
        'CpuPercentage' => $this->faker->word,
        'MemoryPercentage' => $this->faker->word,
        'DiskPercentage' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'TotalMemory' => $this->faker->word
        ];
    }
}
