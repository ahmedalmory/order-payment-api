<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase {
    use RefreshDatabase;

    public function test_process_payment() {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'confirmed']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'order_id', 'status']);
    }

    public function test_process_payment_for_pending_order() {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Order must be confirmed']);
    }
}