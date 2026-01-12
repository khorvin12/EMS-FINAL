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
Route::middleware(['auth', AdminMiddleware::class])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Employees management
    Route::inertia('/manageemployees', 'Admin/ManageEmployees/ManageEmployee')
        ->name('manageemployees');


    Route::post('/employees', [EmployeeController::class, 'store'])
        ->name('employees.store');

    // HR management
    Route::post('/hr', [HRController::class, 'store'])
        ->name('hr.store');

    Route::inertia('/addnewemployee', 'Admin/ManageEmployees/AddnewEmployee')
        ->name('addnewemployee');

    Route::inertia('/view', 'Admin/ManageEmployees/View')
        ->name('view');

    Route::inertia('/edit', 'Admin/ManageEmployees/Edit')
        ->name('edit');

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
