<?php

namespace App\Interfaces;

interface PaymentGateway {
    public function processPayment($amount, $orderId);
}