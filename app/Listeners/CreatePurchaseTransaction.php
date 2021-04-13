<?php

namespace App\Listeners;

use App\Events\PurchaseCreated;
use App\Models\Transaction;
use Illuminate\Support\Arr;

class CreatePurchaseTransaction
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PurchaseCreated $event)
    {
        $purchase = $event->purchase;

        $purchase->transactions()->create([
            'buyer_id' => $purchase->buyer->id,
            'coop_id' => $purchase->coop->id,
            'type' => 'purchase',
            'amount' => $purchase->amount,
            'source' => Arr::random(Transaction::sources()),
            'memo' => 'memo',
            'is_canceled' => false,
            'is_pending' => false,
        ]);
    }
}
