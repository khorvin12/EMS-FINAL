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
        $currentMonth = Carbon::now()->format('F Y');

        $monthlyPay = DB::table('payrolls')
            ->where('month', $currentMonth)
            ->sum('net_salary');

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalEmployees' => User::whereIn('role', ['employee', 'hr'])->count(),
                'totalDepartments' => Department::count(),
                'monthlyPay' => floatval($monthlyPay),
                'leavePending' => Leave::where('status', 'pending')->count(),
                'leaveApproved' => Leave::where('status', 'approved')->count(),
                'leaveRejected' => Leave::where('status', 'rejected')->count(),
            ]
        ]);
    }
}