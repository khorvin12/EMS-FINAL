<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $department = 'Not Assigned';
        if ($user->department_id) {
            $dept = DB::table('departments')->where('id', $user->department_id)->first();
            $department = $dept ? $dept->name : 'Not Assigned';
        }

        $grossSalary  = floatval($user->salary ?? 0);
        $currentMonth = Carbon::now()->format('F Y');

        // Query payrolls using users.id (integer FK)
        $payroll = DB::table('payrolls')
            ->where('employee_id', $user->id)
            ->where('month', $currentMonth)
            ->first();

        if ($payroll) {
            // Read ALL values directly from the saved payroll record.
            // Guarantees employee sees exactly what HR computed and locked in.
            return Inertia::render('Employee/Salary/Index', [
                'employee' => [
                    'id'          => $user->id,
                    'employee_id' => $user->employee_id ?? $user->id,
                    'name'        => $user->name,
                    'department'  => $department,
                ],
                'salary' => [
                    'full_salary' => floatval($payroll->basic_salary),
                    'deductions'  => floatval($payroll->deductions),
                ],
                'attendance' => [
                    'absences'           => intval($payroll->absences),
                    'late_count'         => intval($payroll->late_count),
                    'total_late_minutes' => intval($payroll->total_late_minutes),
                    'total_hours_worked' => floatval($payroll->total_hours_worked),
                    'expected_hours'     => intval($payroll->expected_hours) ?: 176,
                    'undertime_hours'    => floatval($payroll->undertime_hours),
                    'overtime_hours'     => floatval($payroll->overtime_hours ?? 0),
                    'present_days'       => intval($payroll->present_days),
                    'total_working_days' => intval($payroll->total_working_days) ?: 22,
                ],
                'deductions' => [
                    'absence_deduction'   => floatval($payroll->absence_deduction),
                    'late_deduction'      => floatval($payroll->late_deduction),
                    'undertime_deduction' => floatval($payroll->undertime_deduction),
                    'total_deductions'    => floatval($payroll->deductions),
                ],
                'overtime_pay'     => floatval($payroll->overtime_pay ?? 0),
                'netSalary'        => floatval($payroll->net_salary),
                'payrollGenerated' => true,
            ]);
        }

        // No payroll generated yet
        return Inertia::render('Employee/Salary/Index', [
            'employee' => [
                'id'          => $user->id,
                'employee_id' => $user->employee_id ?? $user->id,
                'name'        => $user->name,
                'department'  => $department,
            ],
            'salary' => [
                'full_salary' => $grossSalary,
                'deductions'  => 0,
            ],
            'attendance' => [
                'absences'           => 0,
                'late_count'         => 0,
                'total_late_minutes' => 0,
                'total_hours_worked' => 0,
                'expected_hours'     => 176,
                'undertime_hours'    => 0,
                'overtime_hours'     => 0,
                'present_days'       => 0,
                'total_working_days' => 22,
            ],
            'deductions' => [
                'absence_deduction'   => 0,
                'late_deduction'      => 0,
                'undertime_deduction' => 0,
                'total_deductions'    => 0,
            ],
            'overtime_pay'     => 0,
            'netSalary'        => $grossSalary,
            'payrollGenerated' => false,
        ]);
    }
}