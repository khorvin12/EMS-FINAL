<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HRController extends Controller
{
public function store(Request $request)
{
    // validate input
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:employee,hr,admin',
    ]);

    // create user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    // confirm creation
    if ($user) {
        return back()->with('success', 'HR added!');
    } else {
        return back()->with('error', 'Failed to add HR.');
    }
}


}
