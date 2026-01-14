<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HRController extends Controller
{
    // Add a new user (Employee, HR, Admin)
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:users,employee_id',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'role' => 'required|in:employee,hr,admin',
        ]);

        // Create user
        User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['employee_id'] . '@company.com', // or add email field in Vue form
            'password' => Hash::make('default123'), // default password
            'role' => $validated['role'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'employee_id' => $validated['employee_id'],
        ]);

        return redirect()->back()->with('success', 'User added successfully!');
    }
}
