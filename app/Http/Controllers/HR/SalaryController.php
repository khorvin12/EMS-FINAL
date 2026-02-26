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
                $join->on('users.id', '=', 'payrolls.employee_id')
                    ->where('payrolls.month', '=', $currentMonth);
            })
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                'users.role',
                'departments.name as department',
                'users.salary',
                'payrolls.net_salary',
                'payrolls.deductions',
                'payrolls.absence_deduction',
                'payrolls.late_deduction',
                'payrolls.undertime_deduction',
                'payrolls.overtime_pay'
            )
            ->whereNotNull('users.salary')
            ->where('users.salary', '>', 0)
            ->where('users.role', '!=', 'admin')
            ->orderBy('users.id')
            ->get()
            ->map(function ($user) {
                $salary    = floatval($user->salary ?? 0);
                $generated = $user->net_salary !== null;
                $netSalary = $generated ? floatval($user->net_salary) : $salary;

                return [
                    'id'                  => $user->id,
                    'employee_id'         => $user->id,
                    'employee_name'       => $user->name,
                    'role'                => $user->role,
                    'department'          => $user->department ?? 'Not Assigned',
                    'full_salary'         => $salary,
                    'net_salary'          => $netSalary,
                    'total_deductions'    => $generated ? floatval($user->deductions)          : null,
                    'absence_deduction'   => $generated ? floatval($user->absence_deduction)   : null,
                    'late_deduction'      => $generated ? floatval($user->late_deduction)      : null,
                    'undertime_deduction' => $generated ? floatval($user->undertime_deduction) : null,
                    'overtime_pay'        => $generated ? floatval($user->overtime_pay ?? 0)   : null,
                    'payroll_generated'   => $generated,
                ];
            });

        return Inertia::render('HR/Salaries/Index', [
            'salaries' => $salaries
        ]);
    }

    public function view($userId)
    {
        $user = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                'departments.name as department',
                'users.salary'
            )
            ->first();

        if (!$user) {
            return redirect()->route('hr.salaries.index')
                ->with('error', 'Employee not found');
        }

        $department         = $user->department ?? 'Not Assigned';
        $salary             = floatval($user->salary ?? 0);
        $currentMonth       = Carbon::now()->format('F Y');
        $currentMonthNum    = Carbon::now()->month;
        $currentYear        = Carbon::now()->year;
        $workingDaysInMonth = 22;

        $payroll = DB::table('payrolls')
            ->where('employee_id', $userId)
            ->where('month', $currentMonth)
            ->first();

        $allRecords = DB::table('attendances')
            ->where('employee_id', $userId)
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
        $overtimeHours    = 0;
        $presentDays      = 0;

        foreach ($allRecords->where('status', '!=', 'absent') as $record) {
            if (!$record->check_in || !$record->check_out) continue;
            $presentDays++;

            if ($record->late_minutes > 0) {
                $lateCount++;
                $totalLateMinutes += $record->late_minutes;
            }

            $workedHours = floatval($record->hours_worked ?? 0);

            if ($workedHours >= 8.0) {
                $totalHoursWorked += 8.0;
                $overtimeHours    += round($workedHours - 8.0, 2);
            } else {
                $totalHoursWorked += $workedHours;
                $undertimeHours   += max(0, 8.0 - $workedHours);
            }
        }

        $dailyRate   = $salary / $workingDaysInMonth;
        $hourlyRate  = $dailyRate / 8;
        $overtimePay = round($overtimeHours * ($hourlyRate * 1.25), 2);

        return Inertia::render('HR/Salaries/View', [
            'employee' => [
                'id'         => $user->id,
                'employee_id'=> $user->id,
                'name'       => $user->name,
                'department' => $department,
            ],
            'salary' => [
                'full_salary' => $salary,
            ],
            'attendance' => [
                'absences'           => $totalAbsences,
                'late_count'         => $lateCount,
                'total_late_minutes' => round($totalLateMinutes, 0),
                'total_hours_worked' => round($totalHoursWorked, 2),
                'expected_hours'     => 176,
                'undertime_hours'    => round($undertimeHours, 2),
                'overtime_hours'     => round($overtimeHours, 2),
                'overtime_pay'       => $overtimePay,
                'present_days'       => $presentDays,
                'total_working_days' => $workingDaysInMonth,
            ],
            'payrollGenerated' => $payroll !== null,
            'payroll' => $payroll ? [
                'basic_salary' => $payroll->basic_salary,
                'deductions'   => $payroll->deductions,
                'net_salary'   => $payroll->net_salary,
                'overtime_pay' => $payroll->overtime_pay ?? 0,
            ] : null
        ]);
    }

    public function generatePayroll(Request $request, $userId)
    {
        $user = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                'departments.name as department',
                'users.salary'
            )
            ->first();

        if (!$user) {
            return redirect()->route('hr.salaries.index')
                ->with('error', 'Employee not found');
        }

        $department         = $user->department ?? 'Not Assigned';
        $grossSalary        = floatval($user->salary ?? 0);
        $currentMonth       = Carbon::now()->month;
        $currentYear        = Carbon::now()->year;
        $workingDaysInMonth = 22;

        $allAttendanceRecords = DB::table('attendances')
            ->where('employee_id', $userId)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        $markedAbsences     = $allAttendanceRecords->where('status', 'absent')->count();
        $daysWithRecords    = $allAttendanceRecords->count();
        $daysWithoutRecords = max(0, $workingDaysInMonth - $daysWithRecords);
        $totalAbsences      = $markedAbsences + $daysWithoutRecords;

        $lateCount        = 0;
        $totalLateMinutes = 0;
        $totalHoursWorked = 0;
        $undertimeHours   = 0;
        $overtimeHours    = 0;
        $presentDays      = 0;

        foreach ($allAttendanceRecords->where('status', '!=', 'absent') as $record) {
            if (!$record->check_in || !$record->check_out) continue;
            $presentDays++;

            $checkInDateTime  = Carbon::parse($record->date . ' ' . $record->check_in);
            $checkOutDateTime = Carbon::parse($record->date . ' ' . $record->check_out);
            $scheduleStart    = Carbon::parse($record->date . ' 08:00:00');

            if ($checkInDateTime->greaterThan($scheduleStart)) {
                $lateMinutes       = $scheduleStart->diffInMinutes($checkInDateTime);
                $lateCount++;
                $totalLateMinutes += $lateMinutes;
                DB::table('attendances')->where('id', $record->id)->update(['late_minutes' => $lateMinutes]);
            }

            $workedMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime) - 60;
            $workedHours   = max(0, round($workedMinutes / 60, 2));

            if ($workedHours >= 8.0) {
                $overtimeHours += round($workedHours - 8.0, 2);
            } else {
                $undertimeHours += max(0, 8.0 - $workedHours);
            }

            // Store actual hours worked so view() can display overtime correctly
            $totalHoursWorked += min(8.0, $workedHours);
            DB::table('attendances')->where('id', $record->id)->update(['hours_worked' => $workedHours]);
        }

        $dailyRate          = $grossSalary / $workingDaysInMonth;
        $hourlyRate         = $dailyRate / 8;
        $overtimeRate       = $hourlyRate * 1.25;
        $absenceDeduction   = round($dailyRate * $totalAbsences, 2);
        $lateDeduction      = round(($totalLateMinutes / 60) * $hourlyRate, 2);
        $undertimeDeduction = round($undertimeHours * $hourlyRate, 2);
        $overtimePay        = round($overtimeHours * $overtimeRate, 2);
        $totalDeductions    = $absenceDeduction + $lateDeduction + $undertimeDeduction;
        $netSalary          = $grossSalary - $totalDeductions + $overtimePay;
        $currentMonthLabel  = Carbon::now()->format('F Y');

        DB::table('payrolls')->updateOrInsert(
            ['employee_id' => $userId, 'month' => $currentMonthLabel],
            [
                'basic_salary'        => $grossSalary,
                'deductions'          => $totalDeductions,
                'net_salary'          => $netSalary,
                'present_days'        => $presentDays,
                'absences'            => $totalAbsences,
                'late_count'          => $lateCount,
                'total_late_minutes'  => round($totalLateMinutes, 0),
                'total_hours_worked'  => round($totalHoursWorked, 2),
                'expected_hours'      => 176,
                'undertime_hours'     => round($undertimeHours, 2),
                'total_working_days'  => $workingDaysInMonth,
                'absence_deduction'   => $absenceDeduction,
                'late_deduction'      => $lateDeduction,
                'undertime_deduction' => $undertimeDeduction,
                'overtime_hours'      => round($overtimeHours, 2),
                'overtime_pay'        => $overtimePay,
                'generated_by'        => Auth::id(),
                'generated_at'        => Carbon::now(),
                'year'                => Carbon::now()->year,
                'updated_at'          => Carbon::now(),
                'created_at'          => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );

        return redirect()->route('hr.salaries.view', $userId)
            ->with('success', 'Payroll generated successfully!');
    }
}