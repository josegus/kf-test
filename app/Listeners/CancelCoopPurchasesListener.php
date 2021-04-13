<?php

namespace App\Listeners;

use App\Models\Coop;
use App\Jobs\RefundPurchase;
use App\Events\CoopCanceled;
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
        $coop = $event->coop;

        // If the coop has no purchaes, just change its status
        if ($coop->purchases()->count() === 0) {
            $coop->update(['status' => 'canceled']);

            return;
        }

        $this->queueRefundPurchasesJob($coop);
    }

    protected function queueRefundPurchasesJob(Coop $coop)
    {
        $jobs = [];

        // This can be done using collection
        foreach ($coop->purchases as $purchase) {
            $jobs[] = new RefundPurchase($purchase);
        }

        Bus::batch($jobs)
            ->finally(function () use ($coop) {
                $coop->update(['status' => 'canceled']);
            })
            ->onQueue('refund')
            ->name('Refund purchases')
            ->dispatch();
    }
}
