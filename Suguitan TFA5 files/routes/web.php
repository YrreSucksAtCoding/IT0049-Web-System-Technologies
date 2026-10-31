<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Every URL of this POS app is listed here. This file does the same job as
| app/Config/Routes.php did in CodeIgniter: it maps a URL to one controller
| method and nothing more.
|
| Two differences from CodeIgniter worth noticing:
|   - the handler is an ARRAY of class and method, not a 'Class::method'
|     string, so the class name is checked by PHP instead of at runtime
|   - ->name() gives the route a name, so views can write route('customers')
|     instead of hard-coding the URL
|
*/

// Landing page   ->  http://127.0.0.1:8000/
Route::get('/', [PageController::class, 'index'])->name('home');

// About page     ->  http://127.0.0.1:8000/about
Route::get('/about', [PageController::class, 'about'])->name('about');

// Customer Accounts  ->  http://127.0.0.1:8000/customers
Route::get('/customers', [CustomerController::class, 'index'])->name('customers');

// User Accounts      ->  http://127.0.0.1:8000/users
Route::get('/users', [UserController::class, 'index'])->name('users');
