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

        // Check if HR has generated payroll for this month
        $payroll = DB::table('payrolls')
            ->where('employee_id', $user->employee_id)
            ->where('month', $currentMonth)
            ->first();

        if ($payroll) {
            // ── HR generated payroll exists ──
            // Read attendance from DB — use stored late_minutes & hours_worked
            // (these were written by HR when payroll was generated)
            $currentMonthNum    = Carbon::now()->month;
            $currentYear        = Carbon::now()->year;
            $workingDaysInMonth = 22;

            $allRecords = DB::table('attendances')
                ->where('employee_id', $user->employee_id)
                ->whereMonth('date', $currentMonthNum)
                ->whereYear('date', $currentYear)
                ->get();

            $markedAbsences     = $allRecords->where('status', 'absent')->count();
            $daysWithRecords    = $allRecords->count();
            $daysWithoutRecords = max(0, $workingDaysInMonth - $daysWithRecords);
            $totalAbsences      = $markedAbsences + $daysWithoutRecords;

            $lateCount        = 0;
            $totalLateMinutes = 0;
            $totalHoursWorked = 0;
            $undertimeHours   = 0;
            $presentDays      = 0;

            foreach ($allRecords->where('status', '!=', 'absent') as $record) {
                if (!$record->check_in || !$record->check_out) continue;

                $presentDays++;

                // Use stored late_minutes from DB (set by HR on generate)
                if ($record->late_minutes > 0) {
                    $lateCount++;
                    $totalLateMinutes += $record->late_minutes;
                }

                // Use stored hours_worked from DB
                $hoursWorked = floatval($record->hours_worked ?? 0);
                $totalHoursWorked += $hoursWorked;

                if ($hoursWorked < 8.0) {
                    $undertimeHours += (8.0 - $hoursWorked);
                }
            }

            // Use the SAVED payroll deductions — not a recalculation
            // This guarantees employee sees exactly what HR generated
            $savedDeductions        = floatval($payroll->deductions);
            $savedNet               = floatval($payroll->net_salary);
            $savedBasic             = floatval($payroll->basic_salary);

            // Breakdown for display (recalculate proportionally from saved total)
            $dailyRate              = $savedBasic / $workingDaysInMonth;
            $hourlyRate             = $dailyRate / 8;
            $absenceDeduction       = round($dailyRate * $totalAbsences, 2);
            $lateDeduction          = round(($totalLateMinutes / 60) * $hourlyRate, 2);
            $undertimeDeduction     = round($undertimeHours * $hourlyRate, 2);

            return Inertia::render('Employee/Salary/Index', [
                'employee' => [
                    'id'          => $user->id,
                    'employee_id' => $user->employee_id ?? $user->id,
                    'name'        => $user->name,
                    'department'  => $department,
                ],
                'salary' => [
                    'full_salary' => $savedBasic,
                    'deductions'  => $savedDeductions,
                ],
                'attendance' => [
                    'absences'           => $totalAbsences,
                    'late_count'         => $lateCount,
                    'total_late_minutes' => round($totalLateMinutes, 0),
                    'total_hours_worked' => round($totalHoursWorked, 2),
                    'expected_hours'     => 176,
                    'undertime_hours'    => round($undertimeHours, 2),
                    'present_days'       => $presentDays,
                    'total_working_days' => $workingDaysInMonth,
                ],
                'deductions' => [
                    'absence_deduction'   => $absenceDeduction,
                    'late_deduction'      => $lateDeduction,
                    'undertime_deduction' => $undertimeDeduction,
                    'total_deductions'    => $savedDeductions, // use saved total
                ],
                'netSalary'        => $savedNet, // use saved net — matches HR exactly
                'payrollGenerated' => true,
            ]);
        }

        // ── No payroll generated yet ──
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
                'present_days'       => 0,
                'total_working_days' => 22,
            ],
            'deductions' => [
                'absence_deduction'   => 0,
                'late_deduction'      => 0,
                'undertime_deduction' => 0,
                'total_deductions'    => 0,
            ],
            'netSalary'        => $grossSalary,
            'payrollGenerated' => false,
        ]);
    }
}