<?php

namespace Database\Factories;

use App\Models\Buyer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BuyerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Buyer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'refund_pref' => $this->faker->randomElement(Buyer::refundPreferences()),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function prefersCreditRefund()
    {
        return $this->state(function (array $attributes) {
            return [
                'refund_pref' => 'credit',
            ];
        });
    }

    public function prefersCCRefund()
    {
        return $this->state(function (array $attributes) {
            return [
                'refund_pref' => 'cc',
            ];
        });
    }
}
