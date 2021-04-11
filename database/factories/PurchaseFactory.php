<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\Coop;
use App\Models\Package;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'coop_id' => Coop::factory(),
            'buyer_id' => Buyer::factory(),
            'amount' => $this->faker->randomFloat($decimals = 2, $min = 10, $max = 5000),
            'package_quantity' => $this->faker->numberBetween(1, 1000),
            //'package_id' => Package::factory(),
            'package_id' => $this->faker->numberBetween(1, 10),
            'banking_customer_token' => Str::random(),
        ];
    }
}
