<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\Purchase;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'numero' => $this->faker->word,
            'purchase_id' => Purchase::factory(),
            'echecance_date' => $this->faker->date(),
            'type' => $this->faker->randomElement(["espece","cheque","virement","versement","effet"]),
            'status' => $this->faker->randomElement(["paid","pending"]),
            'proof_file' => $this->faker->text,
        ];
    }
}
