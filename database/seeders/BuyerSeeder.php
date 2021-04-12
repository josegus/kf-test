<?php

namespace Database\Seeders;

use App\Models\Buyer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* if (App::runningInConsole()) {
            $this->command->alert('is running in console');
        }
        App::runningUnitTests(); */

        $count = (int)$this->command->ask('Amount of buyers', 10);

        Buyer::factory()->count($count)->create();
    }
}
