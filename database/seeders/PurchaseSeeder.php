<?php

namespace Database\Seeders;

use App\Models\Coop;
use App\Models\Buyer;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coops = Coop::all();
        $buyers = Buyer::all();

        $count = (int)$this->command->ask('Amount of purchases', 500);

        if ($coops->isNotEmpty()) {
            $coopId = $this->command->ask('Enter the coop ID where all purchases will be created for');
        }

        if ($coopId) {
            $this->seedForSpecificCoop($coopId, $count, $buyers);
            return;
        }

        // Otherwise, seed randomly for coops and buyers
        foreach (range(1, $count) as $i) {
            Purchase::factory(
                $this->attributes($coops, $buyers)
            )->create();
        }
    }

    protected function seedForSpecificCoop(int $coopId, int $count, Collection $buyers)
    {
        $attributes = [
            'coop_id' => $coopId,
        ];

        foreach (range(1, $count) as $i) {
            if ($buyers->isNotEmpty()) {
                $attributes['buyer_id'] = $buyers->random();
            }

            Purchase::factory($attributes)->create();
        }
    }

    protected function attributes($coops, $buyers)
    {
        if ($coops->isEmpty() || $buyers->isEmpty()) {
            return [];
        }

        return [
            'coop_id' => $coops->random(),
            'buyer_id' => $buyers->random(),
        ];
    }
}
