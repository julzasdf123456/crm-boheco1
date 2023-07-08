<?php

namespace Database\Factories;

use App\Models\DisconnectionSchedules;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisconnectionSchedulesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisconnectionSchedules::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'DisconnectorName' => $this->faker->word,
        'DisconnectorId' => $this->faker->word,
        'Day' => $this->faker->word,
        'ServicePeriodEnd' => $this->faker->word,
        'Routes' => $this->faker->word,
        'SequenceFrom' => $this->faker->word,
        'SequenceTo' => $this->faker->word,
        'Status' => $this->faker->word,
        'DatetimeDownloaded' => $this->faker->date('Y-m-d H:i:s'),
        'PhoneModel' => $this->faker->word,
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
