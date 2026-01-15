<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // Add a new user (Employee, HR, Admin)
public function store(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'phone' => 'required|string|max:15', // Added phone validation
        'department_id' => 'required|integer', // Adjust based on your department model
        'role_id' => 'required|integer', // Adjust based on your roles
        'employee_id' => 'required|string|max:50|unique:users,employee_id',
        'dob' => 'required|date',
        'gender' => 'required|string',
        'civil_status' => 'required|string',
        'role' => 'required|in:employee,hr,admin',
        'hire_date' => 'required|date', // Added hire_date validation
        'salary' => 'required|numeric|min:0', // Added salary validation
    ]);

    // Create user
    User::create([
        'name' => $validated['first_name'] . ' ' . $validated['last_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'], // Added phone field
        'department_id' => $validated['department_id'], // Added department_id
        'role_id' => $validated['role_id'], // Added role_id
        'hire_date' => $validated['hire_date'], // Added hire_date
        'salary' => $validated['salary'], // Added salary
        'password' => Hash::make($validated['email']), // Password set to email
        'role' => $validated['role'],
        'dob' => $validated['dob'],
        'gender' => $validated['gender'],
        'civil_status' => $validated['civil_status'],
        'employee_id' => $validated['employee_id'],
    ]);

    return redirect()->back()->with('success', 'User added successfully!');
}
}