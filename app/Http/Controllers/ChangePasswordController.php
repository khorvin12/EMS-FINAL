<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'User not authenticated']);
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Get authenticated user's ID
        $userId = Auth::id();
        
        // Update password in database
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => Hash::make($request->password),
                'updated_at' => now()
            ]);

        return back()->with('success', 'Password changed successfully!');
    }
}