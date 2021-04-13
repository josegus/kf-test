<?php

namespace App\Events;

use App\Models\Purchase;
use Illuminate\Foundation\Events\Dispatchable;

class PurchaseRefunded
{
    use Dispatchable;

    public Purchase $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }
}
