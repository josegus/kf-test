<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Coop;
use Illuminate\Database\Seeder;

class CoopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = (int)$this->command->ask('Amount of coops', 100);

        $brands = Brand::all();

        // Wee need to disable events, because CoopCreating will force the status to be "draft"
        Coop::withoutEvents(function () use ($count, $brands) {
            $brands->isEmpty()
                ? $this->seedWithOutBrand($count)
                : $this->seedWithBrand($count, $brands);
        });
    }

    protected function seedWithOutBrand(int $count)
    {
        foreach (range(1, $count) as $i) {
            Coop::factory()->create();
        }
    }

    protected function seedWithBrand(int $count, $brands)
    {
        foreach (range(1, $count) as $i) {
            Coop::factory([
                'brand_id' => $brands->random(),
            ])->create();
        }
    }
}
