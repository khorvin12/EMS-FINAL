<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index()
    {
        $rankMap = Department::orderBy('id', 'asc')
            ->pluck('id')
            ->values()
            ->flip()
            ->map(fn($i) => $i + 1);

        $total = Department::count();

        $query = Department::orderBy('id', 'desc'); // ← newest first

        if (request()->filled('search')) {
            $search = request('search');
            $matchingIds = $rankMap->filter(fn($serial) => str_contains((string) $serial, $search))->keys();

            $query->where(function ($q) use ($search, $matchingIds) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereIn('id', $matchingIds);
            });
        }

        $departments = $query->paginate(6)->withQueryString();

        $departments->getCollection()->transform(function ($department) use ($rankMap, $total) {
            $department->serial_no = $total - $rankMap[$department->id] + 1; // ← descending
            return $department;
        });

        return Inertia::render('Admin/Departments/ManageDepartment', [
            'departments' => $departments,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Departments/AddDepartment', [
            'users' => User::select('id', 'first_name', 'last_name')
                ->where('role', '!=', 'admin')
                ->orderBy('first_name')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::create($validated);

        return redirect('/admin/departments')->with('success', 'Department added successfully!');
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
            'users' => User::select('id', 'first_name', 'last_name')
                ->where('role', '!=', 'admin')
                ->orderBy('first_name')
                ->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::findOrFail($id)->update($validated);

        return redirect('/admin/departments')->with('success', 'Department updated successfully!');
    }
}