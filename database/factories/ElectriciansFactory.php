<?php

namespace Database\Factories;

use App\Models\Electricians;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectriciansFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Electricians::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'IDNumber' => $this->faker->word,
        'Name' => $this->faker->word,
        'Address' => $this->faker->word,
        'ContactNumber' => $this->faker->word,
        'BankNumber' => $this->faker->word,
        'Bank' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
