<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| The four pages of the Tasks for Today Management System.
|
| Two of them are served by the same controller because they show the same
| kind of record, just filtered differently: "/" is today's tasks, "/tasks"
| is all of them. Keeping both in TaskController is what stops the filtering
| logic from being written twice.
|
*/

// Welcome - today's tasks only   ->  http://127.0.0.1:8000/
Route::get('/', [TaskController::class, 'today'])->name('welcome');

// Task List - every task         ->  http://127.0.0.1:8000/tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// Profile - the single user      ->  http://127.0.0.1:8000/profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// About - static page            ->  http://127.0.0.1:8000/about
Route::get('/about', [PageController::class, 'about'])->name('about');
