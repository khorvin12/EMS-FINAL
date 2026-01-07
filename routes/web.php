<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HRController;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\HRMiddleware;

// Login
Route::get('/', fn () => Inertia::render('Login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/admin/employees', [EmployeeController::class, 'store']);
    Route::post('/admin/hr', [HRController::class, 'store']);
});

// Employee
Route::middleware(['auth', EmployeeMiddleware::class])->group(function () {
    Route::get('/employee/dashboard', fn () =>
        Inertia::render('Employee/Dashboard')
    )->name('employee.dashboard');
});

// HR
Route::middleware(['auth', HRMiddleware::class])->group(function () {
    Route::get('/hr/dashboard', fn () =>
        Inertia::render('HR/Dashboard')
    )->name('hr.dashboard');
});
