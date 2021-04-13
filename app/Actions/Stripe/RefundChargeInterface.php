<?php

namespace App\Actions\Stripe;

interface RefundChargeInterface
{
    /**
     * Mimics a Stripe refund
     *
     * @param string $token string that identifies the target account.
     * @param int $amount in cents
     */
    public function refund(string $token, int $amount);
}
