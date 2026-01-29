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
        return Inertia::render('Admin/Dashboard');
    }

    public function getStats()
    {
        return response()->json([
            'totalEmployees' => User::where('role', 'employee')->count(),
            'totalDepartments' => Department::count(),
            'monthlyPay' => 0, // You can calculate this based on your needs
            'leavePending' => Leave::where('status', 'pending')->count(),
            'leaveApproved' => Leave::where('status', 'approved')->count(),
            'leaveRejected' => Leave::where('status', 'rejected')->count(),
        ]);
    }
}