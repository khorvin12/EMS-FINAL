<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'Employee/Index')->name('index');

Route::inertia('/employee/attendance', 'Employee/Attendance/Index')->name('/employee/attendance');