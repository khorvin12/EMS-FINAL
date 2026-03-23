<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ManageLeavesController;
use App\Http\Controllers\Admin\DepartmentController;

use App\Http\Controllers\HR\AttendanceController as HRAttendanceController;
use App\Http\Controllers\HR\SalaryController as HRSalaryController;
use App\Http\Controllers\HR\DashboardController as HRDashboardController;
use App\Http\Controllers\HR\ReportController as HRReportController;

use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\SalaryController;

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
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Handle logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Department Management
        Route::get('/departments', [DepartmentController::class, 'index'])
            ->name('departments');

        Route::get('/adddepartment', [DepartmentController::class, 'create'])
            ->name('adddepartment');

        Route::post('/adddepartment', [DepartmentController::class, 'store']);

        Route::get('/editdepartment/{id}', [DepartmentController::class, 'edit'])
            ->name('editdepartment');

        // Update Department
        Route::put('/editdepartment/{id}', [DepartmentController::class, 'update']);

        // Delete Department
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');


        // Employee Management Pages
        Route::get('/manageemployees', [EmployeeController::class, 'index'])
            ->name('manageemployees');

        // Add New Employee form
        Route::get('/employee/create', [EmployeeController::class, 'create'])
            ->name('employee.create');

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
            ->name('adminsettings');

        // Change Password
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.reset');

        // Admin Leaves Management
        Route::get('/manageleaves/leaves', [ManageLeavesController::class, 'index'])
            ->name('admin.manageleaves');

        Route::get('/manageleaves/leaves/review/{leave}', [ManageLeavesController::class, 'review'])
            ->name('manageleaves.review');

        Route::post('/manageleaves/leaves/{leave}/approve', [ManageLeavesController::class, 'approve'])
            ->name('manageleaves.approve');

        Route::post('/manageleaves/leaves/{leave}/reject', [ManageLeavesController::class, 'reject'])
            ->name('manageleaves.reject');

        Route::get('/reports/employees', [HRReportController::class, 'employeesPdf'])->name('reports.employees');
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
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Employee Leave Routes
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
        Route::get('/leaves/create', [LeaveController::class, 'create'])->name('create-leave');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('view-leave');

        // Employee Salary Routes
        Route::get('/salary', [SalaryController::class, 'index'])->name('employee-salary');

        // Employee Attendance Routes
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
        Route::post('/attendance/record', [AttendanceController::class, 'record'])->name('attendance.record');

        // Employee Settings Routes
        Route::inertia('/settings', 'Employee/Settings/Index')->name('settings');

        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.reset');
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

        Route::get('/dashboard', [HRDashboardController::class, 'index'])->name('dashboard');



        // HR Salary Routes
        Route::get('/salary', [HRSalaryController::class, 'index'])->name('salary.index');
        Route::get('/salaries/{employeeId}', [HRSalaryController::class, 'view'])->name('salaries.view');
        Route::post('/salaries/{employeeId}/generate-payroll', [HRSalaryController::class, 'generatePayroll'])->name('salaries.generatePayroll');

        Route::get('/leaves', [ManageLeavesController::class, 'index'])
            ->name('leaves.index');

        Route::get('/leaves/review/{leave}', [ManageLeavesController::class, 'review'])
            ->name('leaves.review');

        Route::post('/leaves/{leave}/approve', [ManageLeavesController::class, 'approve'])
            ->name('leaves.approve');

        Route::post('/leaves/{leave}/reject', [ManageLeavesController::class, 'reject'])
            ->name('leaves.reject');

        // HR Attendance - NOW USING CONTROLLER TO FETCH DATA
        Route::get('/attendance/employees', [HRAttendanceController::class, 'employees'])->name('attendance.employees'); // ← ADD FIRST
        Route::get('/attendance', [HRAttendanceController::class, 'index'])->name('attendance');
        Route::get('/attendance/{id}/edit', [HRAttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{id}', [HRAttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{id}', [HRAttendanceController::class, 'destroy'])->name('attendance.destroy');

        // HR Reports
        Route::get('/reports/attendance', [HRReportController::class, 'attendancePdf'])->name('reports.attendance');
        Route::get('/reports/payroll', [HRReportController::class, 'payrollPdf'])->name('reports.payroll');
        Route::get('/reports/employees', [HRReportController::class, 'employeesPdf'])->name('reports.employees');


        // HR Settings Routes
        Route::inertia('/settings', 'HR/Settings/Index')->name('settings');

        // Change Password
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.reset');

    });