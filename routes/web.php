<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/admindashboard', 'Admin/AdminDashboard')->name('admindashboard');

Route::inertia('/managedepartment', 'Admin/Departments/ManageDepartment')->name('managedepartment');
Route::post('/managedepartment', [AuthController::class, 'managedepartment']);