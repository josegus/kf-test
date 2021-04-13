<?php

namespace App\Events;

use App\Models\Coop;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoopCreating
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Coop $coop;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Coop $coop)
    {
        $this->coop = $coop;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
