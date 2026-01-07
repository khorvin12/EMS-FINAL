<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/dashboard', 'Admin/Dashboard')->name('dashboard');

Route::inertia('/', 'Auth/Login')->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::inertia('/register', 'Auth/Register')->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::inertia('/manageemployees', 'Admin/ManageEmployees/ManageEmployee')->name('manageemployee');
Route::post('/manageemployee', [AuthController::class, 'manageemployee']);

