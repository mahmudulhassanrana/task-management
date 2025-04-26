<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Inertia\Inertia;

Route::middleware('guest')->get('/', fn () => Inertia::render('Auth/Login'))->name('login');
Route::middleware('guest')->get('/register', fn () => Inertia::render('Auth/Register'))->name('register');

Route::middleware('guest')->post('/login', LoginController::class);
Route::middleware('guest')->post('/register', RegisterController::class);
Route::middleware('auth')->post('/logout', LogoutController::class)->name('logout');

// Authenticated User Info (optional)
Route::middleware('auth')->get('/user', fn () => auth()->user());

Route::middleware(['auth'])->group(function () {
    // Route::get('/', fn () => redirect('/tasks'));
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/tasks-update/{task}', [TaskController::class, 'update']);
    Route::post('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('/tasks-complete/{task}', [TaskController::class, 'complete']);
});
