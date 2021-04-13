<?php

namespace App\Jobs;

use App\Models\Purchase;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefundPurchase implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $purchase;

    /**
     * Create a new job instance.
     *
     * @param Purchase $purchase
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->purchase->refund();
    }
}
