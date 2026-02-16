<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Display a listing of all employee salaries
     */
    public function index()
    {
        $currentMonth = Carbon::now()->format('F Y');

        // Get all employees with their salary information and payroll data
        $salaries = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->leftJoin('payrolls', function ($join) use ($currentMonth) {
                $join->on('users.employee_id', '=', 'payrolls.employee_id')
                    ->where('payrolls.month', '=', $currentMonth);
            })
            ->select(
                'users.id',
                'users.employee_id',
                'users.name',
                'departments.name as department',
                'users.salary',
                'payrolls.net_salary'
            )
            ->whereNotNull('users.salary')
            ->where('users.salary', '>', 0)
            ->orderBy('users.employee_id')
            ->get()
            ->map(function ($user) {
                $salary = floatval($user->salary ?? 0);
                $netSalary = $user->net_salary ? floatval($user->net_salary) : $salary;

                return [
                    'id' => $user->id,
                    'employee_id' => $user->employee_id,
                    'employee_name' => $user->name,
                    'department' => $user->department ?? 'Not Assigned',
                    'full_salary' => $salary,
                    'net_salary' => $netSalary,
                ];
            });

        return Inertia::render('HR/Salaries/Index', [
            'salaries' => $salaries
        ]);
    }

    /**
     * View individual employee salary details
     */
    public function view($employeeId)
    {
        // Get employee information
        $user = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.employee_id', $employeeId)
            ->select(
                'users.id',
                'users.employee_id',
                'users.name',
                'departments.name as department',
                'users.salary'
            )
            ->first();

        if (!$user) {
            return redirect()->route('hr.salaries.index')
                ->with('error', 'Employee not found');
        }

        $department = $user->department ?? 'Not Assigned';
        $salary = floatval($user->salary ?? 0);

        // Check if payroll has been generated for current month
        $currentMonth = Carbon::now()->format('F Y'); // e.g., "February 2026"

        $payroll = DB::table('payrolls')
            ->where('employee_id', $employeeId)
            ->where('month', $currentMonth)
            ->first();

        return Inertia::render('HR/Salaries/View', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->employee_id,
                'name' => $user->name,
                'department' => $department,
            ],
            'salary' => [
                'full_salary' => $salary,
            ],
            'attendance' => [
                'absences' => 0,
                'late_count' => 0,
                'total_late_minutes' => 0,
                'total_hours_worked' => 0,
                'expected_hours' => 176,
                'undertime_hours' => 0,
                'present_days' => 0,
                'total_working_days' => 22,
            ],
            'payrollGenerated' => $payroll !== null,
            'payroll' => $payroll ? [
                'basic_salary' => $payroll->basic_salary,
                'deductions' => $payroll->deductions,
                'net_salary' => $payroll->net_salary,
            ] : null
        ]);
    }

    /**
     * Generate payroll by fetching attendance data and calculating deductions
     */
    public function generatePayroll(Request $request, $employeeId)
    {
        // Get employee information
        $user = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.employee_id', $employeeId)
            ->select(
                'users.id',
                'users.employee_id',
                'users.name',
                'departments.name as department',
                'users.salary'
            )
            ->first();

        if (!$user) {
            return redirect()->route('hr.salaries.index')
                ->with('error', 'Employee not found');
        }

        $department = $user->department ?? 'Not Assigned';
        $grossSalary = floatval($user->salary ?? 0);

        // Get current month's data
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $workingDaysInMonth = 22; // Total working days in a month

        /* ---------------- COUNT ABSENCES (Full days not worked) ---------------- */

        // Get all attendance records for the month
        $allAttendanceRecords = DB::table('attendances')
            ->where('employee_id', $user->employee_id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        // Count days with status = 'absent'
        $markedAbsences = $allAttendanceRecords->where('status', 'absent')->count();

        // Count days with no attendance records at all
        $daysWithRecords = $allAttendanceRecords->count();
        $daysWithoutRecords = max(0, $workingDaysInMonth - $daysWithRecords);

        // Total absences = marked absences + days without any records
        $totalAbsences = $markedAbsences + $daysWithoutRecords;

        /* ---------------- CALCULATE HOURS WORKED & UNDERTIME ---------------- */

        // Get only records where employee actually came to work (has check-in)
        $workingRecords = $allAttendanceRecords->where('status', '!=', 'absent');

        $lateCount = 0;
        $totalLateMinutes = 0;
        $totalHoursWorked = 0;
        $undertimeHours = 0;
        $presentDays = 0;

        foreach ($workingRecords as $record) {
            // Skip if no check-in
            if (!$record->check_in) {
                continue;
            }

            // Skip if no check-out (incomplete record)
            if (!$record->check_out) {
                continue;
            }

            $presentDays++; // Count this as a working day

            $checkInDateTime = Carbon::parse($record->date . ' ' . $record->check_in);
            $checkOutDateTime = Carbon::parse($record->date . ' ' . $record->check_out);

            $scheduleStart = Carbon::parse($record->date . ' 08:00:00');
            $scheduleEnd = Carbon::parse($record->date . ' 17:00:00');

            /* -------- LATE (penalty only for arriving late) -------- */
            $lateMinutes = 0;
            if ($checkInDateTime->greaterThan($scheduleStart)) {
                $lateMinutes = $scheduleStart->diffInMinutes($checkInDateTime);
                $lateCount++;
                $totalLateMinutes += $lateMinutes;

                // Update database with calculated late_minutes
                DB::table('attendances')
                    ->where('id', $record->id)
                    ->update(['late_minutes' => $lateMinutes]);
            }

            /* -------- HOURS WORKED & UNDERTIME -------- */
            // If employee stayed until 5:00 PM or later = full 8 hours
            if ($checkOutDateTime->greaterThanOrEqualTo($scheduleEnd)) {
                $hoursWorked = 8.0;
            } else {
                // Left early - calculate actual hours worked
                $workedMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime);
                $workedMinutes -= 60; // Subtract 1 hour lunch break
                $hoursWorked = max(0, round($workedMinutes / 60, 2));

                // Calculate undertime for this day
                $dailyUndertime = max(0, 8.0 - $hoursWorked);
                $undertimeHours += $dailyUndertime;
            }

            $totalHoursWorked += $hoursWorked;

            // Update database with calculated hours_worked
            DB::table('attendances')
                ->where('id', $record->id)
                ->update(['hours_worked' => $hoursWorked]);
        }

        /* ---------------- CALCULATE DEDUCTIONS ---------------- */

        $dailyRate = $grossSalary / $workingDaysInMonth;
        $hourlyRate = $dailyRate / 8;

        // Absence deduction (full day rate per absence)
        $absenceDeduction = round($dailyRate * $totalAbsences, 2);

        // Late deduction (based on hourly rate and total late minutes)
        $lateDeduction = round(($totalLateMinutes / 60) * $hourlyRate, 2);

        // Undertime deduction (hourly rate × undertime hours)
        $undertimeDeduction = round($undertimeHours * $hourlyRate, 2);

        // Total deductions
        $totalDeductions = $absenceDeduction + $lateDeduction + $undertimeDeduction;

        // Net salary
        $netSalary = $grossSalary - $totalDeductions;

        /* ---------------- SAVE PAYROLL RECORD ---------------- */

        $currentMonthLabel = Carbon::now()->format('F Y'); // e.g., "February 2026"

        DB::table('payrolls')->updateOrInsert(
            [
                'employee_id' => $user->employee_id,
                'month' => $currentMonthLabel,
            ],
            [
                'basic_salary' => $grossSalary,
                'deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'updated_at' => Carbon::now(),
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );

        return Inertia::render('HR/Salaries/View', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->employee_id,
                'name' => $user->name,
                'department' => $department,
            ],
            'salary' => [
                'full_salary' => $grossSalary,
            ],
            'attendance' => [
                'absences' => $totalAbsences,
                'late_count' => $lateCount,
                'total_late_minutes' => round($totalLateMinutes, 0),
                'total_hours_worked' => round($totalHoursWorked, 2),
                'expected_hours' => 176,
                'undertime_hours' => round($undertimeHours, 2),
                'present_days' => $presentDays,
                'total_working_days' => $workingDaysInMonth,
            ],
            'payrollGenerated' => true,
            'payroll' => [
                'basic_salary' => $grossSalary,
                'deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]
        ]);
    }
}