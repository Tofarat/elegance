<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'nullable|string|in:admin,customer',
        ]);

        // Hash the password and set a default role
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = $validated['role'] ?: 'customer';

        // Create the user and check query log
        $user = User::create($validated);

        // Respond with a success message
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        
    }

    // User login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'Messages' => "Login successful",
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Get authenticated user profile
    public function profile(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    // Update user profile
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'sometimes|string|email|max:255,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        // Hash the password if it is being updated
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user, 200);
    }


    // User logout
public function logout(Request $request)
{
    // Revoke the token that was used to authenticate the current request
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully'], 200);
}


    // Delete user account
    public function delete(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
