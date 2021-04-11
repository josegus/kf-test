<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coop;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoopListingTest extends TestCase
{
    use RefreshDatabase, WithoutEvents;

    /** @test */
    public function it_shows_a_list_of_approved_coops()
    {
        $coops = Coop::factory()->count(10)->approved()->create();

        $response = $this->get('/coops');

        $response->assertSee($coops->pluck('name')->toArray());
    }

    /** @test */
    public function it_shows_a_single_approved_coop()
    {
        $coop = Coop::factory()->approved()->create();

        $response = $this->get("/coops/{$coop->id}");

        $response->assertOk();
        $response->assertSee($coop->name);
    }

    /** @test */
    public function it_wont_show_coops_with_status_draft()
    {
        $coops = Coop::factory()->count(10)->draft()->create();

        $response = $this->get('/coops');

        $response->assertDontSee($coops->pluck('name')->toArray());
    }

    /** @test */
    public function it_wont_show_a_coop_that_is_in_draft()
    {
        $coop = Coop::factory()->draft()->create();

        $response = $this->get("/coops/{$coop->id}");

        $response->assertNotFound();
        $response->assertDontSee($coop->name);
    }

    /** @test */
    public function it_wont_show_coops_with_status_canceled()
    {
        $coops = Coop::factory()->count(10)->canceled()->create();

        $response = $this->get('/coops');

        $response->assertDontSee($coops->pluck('name')->toArray());
    }

    /** @test */
    public function it_wont_show_a_coop_that_has_been_canceled()
    {
        $coop = Coop::factory()->canceled()->create();

        $response = $this->get("/coops/{$coop->id}");

        $response->assertNotFound();
        $response->assertDontSee($coop->name);
    }
}
