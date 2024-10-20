<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Purchase;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),
            'designation' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'priceperunity' => $this->faker->randomFloat(2, 0, 999999999999999999.99),
            'subtotal' => $this->faker->randomFloat(2, 0, 999999999999999999.99),
        ];
    }
}
