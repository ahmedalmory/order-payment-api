<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase {
    use RefreshDatabase;

    public function test_create_order() {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/orders', [
            'user_id' => $user->id,
            'items' => [
                ['product_name' => 'Product 1', 'quantity' => 2, 'price' => 10.00],
                ['product_name' => 'Product 2', 'quantity' => 1, 'price' => 20.00],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'user_id', 'total']);
    }

    public function test_delete_order_with_payments() {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $order = Order::factory()->create(['user_id' => $user->id]);
        $order->payments()->create([
            'payment_id' => '123',
            'status' => 'pending',
            'payment_method' => 'credit_card',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Cannot delete order with associated payments']);
    }
}