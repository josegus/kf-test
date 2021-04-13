<?php

namespace App\Listeners;

use App\Events\PurchaseRefunded;

class CreateRefundTransaction
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PurchaseRefunded $event)
    {
        $purchase = $event->purchase;

        $purchase->transactions()->create([
            'buyer_id' => $purchase->buyer->id,
            'coop_id' => $purchase->coop->id,
            'type' => 'refund',
            'amount' => $purchase->amount,
            'source' => 'wire',
            'memo' => 'memo',
            'is_canceled' => false,
            'is_pending' => false
        ]);
    }
}
