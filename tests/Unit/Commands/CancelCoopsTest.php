<?php

namespace Tests\Unit\Command;

use App\Models\Coop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelCoopsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_how_many_coops_are_being_canceled()
    {
        Coop::factory()
            ->count(3)
            ->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addWeeks(3));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops')->expectsOutput('Canceling 3 coops');
    }
}
