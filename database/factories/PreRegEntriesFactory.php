<?php

namespace Database\Factories;

use App\Models\PreRegEntries;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreRegEntriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreRegEntries::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'Name' => $this->faker->word,
        'Year' => $this->faker->word,
        'RegisteredVenue' => $this->faker->word,
        'DateRegistered' => $this->faker->date('Y-m-d H:i:s'),
        'Status' => $this->faker->word,
        'RegistrationMedium' => $this->faker->word,
        'ContactNumber' => $this->faker->word,
        'Email' => $this->faker->word,
        'Signature' => $this->faker->text
        ];
    }
}
