<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'HR/Index')->name('index');

Route::inertia('/attendance', 'HR/Attendance/Index')->name('attendance');

Route::inertia('/create-attendance', 'HR/Attendance/Create')->name('create-attendance');