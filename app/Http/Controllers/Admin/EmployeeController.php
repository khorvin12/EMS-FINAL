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
        // Build stable serial numbers
        $rankMap = User::where('role', '!=', 'admin')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->values()
            ->flip()
            ->map(fn($i) => $i + 1);

        $query = User::with('department')->where('role', '!=', 'admin')->orderBy('id', 'desc');

        if (request()->filled('search')) {
            $search = request('search');
            $numericSearch = ltrim(preg_replace('/^emp-?/i', '', trim($search)), '0') ?: null;
            $matchingIds = $rankMap->filter(fn($serial) => str_contains((string) $serial, $search))->keys();

            $query->where(function ($q) use ($search, $numericSearch, $matchingIds) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereHas('department', fn($d) => $d->where('name', 'like', "%{$search}%"))
                    ->orWhereIn('id', $matchingIds);

                if ($numericSearch && is_numeric($numericSearch)) {
                    $q->orWhere('id', 'like', "%{$numericSearch}%");
                }
            });
        }

        $employees = $query->paginate(6)->withQueryString();

        // Attach serial_no to each employee
        $employees->getCollection()->transform(function ($employee) use ($rankMap) {
            $employee->serial_no = $rankMap[$employee->id] ?? null;
            return $employee;
        });

        return Inertia::render('Admin/ManageEmployees/ManageEmployee', [
            'employees' => $employees,
            'filters' => request()->only(['search']),
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
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($id ? ",$id" : ''),
            'phone' => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'role' => 'required|in:employee,hr,admin',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
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
        return redirect('/admin/manageemployees')
            ->with('success', $message);
    }
}