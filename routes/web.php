<?php

use App\Models\Department;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AuthController;
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
    // UPDATED: Use controller to fetch data
    Route::get('/manageemployees', [EmployeeController::class, 'index'])
        ->name('manageemployees');

    Route::inertia('/addnewemployee', 'Admin/ManageEmployees/AddnewEmployee')
        ->name('addnewemployee');

    // UPDATED: View specific employee
    Route::get('/view/{id}', [EmployeeController::class, 'show'])
        ->name('view');

    // UPDATED: Edit specific employee
    Route::get('/edit/{id}', [EmployeeController::class, 'edit'])
        ->name('edit');

    Route::put('/edit/{id}', [EmployeeController::class, 'update'])
        ->name('employees.update');

    // UPDATED: Delete specific employee
    Route::delete('/delete/{id}', [EmployeeController::class, 'destroy'])
        ->name('employees.destroy');

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


Route::get('/addnewemployee', function () {
    $departments = Department::all(); // fetch departments from DB
    return Inertia::render('Admin/ManageEmployees/AddnewEmployee', [
        'departments' => $departments
    ]);
})->name('addnewemployee');


Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
