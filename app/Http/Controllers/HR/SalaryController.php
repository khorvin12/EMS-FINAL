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
    public function index()
    {
        $currentMonth = Carbon::now()->format('F Y');

        $salaries = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->leftJoin('payrolls', function ($join) use ($currentMonth) {
                $join->on('users.employee_id', '=', 'payrolls.employee_id')
                    ->where('payrolls.month', '=', $currentMonth);
            })
            ->select(
                'users.id',
                'users.employee_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
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
                    'id'            => $user->id,
                    'employee_id'   => $user->employee_id,
                    'employee_name' => $user->name,
                    'department'    => $user->department ?? 'Not Assigned',
                    'full_salary'   => $salary,
                    'net_salary'    => $netSalary,
                ];
            });

        return Inertia::render('HR/Salaries/Index', [
            'salaries' => $salaries
        ]);
    }

    public function view($employeeId)
    {
        $user = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.employee_id', $employeeId)
            ->select(
                'users.id',
                'users.employee_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
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

        $currentMonth = Carbon::now()->format('F Y');

        $payroll = DB::table('payrolls')
            ->where('employee_id', $employeeId)
            ->where('month', $currentMonth)
            ->first();

        return Inertia::render('HR/Salaries/View', [
            'employee' => [
                'id'          => $user->id,
                'employee_id' => $user->employee_id,
                'name'        => $user->name,
                'department'  => $department,
            ],
            'salary' => [
                'full_salary' => $salary,
            ],
            'attendance' => [
                'absences'            => 0,
                'late_count'          => 0,
                'total_late_minutes'  => 0,
                'total_hours_worked'  => 0,
                'expected_hours'      => 176,
                'undertime_hours'     => 0,
                'present_days'        => 0,
                'total_working_days'  => 22,
            ],
            'payrollGenerated' => $payroll !== null,
            'payroll' => $payroll ? [
                'basic_salary' => $payroll->basic_salary,
                'deductions'   => $payroll->deductions,
                'net_salary'   => $payroll->net_salary,
            ] : null
        ]);
    }

    public function generatePayroll(Request $request, $employeeId)
    {
        $user = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.employee_id', $employeeId)
            ->select(
                'users.id',
                'users.employee_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
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

        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;
        $workingDaysInMonth = 22;

        $allAttendanceRecords = DB::table('attendances')
            ->where('employee_id', $user->employee_id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        $markedAbsences      = $allAttendanceRecords->where('status', 'absent')->count();
        $daysWithRecords     = $allAttendanceRecords->count();
        $daysWithoutRecords  = max(0, $workingDaysInMonth - $daysWithRecords);
        $totalAbsences       = $markedAbsences + $daysWithoutRecords;

        $workingRecords = $allAttendanceRecords->where('status', '!=', 'absent');

        $lateCount        = 0;
        $totalLateMinutes = 0;
        $totalHoursWorked = 0;
        $undertimeHours   = 0;
        $presentDays      = 0;

        foreach ($workingRecords as $record) {
            if (!$record->check_in || !$record->check_out) continue;

            $presentDays++;

            $checkInDateTime  = Carbon::parse($record->date . ' ' . $record->check_in);
            $checkOutDateTime = Carbon::parse($record->date . ' ' . $record->check_out);
            $scheduleStart    = Carbon::parse($record->date . ' 08:00:00');
            $scheduleEnd      = Carbon::parse($record->date . ' 17:00:00');

            $lateMinutes = 0;
            if ($checkInDateTime->greaterThan($scheduleStart)) {
                $lateMinutes = $scheduleStart->diffInMinutes($checkInDateTime);
                $lateCount++;
                $totalLateMinutes += $lateMinutes;
                DB::table('attendances')->where('id', $record->id)->update(['late_minutes' => $lateMinutes]);
            }

            if ($checkOutDateTime->greaterThanOrEqualTo($scheduleEnd)) {
                $hoursWorked = 8.0;
            } else {
                $workedMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime);
                $workedMinutes -= 60;
                $hoursWorked    = max(0, round($workedMinutes / 60, 2));
                $undertimeHours += max(0, 8.0 - $hoursWorked);
            }

            $totalHoursWorked += $hoursWorked;
            DB::table('attendances')->where('id', $record->id)->update(['hours_worked' => $hoursWorked]);
        }

        $dailyRate  = $grossSalary / $workingDaysInMonth;
        $hourlyRate = $dailyRate / 8;

        $absenceDeduction  = round($dailyRate * $totalAbsences, 2);
        $lateDeduction     = round(($totalLateMinutes / 60) * $hourlyRate, 2);
        $undertimeDeduction = round($undertimeHours * $hourlyRate, 2);
        $totalDeductions   = $absenceDeduction + $lateDeduction + $undertimeDeduction;
        $netSalary         = $grossSalary - $totalDeductions;

        $currentMonthLabel = Carbon::now()->format('F Y');

        DB::table('payrolls')->updateOrInsert(
            ['employee_id' => $user->employee_id, 'month' => $currentMonthLabel],
            [
                'basic_salary' => $grossSalary,
                'deductions'   => $totalDeductions,
                'net_salary'   => $netSalary,
                'updated_at'   => Carbon::now(),
                'created_at'   => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );

        return Inertia::render('HR/Salaries/View', [
            'employee' => [
                'id'          => $user->id,
                'employee_id' => $user->employee_id,
                'name'        => $user->name,
                'department'  => $department,
            ],
            'salary' => [
                'full_salary' => $grossSalary,
            ],
            'attendance' => [
                'absences'            => $totalAbsences,
                'late_count'          => $lateCount,
                'total_late_minutes'  => round($totalLateMinutes, 0),
                'total_hours_worked'  => round($totalHoursWorked, 2),
                'expected_hours'      => 176,
                'undertime_hours'     => round($undertimeHours, 2),
                'present_days'        => $presentDays,
                'total_working_days'  => $workingDaysInMonth,
            ],
            'payrollGenerated' => true,
            'payroll' => [
                'basic_salary' => $grossSalary,
                'deductions'   => $totalDeductions,
                'net_salary'   => $netSalary,
            ]
        ]);
    }
}