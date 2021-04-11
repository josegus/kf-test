<?php

namespace Database\Factories;

use App\Models\Coop;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // status: draft, approved, canceled
        return [
            'brand_id' => Brand::factory(),
            'name' => $this->faker->sentence(),
            'expiration_date' => now()->addWeeks(2),
            'goal' => $this->faker->randomFloat(2, 1000, 1000000),
            'status' => 'draft',
        ];
    }

    /**
     * Indicates the coop has an expired date.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expiration_date' => now()->subWeek()
            ];
        });
    }

    /**
     * Indicates the coop is in draft status (waiting for approval).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft'
            ];
        });
    }

    /**
     * Indicates the coop has been approved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved'
            ];
        });
    }

    /**
     * Indicates the coop has been canceled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function canceled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'canceled'
            ];
        });
    }
}
