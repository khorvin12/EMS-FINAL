<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;

// Public login page
Route::get('/', function () {
    return Inertia::render('Login'); // Make sure Login.vue exists
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (protected by auth + admin middleware)
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

// Optional: Test admin middleware
Route::get('/check-admin-middleware', function () {
    return 'Admin middleware is working';
})->middleware(AdminMiddleware::class);
