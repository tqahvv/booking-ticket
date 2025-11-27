<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LocationAdminController;
use App\Http\Controllers\Admin\OperatorAdminController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RouteAdminController;
use App\Http\Controllers\Admin\ScheduleAdminController;
use App\Http\Controllers\Admin\ScheduleTemplateAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleTypeAdminController;
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
        Route::get('/schedule-templates/add', [ScheduleTemplateAdminController::class, 'showFormAdd'])->name('admin.scheduleTemplates.add');
        Route::post('/schedule-templates/add', [ScheduleTemplateAdminController::class, 'add'])->name('admin.scheduleTemplates.store');
        Route::post('/schedule-templates/{id}', [ScheduleTemplateAdminController::class, 'update'])->name('admin.scheduleTemplates.update');
        Route::delete('/schedule-templates/{id}', [ScheduleTemplateAdminController::class, 'delete'])->name('admin.scheduleTemplates.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_routes'])->group(function () {
        Route::get('/routes', [RouteAdminController::class, 'index'])->name('admin.routes.index');
        Route::get('/routes/add', [RouteAdminController::class, 'showFormAdd'])->name('admin.routes.add');
        Route::post('/routes/add', [RouteAdminController::class, 'add'])->name('admin.routes.store');
        Route::post('/routes/update/{id}', [RouteAdminController::class, 'update'])->name('admin.routes.update');
        Route::post('/routes/delete/{id}', [RouteAdminController::class, 'delete'])->name('admin.routes.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_operators'])->group(function () {
        Route::get('/operators', [OperatorAdminController::class, 'index'])->name('admin.operators.index');
        Route::get('/operators/add', [OperatorAdminController::class, 'showFormAdd'])->name('admin.operators.add');
        Route::post('/operators/add', [OperatorAdminController::class, 'add'])->name('admin.operators.store');
        Route::post('/operators/update/{id}', [OperatorAdminController::class, 'update'])->name('admin.operators.update');
        Route::post('/operators/delete/{id}', [OperatorAdminController::class, 'delete'])->name('admin.operators.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_locations'])->group(function () {
        Route::get('/locations', [LocationAdminController::class, 'index'])->name('admin.locations.index');
        Route::get('/locations/add', [LocationAdminController::class, 'showFormAdd'])->name('admin.locations.add');
        Route::post('/locations/add', [LocationAdminController::class, 'add'])->name('admin.locations.store');
        Route::post('/locations/update/{id}', [LocationAdminController::class, 'update'])->name('admin.locations.update');
        Route::post('/locations/delete/{id}', [LocationAdminController::class, 'delete'])->name('admin.locations.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_vehicles'])->group(function () {
        Route::get('/vehicle-types', [VehicleTypeAdminController::class, 'index'])->name('admin.vehicleTypes.index');
        Route::get('/vehicle-types/add', [VehicleTypeAdminController::class, 'showFormAdd'])->name('admin.vehicleTypes.add');
        Route::post('/vehicle-types/add', [VehicleTypeAdminController::class, 'add'])->name('admin.vehicleTypes.store');
        Route::post('/vehicle-types/update/{id}', [VehicleTypeAdminController::class, 'update'])->name('admin.vehicleTypes.update');
        Route::post('/vehicle-types/delete/{id}', [VehicleTypeAdminController::class, 'delete'])->name('admin.vehicleTypes.delete');
    });
});
