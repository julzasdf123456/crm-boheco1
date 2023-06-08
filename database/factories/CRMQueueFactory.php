<?php

namespace Database\Factories;

use App\Models\CRMQueue;
use Illuminate\Database\Eloquent\Factories\Factory;

class CRMQueueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CRMQueue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ConsumerName' => $this->faker->word,
        'ConsumerAddress' => $this->faker->word,
        'TransactionPurpose' => $this->faker->word,
        'Source' => $this->faker->word,
        'SourceId' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'VAT' => $this->faker->word,
        'Total' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
