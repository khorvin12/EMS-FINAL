<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\AttendanceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ManageLeavesController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\ProfileController;

// Middleware
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\HRMiddleware;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

// Login page
Route::get('/', fn() => Inertia::render('Auth/Login'))->name('login');

// Handle login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Handle logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', AdminMiddleware::class])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Dashboard Stats API
        Route::get('/api/dashboard/stats', [AdminDashboardController::class, 'getStats'])
            ->name('dashboard.stats');

        // Department Management
        Route::get('/departments', [DepartmentController::class, 'index'])
            ->name('departments');

        Route::inertia('/adddepartment', 'Admin/Departments/AddDepartment')
            ->name('adddepartment');

        Route::post('/adddepartment', [DepartmentController::class, 'store']);

        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])
            ->name('departments.edit');

        Route::put('/editdepartment/{id}', [DepartmentController::class, 'update']);

        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
            ->name('departments.destroy');

        // Employee Management Pages
        Route::get('/manageemployees', [EmployeeController::class, 'index'])
            ->name('manageemployees');

        Route::get('/addnewemployee', [EmployeeController::class, 'create'])
            ->name('addnewemployee');

        // Show Add New Employee form (alternative route)
        Route::get('/employees/create', [EmployeeController::class, 'create'])
            ->name('employees.create');

        Route::get('/view/{id}', [EmployeeController::class, 'show'])
            ->name('view');

        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])
            ->name('edit');

        Route::put('/edit/{id}', [EmployeeController::class, 'update'])
            ->name('employees.update');

        Route::delete('/delete/{id}', [EmployeeController::class, 'destroy'])
            ->name('employees.destroy');

        // Employee API Routes (handles both employees and HR)
        Route::post('/employees', [EmployeeController::class, 'store'])
            ->name('employees.store');

        // Admin Settings
        Route::inertia('/settings', 'Admin/Settings/AdminSettings')
            ->name('settings');

        Route::inertia('/profile', 'Admin/Settings/Profile')
            ->name('profile');

        // Change Password Route
        Route::inertia('/change-password', 'Admin/Settings/ChangePassword')
            ->name('change-password');

        // Change Password
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.change');

        Route::inertia('/support', 'Admin/Settings/CustomerSupport')
            ->name('support');

        Route::inertia('/about', 'Admin/Settings/About')
            ->name('about');

        // Admin Leaves Management
        Route::get('/manageleaves/leaves', [ManageLeavesController::class, 'index'])
            ->name('manageleaves.index');

        Route::get('/manageleaves/leaves/review/{leave}', [ManageLeavesController::class, 'review'])
            ->name('manageleaves.review');

        Route::post('/manageleaves/leaves/{leave}/approve', [ManageLeavesController::class, 'approve'])
            ->name('manageleaves.approve');

        Route::post('/manageleaves/leaves/{leave}/reject', [ManageLeavesController::class, 'reject'])
            ->name('manageleaves.reject');
    });

/*
|--------------------------------------------------------------------------
| Employee Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', EmployeeMiddleware::class])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        // Employee Dashboard
        Route::get('/dashboard', function () {

            $user = Auth::user();

            $attendanceHistory = DB::table('attendances')
                ->where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();

            $presentDays = DB::table('attendances')
                ->where('user_id', $user->id)
                ->whereMonth('date', now()->month)
                ->where('status', 'present')
                ->count();

            // Pending leave count
            $pendingLeaves = DB::table('leaves')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();

            // Total hours this month
            $totalHours = DB::table('attendances')
                ->where('user_id', $user->id)
                ->whereMonth('date', now()->month)
                ->sum('hours');

            // Example hourly rate (you can store this in users table later)
            $hourlyRate = 150;

            $estimatedSalary = $totalHours * $hourlyRate;

            return Inertia::render('Employee/Dashboard', [
                'attendanceHistory' => $attendanceHistory,
                'presentDays' => $presentDays,
                'pendingLeaves' => $pendingLeaves,
                'estimatedSalary' => $estimatedSalary,
            ]);
        })
            ->name('dashboard');


        // Employee Leave Routes
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
        Route::get('/leaves/create', [LeaveController::class, 'create'])->name('create-leave');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('view-leave');

        // Employee Salary Routes
        Route::get('/salary', [SalaryController::class, 'index'])->name('salary');

        // Employee Attendance Routes
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
        Route::post('/attendance/record', [AttendanceController::class, 'record'])->name('attendance.record');

        // Employee Settings Routes
        Route::inertia('/settings', 'Employee/Settings/Index')->name('settings');

        Route::inertia('/about', 'Employee/Settings/About')->name('about');

         Route::inertia('/support', 'Employee/Settings/CustomerSupport')->name('customer-support');

        // Change Password Route
        Route::inertia('/change-password', 'Employee/Settings/ChangePassword')
            ->name('change-password');

        // Change Password
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.change');
    });

/*
|--------------------------------------------------------------------------
| HR Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', HRMiddleware::class])
    ->prefix('hr')
    ->name('hr.')
    ->group(function () {

        Route::get('/dashboard', fn() => Inertia::render('HR/Dashboard'))
            ->name('dashboard');

        // HR Leaves Management - NOW USING THE SAME CONTROLLER AS ADMIN
        Route::get('/leaves', [ManageLeavesController::class, 'index'])
            ->name('leaves.index');

        Route::get('/leaves/review/{leave}', [ManageLeavesController::class, 'review'])
            ->name('leaves.review');

        Route::post('/leaves/{leave}/approve', [ManageLeavesController::class, 'approve'])
            ->name('leaves.approve');

        Route::post('/leaves/{leave}/reject', [ManageLeavesController::class, 'reject'])
            ->name('leaves.reject');

        // HR Attendance
        Route::inertia('/attendance', 'HR/Attendance/Index')->name('attendance');

        Route::inertia('/salary', 'HR/Salary/Index')->name('salary');

        Route::inertia('/settings', 'HR/Settings/Index')->name('settings');
        Route::inertia('/change-password', 'HR/Settings/ChangePassword')->name('change-password');
        Route::inertia('/about', 'HR/Settings/About')->name('about');
        Route::inertia('/support', 'HR/Settings/CustomerSupport')->name('customer-support');
    });