<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');


    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth.custom'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware(['auth:admin', 'permission:manage_users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/users/updateStatus', [UserController::class, 'updateStatus']);
    });

    Route::middleware(['permission:manage_posts'])->group(function () {
        Route::get('/post/add', [PostController::class, 'showFormAddPost'])->name('admin.post.add');
        Route::post('/post/upload-image', [PostController::class, 'uploadImage'])->name('admin.post.upload-image');


        Route::get('/post', [PostController::class, 'index'])->name('admin.posts.index');
        Route::post('/post/update', [PostController::class, 'updatePost']);
        Route::post('/posts/toggleStatus/{id}', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
    });
});
