<?php

namespace App\Console\Commands;

use App\Models\Coop;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelCoopsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kf:cancel-coops
                            {--coop= : The ID of the coop to be canceled (by force)}
                            {--date= : Cancel all coops that expires in this date (Y-m-d)}';

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
        if ($coopId = $this->option('coop')) {
            $this->cancelSingleCoop($coopId);

            return;
        }

        if ($date = $this->option('date')) {
            $this->cancelCoopsByExpirationDate($date);
        }

        $this->cancelCoopsExpiringToday();
    }

    protected function cancelSingleCoop($coopId)
    {
        $coop = Coop::find($coopId);

        $coop->cancel();

        $this->info("Canceling 1 coop");
    }

    protected function cancelCoopsByExpirationDate(string $date)
    {
        $expirationDate = Carbon::parse($date);

        $coops = Coop::expiresAt($expirationDate)->get();

        foreach ($coops as $coop) {
            $coop->cancel();
        }

        $this->info("Canceling {$coops->count()} coops");
    }

    protected function cancelCoopsExpiringToday()
    {
        $coops = Coop::toBeCancelToday()->get();

        foreach ($coops as $coop) {
            $coop->cancel();
        }

        $this->info("Canceling {$coops->count()} coops");
    }
}
