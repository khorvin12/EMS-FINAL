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
        $totalEmployees = User::whereIn('role', ['employee', 'hr'])->count();

        // Present today
        $presentToday = DB::table('attendances')
            ->where('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();

        // Absent today = total employees minus those with any attendance record today
        $withRecordToday = DB::table('attendances')
            ->where('date', $today)
            ->distinct('employee_id')
            ->count('employee_id');

        $absentToday = max(0, $totalEmployees - $withRecordToday);

        // Leave stats
        $leavePending  = Leave::where('status', 'pending')->count();
        $leaveApproved = Leave::where('status', 'approved')->count();
        $leaveRejected = Leave::where('status', 'rejected')->count();

        return Inertia::render('HR/Index', [
            'stats' => [
                'presentToday'   => $presentToday,
                'absentToday'    => $absentToday,
                'totalEmployees' => $totalEmployees,
                'leavePending'   => $leavePending,
                'leaveApproved'  => $leaveApproved,
                'leaveRejected'  => $leaveRejected,
            ]
        ]);
    }
}
