<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Get the user's cart with items and product details
    public function index()
    {
        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();

        return response()->json($cart);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get or create the cart for the authenticated user
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Add or update the item in the cart
        $cartItem = $cart->cartItems()->updateOrCreate(
            ['product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return response()->json([
            'message' => 'Item added to cart successfully.',
            'cart_item' => $cartItem
        ]);
    }

    // Update item quantity in cart
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'message' => 'Cart item updated successfully.',
            'cart_item' => $cartItem
        ]);
    }

    // Remove item from cart
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart item removed successfully.'
        ], 200);
    }

    // Clear the user's cart
    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->cartItems()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.'
        ], 200);
    }
}
