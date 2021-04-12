<?php

namespace Tests\Unit;

use App\Events\CoopCanceled as EventsCoopCanceled;
use Tests\TestCase;
use App\Models\Coop;
use App\Models\Purchase;
use App\Notifications\CoopCanceled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoopTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_reach_expiration_date()
    {
        $coop = Coop::factory()->create();

        $this->travelTo(now()->addWeeks(3));

        $this->assertTrue($coop->hasReachedExpirationDate());
    }

    /** @test */
    public function can_get_list_of_coops_to_be_canceled_in_the_day()
    {
        Coop::withoutEvents(function () {
            Coop::factory()
                ->count(5)
                ->approved()
                ->create();

            Coop::factory()
                ->count(3)
                ->draft()
                ->create();
        });

        $this->travelTo(now()->addWeeks(3));

        $this->assertCount(5, Coop::toBeCancelToday()->get());
    }

    /** @test */
    public function canceling_a_coop_will_cancel_its_purchases()
    {
        $coop = Coop::factory()
            ->has(Purchase::factory()->count(3))
            ->approved()
            ->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        $this->assertTrue($coop->isCanceled());
        $this->assertCount(3, $coop->purchasesCanceled()->get());
    }

    /** @test */
    public function a_coop_can_be_canceled()
    {
        $coop = Coop::factory()->approved()->create();

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        $this->assertTrue($coop->isCanceled());
    }

    /** @test */
    public function cancelling_a_coop_dispatch_an_event()
    {
        Event::fake();

        $coop = Coop::factory()->approved()->create();

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        Event::assertDispatched(function (EventsCoopCanceled $event) use ($coop) {
            return $event->coop->is($coop);
        });
    }

    /** @test */
    public function a_coop_already_canceled_wont_notify_its_owner()
    {
        Notification::fake();

        Coop::factory()
            ->canceled()
            ->create()
            ->cancel();

        Notification::assertNothingSent();
    }

    /** @test */
    public function a_coop_already_expired_wont_notify_its_owner()
    {
        Notification::fake();

        Coop::factory()
            ->canceled()
            ->expired()
            ->create();

        Notification::assertNothingSent();
    }

    /** @test */
    public function a_coop_notify_its_owner_when_is_canceled()
    {
        Notification::fake();

        $coop = new Coop;

        Coop::withoutEvents(function () use (&$coop) {
            $coop = Coop::factory()->approved()->create();
        });

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        Notification::assertSentTo(
            [$coop->owner],
            CoopCanceled::class
        );
    }
}
