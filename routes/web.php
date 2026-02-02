<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'Employee/Index')->name('index');

Route::inertia('/employee/leave/view', 'Employee/Leaves/View')->name('/employee/leave/view');