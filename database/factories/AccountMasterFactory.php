<?php

namespace Database\Factories;

use App\Models\AccountMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rowguid' => $this->faker->word,
        'ComputeMode' => $this->faker->word,
        'RecordStatus' => $this->faker->word,
        'ChangeDate' => $this->faker->date('Y-m-d H:i:s'),
        'Area' => $this->faker->word,
        'Route' => $this->faker->word,
        'SequenceNumber' => $this->faker->randomDigitNotNull,
        'UniqueID' => $this->faker->word,
        'TemporaryStatus' => $this->faker->word,
        'Email' => $this->faker->word,
        'ContactNumber' => $this->faker->word,
        'MReader' => $this->faker->word,
        'AccountID' => $this->faker->word,
        'Item1' => $this->faker->word,
        'DepositORNumber' => $this->faker->word,
        'TINNo' => $this->faker->text,
        'cust_id' => $this->faker->word,
        'DateEntry' => $this->faker->date('Y-m-d H:i:s'),
        'Municipal' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'SApprovedBy' => $this->faker->word,
        'SDiscountStatus' => $this->faker->word,
        'SRemarks' => $this->faker->word,
        'BAPAECACode' => $this->faker->word,
        'BillDeposit' => $this->faker->randomDigitNotNull,
        'DepositDate' => $this->faker->date('Y-m-d H:i:s'),
        'TSFRental' => $this->faker->randomDigitNotNull,
        'Save5' => $this->faker->word,
        'SApplicationDate' => $this->faker->date('Y-m-d H:i:s'),
        'SDiscountExpiry' => $this->faker->date('Y-m-d H:i:s'),
        'SDateOfBirth' => $this->faker->date('Y-m-d H:i:s'),
        'SDocument' => $this->faker->word,
        'SOATag' => $this->faker->word,
        'OldRoute' => $this->faker->word,
        'UserName' => $this->faker->word,
        'DeletedDate' => $this->faker->date('Y-m-d H:i:s'),
        'GroupTag' => $this->faker->word,
        'SchoolTag' => $this->faker->word,
        'SDWLength' => $this->faker->word,
        'Feeder' => $this->faker->word,
        'CoreLoss' => $this->faker->randomDigitNotNull,
        'CoreLossKWHUpperLimit' => $this->faker->randomDigitNotNull,
        'ReadDate' => $this->faker->date('Y-m-d H:i:s'),
        'UnreadDate' => $this->faker->date('Y-m-d H:i:s'),
        'PrivilegeType' => $this->faker->word,
        'InstalledBy' => $this->faker->word,
        'InstallationType' => $this->faker->word,
        'RateGroup' => $this->faker->word,
        'DisconnectionDate' => $this->faker->date('Y-m-d H:i:s'),
        'Remarks' => $this->faker->word,
        'MeterNumber' => $this->faker->word,
        'MemberType' => $this->faker->word,
        'Transformer' => $this->faker->word,
        'Pole' => $this->faker->word,
        'ConnectionDate' => $this->faker->date('Y-m-d H:i:s'),
        'TurnOnNumber' => $this->faker->word,
        'CIFKey' => $this->faker->word,
        'ConsumerName' => $this->faker->word,
        'ConsumerAddress' => $this->faker->word,
        'ConsumerType' => $this->faker->word,
        'AccountStatus' => $this->faker->word,
        'BillingStatus' => $this->faker->word
        ];
    }
}
