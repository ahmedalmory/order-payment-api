<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory {
    public function definition() {
        return [
            'order_id' => Order::factory(),
            'payment_id' => $this->faker->uuid,
            'status' => 'pending',
            'payment_method' => 'credit_card',
        ];
    }
}