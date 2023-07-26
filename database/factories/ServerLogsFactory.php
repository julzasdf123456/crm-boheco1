<?php

namespace Database\Factories;

use App\Models\ServerLogs;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerLogsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServerLogs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServerIpSource' => $this->faker->word,
        'ServerNameSource' => $this->faker->word,
        'Details' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
