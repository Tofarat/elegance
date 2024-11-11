<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Display a listing of the payments
    public function index()
    {
        $payments = Payment::all();

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully.',
            'data' => $payments
        ]);
    }

    // Store a new payment in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string'
        ]);

        $payment = Payment::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully.',
            'data' => $payment
        ], 201);
    }

    // Show a single payment
    public function show($id)
    {
        $payment = Payment::find($id);

        if ($payment) {
            return response()->json([
                'success' => true,
                'message' => 'Payment details retrieved successfully.',
                'data' => $payment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found.'
            ], 404);
        }
    }

    // Update an existing payment
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found.'
            ], 404);
        }

        $validatedData = $request->validate([
            'order_id' => 'exists:orders,id',
            'amount' => 'numeric',
            'payment_method' => 'string',
            'payment_status' => 'string'
        ]);

        $payment->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully.',
            'data' => $payment
        ]);
    }

    // Delete a payment
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found.'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.'
        ]);
    }
}
