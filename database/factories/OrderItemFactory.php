<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory {
    public function definition() {
        return [
            'order_id' => Order::factory(),
            'product_name' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}