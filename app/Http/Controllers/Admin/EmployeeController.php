<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    // Display all employees
    public function index()
    {
        $employees = User::with('department')
            ->whereIn('role', ['employee', 'hr', 'admin'])
            ->paginate(10);
        
        return Inertia::render('Admin/ManageEmployees/ManageEmployee', [
            'employees' => $employees
        ]);
    }

    // Show specific employee
    public function show($id)
    {
        $employee = User::with('department')->findOrFail($id);
        
        return Inertia::render('Admin/ManageEmployees/View', [
            'employee' => $employee
        ]);
    }

    // Edit employee form
    public function edit($id)
    {
        $employee = User::with('department')->findOrFail($id);
        
        return Inertia::render('Admin/ManageEmployees/Edit', [
            'employee' => $employee
        ]);
    }

    // Update employee
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'department_id' => 'required|integer',
            'role_id' => 'required|integer',
            'employee_id' => 'required|string|max:50|unique:users,employee_id,' . $id,
            'dob' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'role' => 'required|in:employee,hr,admin',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        $employee->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'department_id' => $validated['department_id'],
            'role_id' => $validated['role_id'],
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
            'role' => $validated['role'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'employee_id' => $validated['employee_id'],
        ]);

        return redirect()->route('admin.manageemployees')
            ->with('success', 'Employee updated successfully!');
    }

    // Delete employee
    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.manageemployees')
            ->with('success', 'Employee deleted successfully!');
    }

    // Add a new user (Employee, HR, Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15',
            'department_id' => 'required|integer',
            'role_id' => 'required|integer',
            'employee_id' => 'required|string|max:50|unique:users,employee_id',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'role' => 'required|in:employee,hr,admin',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'department_id' => $validated['department_id'],
            'role_id' => $validated['role_id'],
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
            'password' => Hash::make($validated['email']),
            'role' => $validated['role'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'employee_id' => $validated['employee_id'],
        ]);

        return redirect()->route('admin.manageemployees')
            ->with('success', 'Employee added successfully!');
    }
}