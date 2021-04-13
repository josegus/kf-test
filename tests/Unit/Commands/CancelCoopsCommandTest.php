<?php

namespace Tests\Unit\Commands;

use App\Models\Coop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelCoopsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_how_many_coops_are_being_canceled()
    {
        Coop::factory()
            ->count(3)
            ->create();

        // Coop expires in two weeks, we need to travel to the future
        $this->travelTo(now()->addDays(15));

        // We don't have access to cron scheduling, fire command manually
        $this->artisan('kf:cancel-coops')->expectsOutput('Canceling 3 coops');
    }

    /** @test */
    public function it_accepts_a_coop_parameter()
    {
        $coop = Coop::factory()->create();

        // We don't have access to cron scheduling, fire command manually
        $this->artisan(
            'kf:cancel-coops',
            ['--coop' => $coop->id])
        ->expectsOutput('Canceling 1 coop');
    }

    /** @test */
    public function it_accepts_a_expiration_date_parameter()
    {
        // Force expiration date to be this value
        $expirationDate = today()->toDateString();

        $coops = Coop::factory(['expiration_date' => $expirationDate])->count(3)->create();

        // We don't have access to cron scheduling, fire command manually
        $this->artisan(
            'kf:cancel-coops',
            ['--date' => $expirationDate]
        )->expectsOutput("Canceling {$coops->count()} coops");
    }
}
