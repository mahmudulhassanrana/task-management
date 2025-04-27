<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Inertia\Inertia;


// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', fn () => Inertia::render('Auth/Login'))->name('login');
    Route::get('/register', fn () => Inertia::render('Auth/Register'))->name('register');

    Route::post('/login', LoginController::class);
    Route::post('/register', RegisterController::class);
});

// Authenticated User Info (optional)
Route::middleware('auth')->get('/user', fn () => auth()->user());

Route::middleware(['auth'])->group(function () {
    // Route::get('/', fn () => redirect('/tasks'));
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/tasks-update/{task}', [TaskController::class, 'update']);
    Route::post('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('/tasks-complete/{task}', [TaskController::class, 'complete']);
    Route::post('/tasks-reorder', [TaskController::class, 'reorder'])->name('reorder');

    Route::get('/project', [ProjectController::class, 'index'])->name('projects.index');;
    Route::post('/project', [ProjectController::class, 'store']);
    Route::post('/project-update/{project}', [ProjectController::class, 'update']);
    Route::post('/project-delete/{project}', [ProjectController::class, 'destroy']);
    Route::post('/project-reorder', [ProjectController::class, 'reorder'])->name('reorder');
});
