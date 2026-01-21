<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HRController;
use App\Http\Controllers\Admin\DepartmentController;

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
Route::middleware(['auth', AdminMiddleware::class])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Department Management
    Route::get('/departments', [DepartmentController::class, 'index'])
        ->name('departments');
    
    Route::inertia('/adddepartment', 'Admin/Departments/AddDepartment')
        ->name('adddepartment');
    
    Route::post('/adddepartment', [DepartmentController::class, 'store']);

    Route::get('/editdepartment/{id}', [DepartmentController::class, 'edit'])
        ->name('editdepartment');
    
    Route::put('/editdepartment/{id}', [DepartmentController::class, 'update']);

    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    // Employee Management Pages
    Route::inertia('/manageemployees', 'Admin/ManageEmployees/ManageEmployee')
        ->name('manageemployees');

    Route::inertia('/addnewemployee', 'Admin/ManageEmployees/AddnewEmployee')
        ->name('addnewemployee');

    Route::inertia('/view', 'Admin/ManageEmployees/View')
        ->name('view');

    Route::inertia('/edit', 'Admin/ManageEmployees/Edit')
        ->name('edit');

    // Employee API Routes
    Route::post('/employees', [EmployeeController::class, 'store'])
        ->name('employees.store');

    // HR API Routes
    Route::post('/hr', [HRController::class, 'store'])
        ->name('hr.store');

    // Dashboard Stats API
    Route::get('/api/dashboard/stats', [AdminDashboardController::class, 'getStats'])
        ->name('dashboard.stats');
});

/*
|--------------------------------------------------------------------------
| Employee Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EmployeeMiddleware::class])->prefix('employee')->name('employee.')->group(function () {

    // Employee Dashboard
    Route::get('/dashboard', fn() => Inertia::render('Employee/Index'))
        ->name('dashboard');

    // Employee Leave Routes
    Route::inertia('/leave', 'Employee/Leaves/Index')->name('leave');
    Route::inertia('/create-leave', 'Employee/Leaves/Create')->name('create-leave');
    Route::inertia('/view-leave', 'Employee/Leaves/View')->name('view-leave');

    // Employee Salary Routes
    Route::inertia('/employee-salary', 'Employee/Salary/Index')->name('employee-salary');

    // Employee Attendance Route
    Route::inertia('/attendance', 'Employee/Attendance/Index')->name('attendance');

    // Employee Settings Routes
    Route::inertia('/settings', 'Employee/Settings/Index')->name('settings');
});

/*
|--------------------------------------------------------------------------
| HR Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', HRMiddleware::class])->prefix('hr')->name('hr.')->group(function () {

    Route::get('/HR/dashboard', fn() => Inertia::render('HR/Dashboard'))
        ->name('dashboard');
});