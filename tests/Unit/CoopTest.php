<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Coop;
use App\Models\Purchase;
use App\Notifications\CoopCanceled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Events\CoopCanceled as EventsCoopCanceled;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoopTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_list_of_coops_to_be_canceled_in_the_day()
    {
        Coop::withoutEvents(function () {
            Coop::factory()
                ->count(5)
                ->draft()
                ->create();

            Coop::factory()
                ->count(3)
                ->canceled()
                ->create();
        });

        $this->travelTo(now()->addWeeks(3));

        $this->assertCount(5, Coop::toBeCancelToday()->get());
    }

    /** @test */
    /* public function a_coop_can_be_canceled()
    {
        // create coop, with some purchases

        // perform cancellation

        // assert

        $coop = Coop::factory()->approved()->create();

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        $this->assertTrue($coop->isCanceled());
    } */

    /** @test */
    public function cancelling_a_coop_dispatch_an_event()
    {
        Event::fake();

        $coop = Coop::factory()->create();

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        Event::assertDispatched(function (EventsCoopCanceled $event) use ($coop) {
            return $event->coop->is($coop);
        });
    }

    /** @test */
    public function a_coop_already_canceled_will_not_be_canceled_again()
    {
        Event::fake();

        $coop = Coop::withoutEvents(function () {
            return Coop::factory()->canceled()->create();
        });

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        $this->assertTrue($coop->isCanceled());
        Event::assertNotDispatched(EventsCoopCanceled::class);
    }

    /** @test */
    public function a_coop_fully_funded_wil_not_be_canceled_()
    {
        Event::fake();

        // Create a coop and many purchase to fund it
        $coop = Coop::factory(['goal' => 100])
            ->has(Purchase::factory(['amount' => 200])->count(2))
            ->create();

        $coop->cancel();

        $this->assertFalse($coop->isCanceled());
        Event::assertNotDispatched(EventsCoopCanceled::class);
    }

    /** @test */
    public function a_coop_already_canceled_wont_notify_its_owner()
    {
        Notification::fake();

        Coop::withoutEvents(function () {
            Coop::factory()
                ->canceled()
                ->create()
                ->cancel();
        });

        Notification::assertNothingSent();
    }

    /** @test */
    public function a_coop_notify_its_owner_when_is_canceled()
    {
        Notification::fake();

        $coop = Coop::factory()->create();

        $this->travelTo(now()->addWeeks(3));

        $coop->cancel();

        Notification::assertSentTo(
            [$coop->owner],
            CoopCanceled::class
        );
    }
}
