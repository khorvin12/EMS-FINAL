<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SalaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get department name from departments table if using department_id
        $department = null;
        if ($user->department_id) {
            $dept = DB::table('departments')->where('id', $user->department_id)->first();
            $department = $dept ? $dept->name : 'Not Assigned';
        } else {
            $department = $user->department ?? 'Not Assigned';
        }
        
        // Count absences from leaves table (approved leaves in current month)
        $absences = DB::table('leaves')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();
        
        // Calculate deductions (17% for taxes/contributions)
        $salary = floatval($user->salary ?? 0);
        $baseDeductions = $salary * 0.17;
        
        return Inertia::render('Employee/Salary/Index', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->employee_id ?? $user->id,
                'name' => $user->name,
                'department' => $department,
            ],
            'salary' => [
                'full_salary' => $salary,
                'deductions' => $baseDeductions,
            ],
            'absences' => $absences,
        ]);
    }
}