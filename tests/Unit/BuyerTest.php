<?php

namespace Tests\Unit;

use App\Models\Buyer;
use Tests\TestCase;

class BuyerTest extends TestCase
{
    /** @test */
    public function a_buyer_can_prefer_credit_refund()
    {
        $buyer = Buyer::factory()->prefersCreditRefund()->make();

        $this->assertTrue($buyer->prefersCreditRefund());
    }

    /** @test */
    public function a_buyer_can_prefer_cc_refund()
    {
        $buyer = Buyer::factory()->prefersCCRefund()->make();

        $this->assertFalse($buyer->prefersCreditRefund());
    }
}
