<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HRController;

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

        // Employees management
        Route::inertia('/manageemployee', 'Admin/ManageEmployees/ManageEmployee')
            ->name('manageemployees');

        // Show Add New Employee form
        Route::inertia('/employees/create', 'Admin/ManageEmployees/AddnewEmployee')
            ->name('employees.create');

        // Store new employee
        Route::post('/employees', [EmployeeController::class, 'store'])
            ->name('employees.store');

        // HR management
        Route::post('/hr', [HRController::class, 'store'])
            ->name('hr.store');

        // Admin Settings
        Route::inertia('/adminsettings', 'Admin/Settings/adminsettings')
            ->name('adminsettings');

        // Admin Leaves Management
        Route::inertia('/manageleaves', 'Admin/ManageLeaves/Leaves')
            ->name('manageleaves');

        // Leaves Buttons
        Route::inertia(
            '/AdminLeavesReview',
            'Admin/ManageLeaves/LeavesButton/LeavesReview'
        )->name('ReviewLeaves');
    });

/*
|--------------------------------------------------------------------------
| Employee Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', EmployeeMiddleware::class])->prefix('employee')->name('employee.')->group(function () {

    Route::get('/dashboard', fn() => Inertia::render('Employee/Dashboard'))
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| HR Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', HRMiddleware::class])->prefix('hr')->name('hr.')->group(function () {

    Route::get('/dashboard', fn() => Inertia::render('HR/Dashboard'))
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Leaves Management Routes (Protected)
|--------------------------------------------------------------------------
*/