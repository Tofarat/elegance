<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. Store a new order with items
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Create the order
            $order = Order::create([
                'user_id' => $request->user_id,
                'status' => $request->status,
                'total_amount' => $request->total_amount,
            ]);

            // Attach each order item to the order
            foreach ($request->items as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Your order has been placed successfully!',
                'order' => $order->load('orderItems')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Sorry, we were unable to create your order. Please try again or contact support.'
            ], 500);
        }
    }

    // 2. Retrieve a specific order by ID with items and payment details
    public function show($id)
    {
        $order = Order::with(['orderItems', 'payment'])->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found. Please check the ID and try again.'
            ], 404);
        }

        return response()->json($order);
    }

    // 3. List all orders with related user and item data
    public function index()
    {
        $orders = Order::with(['user', 'orderItems'])->get();
        return response()->json([
            'message' => 'Here are all the orders in our system:',
            'orders' => $orders
        ]);
    }

    // 4. Update an order's status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found. Please verify the order ID.'
            ], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'The order status has been updated successfully.',
            'order' => $order
        ]);
    }

    // 5. Delete an order with its items
    public function destroy($id)
    {
        $order = Order::with('orderItems')->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found. Please verify the order ID.'
            ], 404);
        }

        // Delete all associated order items before deleting the order
        $order->orderItems()->delete();
        $order->delete();

        return response()->json([
            'message' => 'The order and its items have been successfully deleted.'
        ]);
    }
}
