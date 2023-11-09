<?php

namespace Database\Factories;

use App\Models\MiscellaneousApplications;
use Illuminate\Database\Eloquent\Factories\Factory;

class MiscellaneousApplicationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MiscellaneousApplications::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ConsumerName' => $this->faker->word,
        'Town' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Sitio' => $this->faker->word,
        'Application' => $this->faker->word,
        'Notes' => $this->faker->word,
        'Status' => $this->faker->word,
        'ServiceDropLength' => $this->faker->word,
        'TransformerLoad' => $this->faker->word,
        'TicketId' => $this->faker->word,
        'ServiceConnectionId' => $this->faker->word,
        'AccountNumber' => $this->faker->word,
        'UserId' => $this->faker->word,
        'TotalAmount' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word
        ];
    }
}
