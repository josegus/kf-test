<?php

namespace Tests\Feature;

use App\Models\Coop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoopListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_list_of_coops_in_to_be_funded()
    {
        $coops = Coop::factory()->count(10)->create();

        $response = $this->get('/coops');

        $response->assertSee($coops->pluck('name')->toArray());
    }

    /** @test */
    public function it_shows_a_single_coop()
    {
        $coop = Coop::factory()->create();

        $response = $this->get("/coops/{$coop->id}");

        $response->assertOk();
        $response->assertSee($coop->name);
    }

    /** @test */
    public function it_wont_show_canceled_coops()
    {
        $coops = Coop::withoutEvents(function () {
            return Coop::factory()->count(10)->canceled()->create();
        });

        $response = $this->get('/coops');

        $response->assertDontSee($coops->pluck('name')->toArray());
    }

    /** @test */
    public function it_wont_show_a_coop_that_is_canceled()
    {
        $coop = Coop::withoutEvents(function () {
            return Coop::factory()->canceled()->create();
        });

        $response = $this->get("/coops/{$coop->id}");

        $response->assertNotFound();
        $response->assertDontSee($coop->name);
    }
}
