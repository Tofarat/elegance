<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Admin login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Generate and return token for authenticated admin
            return response()->json(['message' => 'Login successful']);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    // List all users
    public function listUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Add other admin functions as needed, e.g., manage products, view orders, etc.
}
