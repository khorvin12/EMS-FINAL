<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'Employee/Index')->name('index');

Route::inertia('/employee/leave', 'Employee/Leaves/Index')->name('/employee/leave');