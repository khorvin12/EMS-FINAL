<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index()
    {
        // Hide admin accounts from the employee list
        $employees = User::with('department')
            ->where('role', '!=', 'admin')
            ->paginate(10);

        return Inertia::render('Admin/ManageEmployees/ManageEmployee', [
            'employees' => $employees
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/ManageEmployees/AddnewEmployee', [
            'departments' => $this->getDepartments()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);
        User::create($this->prepareEmployeeData($validated));
        return $this->redirectWithSuccess('Employee added successfully!');
    }

    public function show($id)
    {
        $employee = User::with('department')->findOrFail($id);

        return Inertia::render('Admin/ManageEmployees/View', [
            'employee' => $employee
        ]);
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);

        // Prevent editing admin accounts
        if ($employee->role === 'admin') {
            return redirect()->route('admin.manageemployees')
                ->with('error', 'Admin accounts cannot be edited here.');
        }

        return Inertia::render('Admin/ManageEmployees/EditEmployee', [
            'employee'    => $employee,
            'departments' => $this->getDepartments()
        ]);
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        // Prevent updating admin accounts
        if ($employee->role === 'admin') {
            return redirect()->route('admin.manageemployees')
                ->with('error', 'Admin accounts cannot be edited here.');
        }

        $validated = $this->validateEmployee($request, $id);
        $employee->update($this->prepareEmployeeData($validated, false));
        return $this->redirectWithSuccess('Employee updated successfully!');
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);

        // Prevent deleting admin accounts
        if ($employee->role === 'admin') {
            return redirect()->route('admin.manageemployees')
                ->with('error', 'Admin accounts cannot be deleted.');
        }

        $employee->delete();
        return $this->redirectWithSuccess('Employee deleted successfully!');
    }

    private function getDepartments()
    {
        return Department::select('id', 'name')->orderBy('name')->get();
    }

    private function validateEmployee(Request $request, $id = null)
    {
        return $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email' . ($id ? ",$id" : ''),
            'phone'         => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
            'employee_id'   => 'required|string|max:50|unique:users,employee_id' . ($id ? ",$id" : ''),
            'dob'           => 'required|date',
            'gender'        => 'required|string',
            'civil_status'  => 'required|string',
            'role'          => 'required|in:employee,hr',  // admin cannot be created here
            'hire_date'     => 'required|date',
            'salary'        => 'required|numeric|min:0',
        ]);
    }

    private function prepareEmployeeData(array $validated, $isNew = true)
    {
        $data = [
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'department_id' => $validated['department_id'],
            'employee_id'   => $validated['employee_id'],
            'dob'           => $validated['dob'],
            'gender'        => $validated['gender'],
            'civil_status'  => $validated['civil_status'],
            'role'          => $validated['role'],
            'hire_date'     => $validated['hire_date'],
            'salary'        => $validated['salary'],
        ];

        if ($isNew) {
            $data['password'] = Hash::make($validated['email']);
        }

        return $data;
    }

    private function redirectWithSuccess($message)
    {
        return redirect()
            ->route('admin.manageemployees')
            ->with('success', $message);
    }
}