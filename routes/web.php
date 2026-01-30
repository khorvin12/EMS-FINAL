<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/index', 'HR/Index')->name('index');

Route::inertia('/salary', 'HR/Salaries/Salary')->name('salary');

Route::inertia('/salary/edit', 'HR/Salaries/SalaryEdit')->name('salary.edit');