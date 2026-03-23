<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = DB::table('departments')
            ->leftJoin('users', 'departments.manager_id', '=', 'users.id')
            ->select(
                'departments.id',
                'departments.name',
                'departments.manager_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as manager_name")
            )
            ->paginate(6);

        return Inertia::render('Admin/Departments/ManageDepartment', [
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'manager_id'  => 'nullable|exists:users,id'
        ]);

        Department::create($validated);

        return redirect('/departments')
            ->with('success', 'Department added successfully!');
    }

    public function destroy($id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Department deleted!');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return Inertia::render('Admin/Departments/EditDepartment', [
            'department' => $department,
            'employees'  => $this->getEmployees()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'manager_id'  => 'nullable|exists:users,id'
        ]);

        Department::findOrFail($id)->update($validated);

        return redirect()->route('admin.departments')
            ->with('success', 'Department updated successfully!');
    }

    private function getEmployees()
    {
        return DB::table('users')
            ->where('role', 'employee')
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get()
            ->map(fn($u) => [
                'id'   => $u->id,
                'name' => $u->first_name . ' ' . $u->last_name
            ]);
    }
}