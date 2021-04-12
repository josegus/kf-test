<?php

namespace App\Listeners;

use App\Events\CoopCanceled;
use App\Notifications\CoopCanceled as CoopCanceledNotification;

class NotifyOwnerCoopCanceledListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CoopCanceled $event)
    {
        $event->coop->owner->notify(new CoopCanceledNotification());
    }
}
