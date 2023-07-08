<?php

namespace Database\Factories;

use App\Models\DisconnectionData;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisconnectionDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisconnectionData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ScheduleId' => $this->faker->word,
        'DisconnectorName' => $this->faker->word,
        'UserId' => $this->faker->word,
        'AccountNumber' => $this->faker->word,
        'ServicePeriodEnd' => $this->faker->word,
        'AccountCoordinates' => $this->faker->word,
        'Latitude' => $this->faker->word,
        'Longitude' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'NetAmount' => $this->faker->word,
        'Surcharge' => $this->faker->word,
        'ServiceFee' => $this->faker->word,
        'Others' => $this->faker->word,
        'PaidAmount' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
