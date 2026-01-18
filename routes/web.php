<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'HR/Index')->name('index');

Route::inertia('/salary', 'HR/Salaries/Salary')->name('salary');
