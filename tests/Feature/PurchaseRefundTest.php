<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coop;
use App\Models\Purchase;
use App\Actions\Stripe\FakeRefundCharge;
use App\Actions\Stripe\RefundChargeInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseRefundTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(RefundChargeInterface::class, new FakeRefundCharge);
    }

    /** @test */
    public function coop_can_be_canceled()
    {
        // Create a coop that won't reach the goal
        $coop = Coop::factory(['goal' => 100])->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addDays(15));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops');

        $coop->refresh();

        $this->assertTrue($coop->isCanceled());
    }

    /** @test */
    public function cancelling_a_coop_refunds_all_purchases()
    {
        // Create a coop that won't reach the goal
        $coop = Coop::factory(['goal' => 100])
            ->has(Purchase::factory(['amount' => 10])->count(2))
            ->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addDays(15));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops');

        $this->assertTrue($coop->purchases[0]->hasBeenRefunded());
        $this->assertTrue($coop->purchases[1]->hasBeenRefunded());
    }

    /** @test */
    public function it_wont_refund_a_purchase_that_has_been_fully_funded()
    {
        // Create a coop that reachs the goal with two purchases
        $coop = Coop::factory(['goal' => 100])
            ->has(Purchase::factory(['amount' => 100])->count(2))
            ->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addDays(15));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops');

        $this->assertTrue($coop->hasBeenFullyFunded());
        $this->assertFalse($coop->purchases[0]->hasBeenRefunded());
        $this->assertFalse($coop->purchases[1]->hasBeenRefunded());
    }

    /** @test */
    public function it_creates_a_refund_transaction_for_each_refunded_purchase()
    {
        // Create a coop that won't reach the goal
        $coop = Coop::factory(['goal' => 100])
            ->has(Purchase::factory(['amount' => 10]))
            ->has(Purchase::factory(['amount' => 20]))
            ->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addDays(15));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops');

        $this->assertDatabaseHas('transactions', [
            'coop_id' => $coop->id,
            'purchase_id' => $coop->purchases[0]->id,
            'type' => 'refund',
            'amount' => 10
        ]);

        $this->assertDatabaseHas('transactions', [
            'coop_id' => $coop->id,
            'purchase_id' => $coop->purchases[1]->id,
            'type' => 'refund',
            'amount' => 20
        ]);
    }
}
