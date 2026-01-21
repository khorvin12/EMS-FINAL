<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Employee Dashboard Route
Route::inertia('/index', 'Employee/Index')->name('index');

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