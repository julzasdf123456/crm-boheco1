<?php

namespace Database\Factories;

use App\Models\Servers;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Servers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServerName' => $this->faker->word,
        'ServerIp' => $this->faker->word,
        'Status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
