<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGateway;

class PayPalGateway implements PaymentGateway {
    public function processPayment($amount, $orderId) {
        // Simulate payment processing
        return 'successful';
    }
}