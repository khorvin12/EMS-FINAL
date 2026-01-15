<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return Inertia::render('Admin/Departments/ManageDepartment', [
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|string'
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
        'department' => $department
    ]);
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:departments,name,' . $id,
        'description' => 'nullable|string',
        'manager_id' => 'nullable|string'
    ]);

    Department::findOrFail($id)->update($validated);

    return redirect()->route('admin.departments')
        ->with('success', 'Department updated successfully!');
}
}