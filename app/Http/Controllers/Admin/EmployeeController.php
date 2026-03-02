<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::with('department')->paginate(6);

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
        return Inertia::render('Admin/ManageEmployees/EditEmployee', [
            'employee' => User::findOrFail($id),
            'departments' => $this->getDepartments()
        ]);
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);
        $validated = $this->validateEmployee($request, $id);
        $employee->update($this->prepareEmployeeData($validated, false));
        return $this->redirectWithSuccess('Employee updated successfully!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return $this->redirectWithSuccess('Employee deleted successfully!');
    }

    private function getDepartments()
    {
        return Department::select('id', 'name')->orderBy('name')->get();
    }

    private function validateEmployee(Request $request, $id = null)
    {
        $isHRorAdmin = in_array($request->role, ['hr', 'admin']);

        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($id ? ",$id" : ''),
            'phone' => 'nullable|string|max:15',
            'department_id' => $isHRorAdmin ? 'nullable|exists:departments,id' : 'required|exists:departments,id',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'role' => 'required|in:employee,hr,admin',
            'hire_date' => 'nullable|date',
            'salary' => $isHRorAdmin ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
        ]);
    }

    private function prepareEmployeeData(array $validated, $isNew = true)
    {
        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'department_id' => $validated['department_id'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'role' => $validated['role'],
            'role_id' => Role::where('name', $validated['role'])->value('id'),
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
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