<?php

namespace App\Actions\Stripe;

class CancelCharge
{
    /**
     * Mimics a Stripe cancellation.
     *
     * @param string $identifier string that identifies the target account.
     */
    public function refund(string $identifier)
    {
        sleep(10);

        return true;
    }
}
