<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Department;
use App\Models\Leave;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Manila');

        $presentToday = DB::table('attendances')
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereIn('status', ['present', 'late'])
            ->count();

        $totalEmployees = User::where('role', 'employee')->count();
        $absentToday    = max(0, $totalEmployees - $presentToday);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'presentToday'   => $presentToday,
                'absentToday'    => $absentToday,
                'totalEmployees' => $totalEmployees,
                'leavePending'   => Leave::where('status', 'pending')->count(),
                'leaveApproved'  => Leave::where('status', 'approved')->count(),
                'leaveRejected'  => Leave::where('status', 'rejected')->count(),
            ]
        ]);
    }

    public function getStats()
    {
        return response()->json([
            'totalEmployees'   => User::whereIn('role', ['employee', 'hr'])->count(),
            'totalDepartments' => Department::count(),
            'leavePending'     => Leave::where('status', 'pending')->count(),
            'leaveApproved'    => Leave::where('status', 'approved')->count(),
            'leaveRejected'    => Leave::where('status', 'rejected')->count(),
        ]);
    }
}