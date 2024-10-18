<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Supplier;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'telephone' => $this->faker->word,
            'adresse' => $this->faker->word,
            'bank_code' => $this->faker->word,
            'bank_name' => $this->faker->word,
            'bank_rib' => $this->faker->word,
        ];
    }
}
