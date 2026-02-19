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
    /**
     * Display employee list
     */
    public function index()
    {
        $employees = User::with('department')->paginate(6);

        return Inertia::render('Admin/ManageEmployees/ManageEmployee', [
            'employees' => $employees
        ]);
    }

    /**
     * Show create employee form
     */
    public function create()
    {
        return Inertia::render('Admin/ManageEmployees/AddnewEmployee', [
            'departments' => $this->getDepartments()
        ]);
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);

        User::create($this->prepareEmployeeData($validated));

        return $this->redirectWithSuccess('Employee added successfully!');
    }

    /**
     * Display single employee
     */
    public function show($id)
    {
        $employee = User::with('department')->findOrFail($id);

        return Inertia::render('Admin/ManageEmployees/View', [
            'employee' => $employee
        ]);
    }

    /**
     * Show edit employee form
     */
    public function edit($id)
    {
        return Inertia::render('Admin/ManageEmployees/EditEmployee', [
            'employee' => User::findOrFail($id),
            'departments' => $this->getDepartments()
        ]);
    }

    /**
     * Update employee
     */
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);
        $validated = $this->validateEmployee($request, $id);

        $employee->update($this->prepareEmployeeData($validated, false));

        return $this->redirectWithSuccess('Employee updated successfully!');
    }

    /**
     * Delete employee
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return $this->redirectWithSuccess('Employee deleted successfully!');
    }

    /**
     * Get departments for dropdown
     */
    private function getDepartments()
    {
        return Department::select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Validate employee data
     */
    private function validateEmployee(Request $request, $id = null)
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($id ? ",$id" : ''),
            'phone' => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|string|max:50|unique:users,employee_id' . ($id ? ",$id" : ''),
            'dob' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'role' => 'required|in:employee,hr,admin',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);
    }

    /**
     * Prepare employee data for create/update
     */
    private function prepareEmployeeData(array $validated, $isNew = true)
    {
        $data = [
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'department_id' => $validated['department_id'],
            'employee_id' => $validated['employee_id'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'role' => $validated['role'],
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
        ];

        // Only set password for new employees
        if ($isNew) {
            $data['password'] = Hash::make($validated['email']);
        }

        return $data;
    }

    /**
     * Redirect to employee list with success message
     */
    private function redirectWithSuccess($message)
    {
        return redirect()
            ->route('admin.manageemployees')
            ->with('success', $message);
    }
}