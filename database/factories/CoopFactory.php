<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Coop;
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
        // status: draft, canceled
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
                'expiration_date' => now()->subWeek(),
                'canceled' => true,
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
                'status' => 'draft',
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
                'status' => 'canceled',
                'expiration_date' => now()->subWeek(),
            ];
        });
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        // Doesn't work
        return $this->afterCreating(function (Coop $coop) {
            Coop::flushEventListeners();
        });
    }
}
