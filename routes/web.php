<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/admindashboard', 'Admin/AdminDashboard')->name('admindashboard');

Route::inertia('/managedepartment', 'Admin/Departments/ManageDepartment')->name('managedepartment');
Route::post('/managedepartment', [AuthController::class, 'managedepartment']);

Route::inertia('/adddepartment', 'Admin/Departments/AddDepartment')->name('adddepartment');
Route::post('/adddepartment', [AuthController::class, 'adddepartment']);