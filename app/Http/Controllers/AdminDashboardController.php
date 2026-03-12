<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Department;
use App\Models\Leave;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalEmployees' => User::whereIn('role', ['employee', 'hr'])->count(),
                'totalDepartments' => Department::count(),
                'monthlyPay' => 0,
                'leavePending' => Leave::where('status', 'pending')->count(),
                'leaveApproved' => Leave::where('status', 'approved')->count(),
                'leaveRejected' => Leave::where('status', 'rejected')->count(),
            ]
        ]);
    }
}