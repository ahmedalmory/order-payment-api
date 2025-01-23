<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller {
    // Create Order
    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
        ]);
    
        $total = collect($validated['items'])->sum(fn($item) => $item['quantity'] * $item['price']);
        $order = Order::create(['user_id' => $validated['user_id'], 'total' => $total]);
    
        foreach ($validated['items'] as $item) {
            $order->items()->create($item);
        }
    
        return response()->json($order, 201);
    }

    // Update Order
    public function update(Request $request, $id) {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'status' => 'sometimes|string',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    // Delete Order
    public function destroy($id) {
        $order = Order::findOrFail($id);
        if ($order->payments()->exists()) {
            return response()->json(['error' => 'Cannot delete order with associated payments'], 400);
        }

        $order->delete();
        return response()->json(null, 204);
    }

    // View Orders
    public function index(Request $request) {
        $query = Order::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);
        return response()->json($orders);
    }

    // View Single Order
    public function show($id) {
        $order = Order::with('items', 'payments')->findOrFail($id);
        return response()->json($order);
    }
}