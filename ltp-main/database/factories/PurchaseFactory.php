<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bank;
use App\Models\March;
use App\Models\Purchase;
use App\Models\Supplier;

class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bank_id' => Bank::factory(),
            'supplier_id' => Supplier::factory(),
            'marche_id' => March::factory(),
            'total' => $this->faker->randomFloat(2, 0, 999999999999999999.99),
        ];
    }
}
