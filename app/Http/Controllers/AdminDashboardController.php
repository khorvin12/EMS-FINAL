<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;

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
        ]);
    }
}