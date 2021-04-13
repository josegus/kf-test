<?php

namespace Database\Seeders;

use App\Models\Coop;
use App\Models\Buyer;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PurchaseSeeder extends Seeder
{
    protected Collection $coops;

    protected Collection $buyers;

    public function __construct()
    {
        $this->coops = Coop::all();
        $this->buyers = Buyer::all();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = (int)$this->command->ask('Amount of purchases', 500);

        $coopId = $this->command->ask('Enter the coop ID where all purchases will be created for. Leave empty for randomly seeding');

        if ($coopId && Coop::find($coopId)) {
            $this->seedForSpecificCoop($coopId, $count);

            return;
        }

        // Otherwise, seed randomly for coops and buyers
        foreach (range(1, $count) as $i) {
            Purchase::factory(
                $this->attributes()
            )->create();
        }
    }

    protected function seedForSpecificCoop(int $coopId, int $count)
    {
        $attributes = [
            'coop_id' => $coopId,
        ];

        foreach (range(1, $count) as $i) {
            if ($this->buyers->isNotEmpty()) {
                $attributes['buyer_id'] = $this->buyers->random();
            }

            Purchase::factory($attributes)->create();
        }
    }

    protected function attributes()
    {
        if ($this->coops->isEmpty() || $this->buyers->isEmpty()) {
            return [];
        }

        return [
            'coop_id' => $this->coops->random(),
            'buyer_id' => $this->buyers->random(),
        ];
    }
}
