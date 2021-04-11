<?php

namespace App\Listeners;

use App\Enums\CoopStatus;
use App\Events\CoopCreating;

class SetCoopStatus
{
    /**
     * Handle the event.
     *
     * @param  CoopCreated  $event
     * @return void
     */
    public function handle(CoopCreating $event)
    {
        $event->coop->setAttribute('status', CoopStatus::DRAFT);
    }
}
