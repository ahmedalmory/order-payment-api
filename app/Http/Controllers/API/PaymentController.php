<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentGatewayFactory;
use Illuminate\Http\Request;

class PaymentController extends Controller {
    // Process Payment
    public function store(Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        if ($order->status !== 'confirmed') {
            return response()->json(['error' => 'Order must be confirmed'], 400);
        }

        $gateway = PaymentGatewayFactory::create($validated['payment_method']);
        $paymentStatus = $gateway->processPayment($order->total, $order->id);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_id' => uniqid(),
            'status' => $paymentStatus,
            'payment_method' => $validated['payment_method'],
        ]);

        return response()->json($payment, 201);
    }

    // View Payments
    public function index(Request $request) {
        $query = Payment::query();
        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        $payments = $query->paginate(10);
        return response()->json($payments);
    }
}