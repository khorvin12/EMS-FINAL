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
    // Cutoff time for late — must match AttendanceController
    private const SCHEDULE_START = '09:00:00';

    public function index()
    {
        $currentMonth = Carbon::now()->format('F Y');

        $rankMap = DB::table('users')
            ->whereNotNull('salary')
            ->where('salary', '>', 0)
            ->where('role', 'employee')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->values()
            ->flip()
            ->map(fn($i) => $i + 1);

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
                'payrolls.net_salary'
            )
            ->whereNotNull('users.salary')
            ->where('users.salary', '>', 0)
            ->where('users.role', 'employee')
            ->orderBy('users.id', 'desc')
            ->paginate(6)
            ->withQueryString();

        $salaries->getCollection()->transform(function ($user) use ($rankMap) {
            $salary = floatval($user->salary ?? 0);
            $netSalary = $user->net_salary ? floatval($user->net_salary) : $salary;

            return [
                'id' => $user->id,
                'serial_no' => $rankMap[$user->id] ?? null,
                'employee_id' => $user->id,
                'employee_name' => $user->name,
                'role' => $user->role,
                'department' => $user->department ?? 'Not Assigned',
                'full_salary' => $salary,
                'net_salary' => $netSalary,
            ];
        });

        return Inertia::render('HR/Salaries/Index', [
            'salaries' => $salaries,
            'filters' => request()->only(['search']),
        ]);
    }

    public function view($userId)
    {
        $user = $this->getEmployee($userId);

        if (!$user) {
            return redirect()->route('hr.salary.index')
                ->with('error', 'Employee not found');
        }

        $currentMonth = Carbon::now()->format('F Y');
        $currentMonthNum = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $workingDays = 22;

        $stats = $this->calculateAttendanceStats($userId, $currentMonthNum, $currentYear, $workingDays);

        $payroll = DB::table('payrolls')
            ->where('employee_id', $userId)
            ->where('month', $currentMonth)
            ->first();

        return Inertia::render('HR/Salaries/View', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->id,
                'name' => $user->name,
                'department' => $user->department ?? 'Not Assigned',
            ],
            'salary' => [
                'full_salary' => floatval($user->salary ?? 0),
            ],
            'attendance' => $stats,
            'payrollGenerated' => $payroll !== null,
            'payroll' => $payroll ? [
                'basic_salary' => $payroll->basic_salary,
                'deductions' => $payroll->deductions,
                'net_salary' => $payroll->net_salary,
                'overtime_pay' => $payroll->overtime_pay ?? 0,
            ] : null,
        ]);
    }

    public function generatePayroll(Request $request, $userId)
    {
        $user = $this->getEmployee($userId);

        if (!$user) {
            return redirect()->route('hr.salary.index')
                ->with('error', 'Employee not found');
        }

        $grossSalary = floatval($user->salary ?? 0);
        $currentMonthNum = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->format('F Y');
        $workingDays = 22;

        $stats = $this->calculateAttendanceStats($userId, $currentMonthNum, $currentYear, $workingDays, true);
        $dailyRate = $grossSalary / $workingDays;
        $hourlyRate = $dailyRate / 8;

        $absenceDeduction = round($dailyRate * $stats['absences'], 2);
        $lateDeduction = round(($stats['total_late_minutes'] / 60) * $hourlyRate, 2);
        $undertimeDeduction = round($stats['undertime_hours'] * $hourlyRate, 2);
        $overtimePay = round($stats['overtime_hours'] * ($hourlyRate * 1.25), 2);
        $totalDeductions = $absenceDeduction + $lateDeduction + $undertimeDeduction;
        $netSalary = $grossSalary - $totalDeductions + $overtimePay;

        $now = Carbon::now();

        // Check if payroll already exists
        $exists = DB::table('payrolls')
            ->where('employee_id', $userId)
            ->where('month', $currentMonth)
            ->exists();

        if ($exists) {
            DB::table('payrolls')
                ->where('employee_id', $userId)
                ->where('month', $currentMonth)
                ->update([
                    'basic_salary' => $grossSalary,
                    'deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'present_days' => $stats['present_days'],
                    'absences' => $stats['absences'],
                    'late_count' => $stats['late_count'],
                    'total_late_minutes' => $stats['total_late_minutes'],
                    'total_hours_worked' => $stats['total_hours_worked'],
                    'expected_hours' => 176,
                    'undertime_hours' => $stats['undertime_hours'],
                    'total_working_days' => $workingDays,
                    'absence_deduction' => $absenceDeduction,
                    'late_deduction' => $lateDeduction,
                    'undertime_deduction' => $undertimeDeduction,
                    'overtime_hours' => $stats['overtime_hours'],
                    'overtime_pay' => $overtimePay,
                    'generated_by' => Auth::id(),
                    'generated_at' => $now,
                    'year' => $currentYear,
                    'updated_at' => $now,
                ]);
        } else {
            DB::table('payrolls')->insert([
                'employee_id' => $userId,
                'month' => $currentMonth,
                'basic_salary' => $grossSalary,
                'deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'present_days' => $stats['present_days'],
                'absences' => $stats['absences'],
                'late_count' => $stats['late_count'],
                'total_late_minutes' => $stats['total_late_minutes'],
                'total_hours_worked' => $stats['total_hours_worked'],
                'expected_hours' => 176,
                'undertime_hours' => $stats['undertime_hours'],
                'total_working_days' => $workingDays,
                'absence_deduction' => $absenceDeduction,
                'late_deduction' => $lateDeduction,
                'undertime_deduction' => $undertimeDeduction,
                'overtime_hours' => $stats['overtime_hours'],
                'overtime_pay' => $overtimePay,
                'generated_by' => Auth::id(),
                'generated_at' => $now,
                'year' => $currentYear,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return redirect()->route('hr.salaries.view', $userId)
            ->with('success', 'Payroll generated successfully!');
    }

    // ── Private Helpers ──────────────────────────────────────────────────────

    private function getEmployee($userId)
    {
        return DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                'departments.name as department',
                'users.salary'
            )
            ->first();
    }

    private function calculateAttendanceStats(int $userId, int $month, int $year, int $workingDays = 22, bool $updateRecords = false): array
    {
        $records = DB::table('attendances')
            ->where('employee_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $markedAbsences = $records->where('status', 'absent')->count();
        $daysWithRecords = $records->count();
        $daysWithoutRecords = max(0, $workingDays - $daysWithRecords);
        $totalAbsences = $markedAbsences + $daysWithoutRecords;

        $lateCount = 0;
        $totalLateMinutes = 0;
        $totalHoursWorked = 0;
        $undertimeHours = 0;
        $overtimeHours = 0;
        $presentDays = 0;

        foreach ($records->where('status', '!=', 'absent') as $record) {
            if (!$record->check_in || !$record->check_out)
                continue;

            $presentDays++;
            $scheduleStart = Carbon::parse($record->date . ' ' . self::SCHEDULE_START);
            $checkIn = Carbon::parse($record->date . ' ' . $record->check_in);
            $checkOut = Carbon::parse($record->date . ' ' . $record->check_out);

            // Late minutes
            if ($checkIn->greaterThan($scheduleStart)) {
                $lateMinutes = (int) $scheduleStart->diffInMinutes($checkIn);
                $lateCount++;
                $totalLateMinutes += $lateMinutes;

                if ($updateRecords) {
                    DB::table('attendances')->where('id', $record->id)->update(['late_minutes' => $lateMinutes]);
                }
            }

            // Hours worked (minus 1 hour break)
            $workedMinutes = $checkIn->diffInMinutes($checkOut) - 60;
            $workedHours = max(0, round($workedMinutes / 60, 2));

            if ($workedHours >= 8.0) {
                $totalHoursWorked += 8.0;
                $overtimeHours += round($workedHours - 8.0, 2);
            } else {
                $totalHoursWorked += $workedHours;
                $undertimeHours += max(0, 8.0 - $workedHours);
            }

            if ($updateRecords) {
                DB::table('attendances')->where('id', $record->id)->update(['hours_worked' => min($workedHours, 8.0)]);
            }
        }

        return [
            'absences' => $totalAbsences,
            'late_count' => $lateCount,
            'total_late_minutes' => (int) round($totalLateMinutes),
            'total_hours_worked' => round($totalHoursWorked, 2),
            'expected_hours' => 176,
            'undertime_hours' => round($undertimeHours, 2),
            'overtime_hours' => round($overtimeHours, 2),
            'overtime_pay' => 0, // calculated in generatePayroll
            'present_days' => $presentDays,
            'total_working_days' => $workingDays,
        ];
    }
}