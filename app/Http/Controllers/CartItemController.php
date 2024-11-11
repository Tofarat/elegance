<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CartItemController extends Controller
{
    // Add item to the cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get or create the user's cart
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Add or update the item in the cart
        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity
            ]
        );

        return response()->json([
            'message' => 'Item added to cart successfully!',
            'cartItem' => $cartItem
        ], 201);
    }

    // Update item in the cart
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item updated successfully!',
            'cartItem' => $cartItem
        ], 200);
    }

    // Remove item from the cart
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully!'
        ], 200);
    }

    // Get all items in the user's cart
    public function index()
    {
        // Get the user's cart and its items
        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        $cartItems = $cart ? $cart->cartItems : [];

        return response()->json([
            'message' => 'Retrieved cart items successfully!',
            'cartItems' => $cartItems
        ], 200);
    }

    // Get all items in the user's cart along with product details
    public function getCartItems()
    {
        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        $cartItems = $cart ? $cart->cartItems : [];

        return response()->json([
            'message' => 'Retrieved cart items successfully!',
            'cartItems' => $cartItems
        ], 200);
    }
}
