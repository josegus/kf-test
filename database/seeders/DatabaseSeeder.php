<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BuyerSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CoopSeeder::class);
        $this->call(PurchaseSeeder::class);
    }
}
