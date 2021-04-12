<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coop;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseRefundTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_how_many_coops_are_being_canceled()
    {
        Coop::withoutEvents(function () {
            Coop::factory()
                ->count(2)
                ->has(Purchase::factory()->count(3))
                ->approved()
                ->create();
        });

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addWeeks(3));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops')->expectsOutput('Coops canceled: 2');
    }

    /** @test */
    public function ensure_coop_is_canceled()
    {
        $coops = collect([]);

        Coop::withoutEvents(function () use (&$coops) {
            $coops = Coop::factory()
                ->count(2)
                ->has(Purchase::factory()->count(3))
                ->approved()
                ->create();
        });

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addWeeks(3));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops');

        $coop = $coops->first()->refresh();

        $this->assertTrue($coop->isCanceled());
    }

    /** @test */
    public function ensure_purchases_are_canceled()
    {
        $coops = collect([]);

        Coop::withoutEvents(function () use (&$coops) {
            $coops = Coop::factory()
                ->count(2)
                ->has(Purchase::factory()->count(3))
                ->approved()
                ->create();
        });

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addWeeks(3));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops');

        $coop = $coops->first()->refresh();

        $this->assertTrue($coop->isCanceled());
        $this->assertCount(3, $coop->purchasesCanceled()->get());
    }
}
