<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'HR/Index')->name('index');

Route::inertia('/attendance', 'HR/Attendance/Index')->name('attendance');

Route::inertia('/view-attendance', 'HR/Attendance/View')->name('view-attendance');