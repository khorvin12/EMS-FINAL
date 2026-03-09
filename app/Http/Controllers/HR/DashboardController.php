<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Manila')->toDateString();

        // Count employees (exclude admins)
        $totalEmployees = User::where('role', 'employee')->count();

        // Present today — only employees
        $presentToday = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.id')
            ->where('attendances.date', $today)
            ->where('users.role', 'employee')
            ->whereIn('attendances.status', ['present', 'late'])
            ->count();

        // Absent today — only employees
        $withRecordToday = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.id')
            ->where('attendances.date', $today)
            ->where('users.role', 'employee')
            ->distinct('attendances.employee_id')
            ->count('attendances.employee_id');

        $absentToday = max(0, $totalEmployees - $withRecordToday);

        // Leave stats
        $leavePending = Leave::where('status', 'pending')->count();
        $leaveApproved = Leave::where('status', 'approved')->count();
        $leaveRejected = Leave::where('status', 'rejected')->count();

        return Inertia::render('HR/Index', [
            'stats' => [
                'presentToday' => $presentToday,
                'absentToday' => $absentToday,
                'totalEmployees' => $totalEmployees,
                'leavePending' => $leavePending,
                'leaveApproved' => $leaveApproved,
                'leaveRejected' => $leaveRejected,
            ]
        ]);
    }
}
