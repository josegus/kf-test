<?php

namespace App\Listeners;

use App\Events\CoopCanceled;

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
        $event->coop->purchases()->update(['coop_canceled' => true]);
    }
}
