<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Buyer;

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
