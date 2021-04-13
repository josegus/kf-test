<?php

namespace App\Listeners;

use App\Events\CoopCanceled;
use App\Jobs\RefundPurchase;

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
        foreach ($event->coop->purchases as $purchase) {
            RefundPurchase::dispatch($purchase);
        }
    }
}
