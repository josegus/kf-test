<?php

namespace Database\Seeders;

use App\Models\Coop;
use App\Models\Buyer;
use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = (int)$this->command->ask('Amount of purchases', 500);

        $coops = Coop::all();
        $buyers = Buyer::all();

        foreach (range(1, $count) as $i) {
            Purchase::factory(
                $this->attributes($coops, $buyers)
            )->create();
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
