<?php

namespace App\Console\Commands;

use App\Models\Coop;
use Illuminate\Console\Command;

class CancelCoops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kf:cancel-coops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel coops that are not already canceled and has reached expiration date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $coops = Coop::toBeCancelToday()->get();

        foreach ($coops as $coop) {
            $coop->cancel();
        }

        $this->info("Coops canceled: {$coops->count()}");
    }
}
