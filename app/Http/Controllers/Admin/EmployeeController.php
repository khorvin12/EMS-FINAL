<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    // Show employee list
    public function index()
    {
        $employees = User::with('department')->paginate(10);

        return Inertia::render('Admin/ManageEmployees/ManageEmployee', [
            'employees' => $employees
        ]);
    }

    // Show Add Employee page
    public function create()
    {
        $departments = Department::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/ManageEmployees/AddEmployee', [
            'departments' => $departments
        ]);
    }

    // Store new employee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
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
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
            'password' => Hash::make($validated['email']), // default password
            'role' => $validated['role'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'employee_id' => $validated['employee_id'],
        ]);

        return redirect()->route('admin.manageemployees')
            ->with('success', 'Employee added successfully!');
    }

    // Show single employee (for View page)
    public function show($id)
    {
        $employee = User::with('department')->findOrFail($id);

        return Inertia::render('Admin/ManageEmployees/View', [
            'employee' => $employee
        ]);
    }

    // Show Edit Employee page
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        $departments = Department::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/ManageEmployees/EditEmployee', [
            'employee' => $employee,
            'departments' => $departments
        ]);
    }

    // Update employee
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
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
}