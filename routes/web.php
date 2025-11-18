<?php

use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LocationController;
use App\Http\Controllers\Client\PostController;
use App\Http\Controllers\Client\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('client.pages.account');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('post/{slug}', [PostController::class, 'detail'])->name('post.detail');
Route::get('/post', [PostController::class, 'index'])->name('post.index');

Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');
Route::get('/search', [SearchController::class, 'search'])->name('search.results');

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');
});

require __DIR__ . '/admin.php';
