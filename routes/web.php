<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Auth/Login')->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
