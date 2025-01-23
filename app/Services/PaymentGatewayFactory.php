<?php

namespace App\Services;

use App\Interfaces\PaymentGateway;
use App\Services\PaymentGateways\CreditCardGateway;
use App\Services\PaymentGateways\PayPalGateway;

class PaymentGatewayFactory {
    public static function create($gateway): PaymentGateway {
        return match ($gateway) {
            'credit_card' => new CreditCardGateway(),
            'paypal' => new PayPalGateway(),
            default => throw new \InvalidArgumentException('Invalid payment gateway'),
        };
    }
}