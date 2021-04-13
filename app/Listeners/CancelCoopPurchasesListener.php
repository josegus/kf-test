<?php

namespace App\Listeners;

use App\Events\CoopCanceled;
use App\Jobs\RefundPurchase;
use Illuminate\Support\Facades\Bus;

class CancelCoopPurchasesListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CoopCanceled $event)
    {
        $jobs = [];

        // This can be done using collection
        foreach ($event->coop->purchases as $purchase) {
            $jobs[] = new RefundPurchase($purchase);
        }

        Bus::batch($jobs)
            ->then(function () use ($event) {
                $event->coop->update(['status' => 'canceled']);
            })
            ->onQueue('refund')
            ->name('Cancel coops purchase')
            ->dispatch();
    }
}
