<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingAddressController;
use Illuminate\Http\Request;

// Register and Login routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/update-user', [UserController::class, 'update']);
    Route::delete('/delete', [UserController::class, 'delete']);
    Route::post('/logout', [UserController::class, 'logout']);
});


// Product routes with a prefix
Route::prefix('products')->group(function () {
    Route::get('/list', [ProductController::class, 'index']); // List all products
    Route::post('/create', [ProductController::class, 'store']); // Create a new product
    Route::get('/show{id}', [ProductController::class, 'show']); // Get product by ID
    Route::put('/update{id}', [ProductController::class, 'update']); // Update product by ID
    Route::delete('/delete{id}', [ProductController::class, 'destroy']); // Delete product by ID
});




Route::prefix('categories')->group(function () {
    Route::get('/list', [CategoryController::class, 'index']);          // List all categories
    Route::post('/create', [CategoryController::class, 'store']);         // Create a new category
    Route::get('/show{id}', [CategoryController::class, 'show']);       // Get a specific category
    Route::put('/update{id}', [CategoryController::class, 'update']);     // Update a specific category
    Route::delete('/delete{id}', [CategoryController::class, 'destroy']); // Delete a specific category
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get_cart', [CartController::class, 'index']);// Get the current user's cart items
    Route::post('/add_cart', [CartController::class, 'store']);    // Add a new item to the user's cart
    Route::put('/update_cart/items/{id}', [CartController::class, 'update']);    // Update an existing item in the user's cart by its ID
    Route::delete('/delete_cart/items/{id}', [CartController::class, 'destroy']);    // Remove an item from the user's cart by its ID
    Route::delete('/cart/clear', [CartController::class, 'clear']);    // Clear all items from the user's cart
});



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add_cart-items', [CartItemController::class, 'add']);    // Route to add an item to the cart
    Route::put('/update_cart-items/{id}', [CartItemController::class, 'update']);    // Route to update the quantity of an existing cart item
    Route::delete('/delete_cart-items/{id}', [CartItemController::class, 'remove']);    // Route to remove an item from the cart
    Route::get('/get_cart-items', [CartItemController::class, 'index']);    // Route to retrieve all items in the user's cart
    Route::get('/get_carts/{cartId}/items', [CartItemController::class, 'getCartItems']);        // Get all items in a specific cart along with product details
});


Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
    Route::post('/create_order', [OrderController::class, 'store']);           // Create a new order
    Route::get('/get_order', [OrderController::class, 'index']);            // List all orders
    Route::get('/show{id}', [OrderController::class, 'show']);         // Get details of a specific order
    Route::put('/update{id}/status', [OrderController::class, 'updateStatus']); // Update the order status
    Route::delete('/delete{id}', [OrderController::class, 'destroy']);   // Delete an order
});


Route::middleware('auth:sanctum')->prefix('orders/{orderId}/items')->group(function () {
    Route::post('/store', [OrderItemController::class, 'store']);
    Route::get('/show{itemId}', [OrderItemController::class, 'show']);
    Route::put('/update{itemId}', [OrderItemController::class, 'update']);
    Route::delete('/delete{itemId}', [OrderItemController::class, 'destroy']);
});





Route::middleware('auth:sanctum')->prefix('payments')->group(function () {
    Route::get('/get', [PaymentController::class, 'index']);    // Get all payments
    Route::post('/create', [PaymentController::class, 'store']);    // Create a new payment
    Route::get('/show{id}', [PaymentController::class, 'show']);    // Show a specific payment by ID
    Route::put('/update{id}', [PaymentController::class, 'update']);    // Update a specific payment by ID
    Route::delete('/delete{id}', [PaymentController::class, 'destroy']);    // Delete a specific payment by ID
});



Route::prefix('shipping-addresses')->middleware('auth:sanctum')->group(function () {
    Route::get('/get', [ShippingAddressController::class, 'index']);     // Get a list of all shipping addresses
    Route::post('/create', [ShippingAddressController::class, 'store']);    // Store a new shipping address
    Route::get('/get{id}', [ShippingAddressController::class, 'show']);    // Retrieve a specific shipping address by ID
    Route::put('/update{id}', [ShippingAddressController::class, 'update']);    // Update an existing shipping address by ID
    Route::delete('/delete{id}', [ShippingAddressController::class, 'destroy']);    // Delete a specific shipping address by ID
});
