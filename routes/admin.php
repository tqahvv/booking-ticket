<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ScheduleAdminController;
use App\Http\Controllers\Admin\ScheduleTemplateAdminController;
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

    Route::middleware(['auth:admin', 'permission:manage_posts'])->group(function () {
        Route::get('/post/add', [PostController::class, 'showFormAddPost'])->name('admin.post.add');
        Route::post('/post/add', [PostController::class, 'addPost'])->name('admin.post.add');
        Route::post('/post/upload-image', [PostController::class, 'uploadImage'])->name('admin.post.upload-image');
        Route::post('/posts/{id}', [PostController::class, 'delete'])->name('posts.delete');

        Route::get('/post', [PostController::class, 'index'])->name('admin.posts.index');
        Route::post('/post/update', [PostController::class, 'updatePost']);
        Route::post('/posts/toggleStatus/{id}', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
    });

    Route::middleware(['auth:admin', 'permission:manage_bookings'])->group(function () {
        Route::get('/bookings', [BookingAdminController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/{id}', [BookingAdminController::class, 'show'])->name('admin.bookings.show');
        Route::post('/bookings/{id}/update-status', [BookingAdminController::class, 'updateStatus'])
            ->name('admin.bookings.updateStatus');

        Route::post('/bookings/{id}/confirm-transfer', [BookingAdminController::class, 'confirmTransfer'])
            ->name('admin.bookings.confirmTransfer');

        Route::delete('/bookings/{id}', [BookingAdminController::class, 'delete'])
            ->name('admin.bookings.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_schedules'])->group(function () {
        Route::get('/schedules', [ScheduleAdminController::class, 'index'])->name('admin.schedules.index');
        Route::post('/schedules/{id}/update', [ScheduleAdminController::class, 'update'])->name('admin.schedules.update');

        Route::get('/schedule-templates', [ScheduleTemplateAdminController::class, 'index'])->name('admin.scheduleTemplates.index');
        Route::post('/schedule-templates', [ScheduleTemplateAdminController::class, 'store'])->name('admin.scheduleTemplates.store');
        Route::post('/schedule-templates/{id}', [ScheduleTemplateAdminController::class, 'update'])->name('admin.scheduleTemplates.update');
        Route::delete('/schedule-templates/{id}', [ScheduleTemplateAdminController::class, 'delete'])->name('admin.scheduleTemplates.delete');
        Route::post('/schedule-templates/{id}/generate', [ScheduleTemplateAdminController::class, 'generateSchedules'])->name('admin.scheduleTemplates.generate');
    });
});
