<?php

namespace Database\Factories;

use App\Models\AccountMasterExtension;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountMasterExtensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountMasterExtension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'PoleCode' => $this->faker->word,
        'ServiceVoltage' => $this->faker->word,
        'Phase' => $this->faker->word,
        'PF' => $this->faker->word,
        'Phasing' => $this->faker->word,
        'SubstationID' => $this->faker->word,
        'SDWType' => $this->faker->word,
        'Item1' => $this->faker->word,
        'Item2' => $this->faker->word,
        'Item3' => $this->faker->word,
        'Item4' => $this->faker->word,
        'Item5' => $this->faker->word
        ];
    }
}
