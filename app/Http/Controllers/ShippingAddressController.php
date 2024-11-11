<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    // Display a listing of the user's shipping addresses
    public function index()
    {
        $userId = Auth::id();
        $shippingAddresses = ShippingAddress::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Here are your saved shipping addresses.',
            'data' => $shippingAddresses
        ]);
    }

    // Store a new shipping address for the user
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $validatedData['user_id'] = Auth::id();

        $shippingAddress = ShippingAddress::create($validatedData);

        return response()->json([
            'message' => 'Your shipping address has been added successfully!',
            'data' => $shippingAddress
        ], 201);
    }

    // Show a single shipping address
    public function show($id)
    {
        $userId = Auth::id();
        $shippingAddress = ShippingAddress::where('user_id', $userId)->findOrFail($id);

        return response()->json([
            'message' => 'Here is the details of your selected shipping address.',
            'data' => $shippingAddress
        ]);
    }

    // Update an existing shipping address
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $shippingAddress = ShippingAddress::where('user_id', $userId)->findOrFail($id);

        $validatedData = $request->validate([
            'address_line_1' => 'string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'string|max:100',
            'state' => 'string|max:100',
            'country' => 'string|max:100',
            'postal_code' => 'string|max:20',
        ]);

        $shippingAddress->update($validatedData);

        return response()->json([
            'message' => 'Your shipping address has been updated successfully!',
            'data' => $shippingAddress
        ]);
    }

    // Delete a shipping address
    public function destroy($id)
    {
        $userId = Auth::id();
        $shippingAddress = ShippingAddress::where('user_id', $userId)->findOrFail($id);
        $shippingAddress->delete();

        return response()->json([
            'message' => 'Your shipping address has been deleted successfully.'
        ]);
    }
}
