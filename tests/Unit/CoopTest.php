<?php

namespace Tests\Unit;

use App\Models\Coop;
use Tests\TestCase;
use App\Notifications\CoopCanceled;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CoopTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_coop_can_be_canceled()
    {
        $coop = Coop::factory()->approved()->create();

        $coop->cancel();

        $this->assertTrue($coop->isCanceled());
    }

    /** @test */
    public function a_coop_already_canceled_wont_notify_its_owner()
    {
        Coop::withoutEvents(function () {
            Notification::fake();

            Coop::factory()->canceled()->create()->cancel();

            Notification::assertNothingSent();
        });
    }

    /** @test */
    public function a_coop_notify_its_owner_when_is_canceled()
    {
        Coop::withoutEvents(function () {
            Notification::fake();

            $coop = Coop::factory()->approved()->create();

            $coop->cancel();

            Notification::assertSentTo(
                [$coop->owner],
                CoopCanceled::class
            );
        });
    }
}
