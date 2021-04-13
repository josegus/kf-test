<?php

namespace App\Actions\Stripe;

class FakeRefundCharge implements RefundChargeInterface
{
    /**
     * Mimics a Stripe refund
     *
     * @param string $token string that identifies the target account.
     * @param int $amount in cents
     */
    public function refund(string $token, int $amount)
    {
        return true;
    }
}
