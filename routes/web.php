<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'HR/Index')->name('index');

Route::inertia('/leaves', 'HR/Leaves/Index')->name('leaves.index');

Route::inertia('/leaves', 'HR/Leaves/View')->name('leaves.view');