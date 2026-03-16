<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now  = Carbon::now('Asia/Manila');

        // 1. Count present days this month
        $presentDays = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->where('status', 'present')
            ->count();

        // 2. Count pending leaves
        $pendingLeaves = DB::table('leaves')
            ->where('employee_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // 3. Get last 5 attendance records
        $attendanceHistory = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($record) {
                $hours = 0;
                if ($record->check_in && $record->check_out) {
                    $hours = round(max(0, Carbon::parse($record->check_in)
                        ->diffInHours(Carbon::parse($record->check_out), true) - 1), 2);
                }
                return [
                    'id'        => $record->id,
                    'date'      => $record->date,
                    'check_in'  => $record->check_in,
                    'check_out' => $record->check_out,
                    'status'    => $record->status,
                    'hours'     => $hours,
                ];
            });

        // 4. Estimated salary — use actual payroll if generated, else gross salary
        $currentMonth = $now->format('F Y');
        $payroll = DB::table('payrolls')
            ->where('employee_id', $user->id)
            ->where('month', $currentMonth)
            ->first();

        $estimatedSalary = $payroll
            ? floatval($payroll->net_salary)
            : floatval($user->salary ?? 0);

        return Inertia::render('Employee/Dashboard', [
            'presentDays'       => $presentDays,
            'pendingLeaves'     => $pendingLeaves,
            'attendanceHistory' => $attendanceHistory,
            'estimatedSalary'   => $estimatedSalary,
        ]);
    }
}
