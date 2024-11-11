<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    // 1. Add an item to a specific order
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Sorry, we couldn’t find the order you’re looking for. Please double-check the order ID and try again.'], 404);
        }

        $orderItem = $order->orderItems()->create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Item added successfully to your order!', 'orderItem' => $orderItem], 201);
    }

    // 2. Retrieve a specific item in an order
    public function show($orderId, $itemId)
    {
        $orderItem = OrderItem::where('order_id', $orderId)->find($itemId);

        if (!$orderItem) {
            return response()->json(['message' => 'Sorry, we couldn’t find this item in your order. Please check the item ID and try again.'], 404);
        }

        return response()->json(['message' => 'Order item retrieved successfully!', 'orderItem' => $orderItem]);
    }

    // 3. Update a specific item in an order
    public function update(Request $request, $orderId, $itemId)
    {
        $request->validate([
            'product_id' => 'sometimes|integer|exists:products,id',
            'quantity' => 'sometimes|integer',
            'price' => 'sometimes|numeric',
        ]);

        $orderItem = OrderItem::where('order_id', $orderId)->find($itemId);

        if (!$orderItem) {
            return response()->json(['message' => 'Oops! We couldn’t find the item in your order. Please confirm the item ID and try again.'], 404);
        }

        $orderItem->update($request->only(['product_id', 'quantity', 'price']));

        return response()->json(['message' => 'Order item updated successfully!', 'orderItem' => $orderItem]);
    }

    // 4. Delete a specific item in an order
    public function destroy($orderId, $itemId)
    {
        $orderItem = OrderItem::where('order_id', $orderId)->find($itemId);

        if (!$orderItem) {
            return response()->json(['message' => 'We couldn’t find the item you want to delete. Please verify the item ID and try again.'], 404);
        }

        $orderItem->delete();

        return response()->json(['message' => 'Item successfully removed from your order.']);
    }
}

