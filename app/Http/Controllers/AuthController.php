<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle admin login (NO REGISTER)
     */
    public function login(Request $request)
{
    // Validate input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Attempt login
    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    // Regenerate session
    $request->session()->regenerate();

    // Redirect based on role
    $role = Auth::user()->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'employee') {
        return redirect()->route('employee.dashboard'); }// employee page
        elseif ($role === 'hr') {
        return redirect()->route('hr.dashboard');
    } else {
        Auth::logout();
        return back()->withErrors([
            'email' => 'Your role is not allowed.',
        ]);
    }
}

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
