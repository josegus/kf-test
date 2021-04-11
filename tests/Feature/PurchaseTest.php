<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\Coop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase, WithoutEvents;

    /** @test */
    public function a_buyer_can_fund_a_coop_by_creating_a_purchase()
    {
        $coop = Coop::factory()->approved()->create();
        $buyer = Buyer::factory()->create();

        $this->actingAs($buyer)
            ->post("/coops/{$coop->id}/fund", [
                'amount' => 500,
                'package_quantity' => 5,
                'package_id' => 1
            ]);

        $this->assertDatabaseCount('purchases', 1);
        $this->assertDatabaseHas('purchases', [
            'amount' => 500,
            'package_quantity' => 5,
            'package_id' => 1
        ]);
    }

    /** @test */
    public function purchase_has_owner_after_creating()
    {
        $coop = Coop::factory()->approved()->create();
        $buyer = Buyer::factory()->create();

        $this->actingAs($buyer)
            ->post("/coops/{$coop->id}/fund", [
                'amount' => 500,
                'package_quantity' => 5,
                'package_id' => 1
            ]);

        $this->assertEquals(1, $buyer->purchases()->count());
    }


    /** @test */
    public function redirect_back_to_coop_after_purchase_has_been_created()
    {
        $coop = Coop::factory()->approved()->create();
        $buyer = Buyer::factory()->create();

        $response = $this->actingAs($buyer)
            ->post("/coops/{$coop->id}/fund", [
                'amount' => 500,
                'package_quantity' => 5,
                'package_id' => 1
            ]);

        $response->assertRedirect("/coops/{$coop->id}");
    }

    /** @test */
    /* public function display_a_success_message_after_purchase_has_been_created()
    {
        $coop = Coop::factory()->approved()->create();
        $buyer = Buyer::factory()->create();

        $response = $this->actingAs($buyer)
            ->post("/coops/{$coop->id}/fund", [
                'amount' => 500,
                'package_quantity' => 5,
                'package_id' => 1
            ]);

        $response->assertSee('Thanks for purchasing');
    } */
}
