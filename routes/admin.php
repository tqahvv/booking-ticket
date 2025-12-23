<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LocationAdminController;
use App\Http\Controllers\Admin\OperatorAdminController;
use App\Http\Controllers\Admin\PaymentAdminController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\Admin\PromotionAdminController;
use App\Http\Controllers\Admin\RouteAdminController;
use App\Http\Controllers\Admin\ScheduleTemplateAdminController;
use App\Http\Controllers\Admin\TicketAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleTypeAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::middleware('guest:admin')->get('login', [AdminAuthController::class, 'showLoginForm'])
        ->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');


    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:admin', 'permission:view_dashboard'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware(['auth:admin', 'permission:manage_users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/users/updateStatus', [UserController::class, 'updateStatus']);
    });

    Route::middleware(['auth:admin', 'permission:manage_categories'])->group(function () {
        Route::get('/categories', [CategoryAdminController::class, 'index'])->name('admin.categories.index');
        Route::get('/categories/add', [CategoryAdminController::class, 'showFormAddCate'])->name('admin.categories.add');
        Route::post('/categories/add', [CategoryAdminController::class, 'addCate'])->name('admin.categories.store');
        Route::post('/categories/{id}', [CategoryAdminController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [CategoryAdminController::class, 'delete'])->name('admin.categories.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_posts'])->group(function () {
        Route::get('/post/add', [PostAdminController::class, 'showFormAddPost'])->name('admin.post.add');
        Route::post('/post/add', [PostAdminController::class, 'addPost'])->name('admin.post.add');
        Route::post('/post/upload-image', [PostAdminController::class, 'uploadImage'])->name('admin.post.upload-image');
        Route::post('/posts/{id}', [PostAdminController::class, 'delete'])->name('posts.delete');

        Route::get('/post', [PostAdminController::class, 'index'])->name('admin.posts.index');
        Route::post('/post/update', [PostAdminController::class, 'updatePost']);
        Route::post('/posts/toggleStatus/{id}', [PostAdminController::class, 'toggleStatus'])->name('posts.toggleStatus');
    });

    Route::middleware(['auth:admin', 'permission:manage_bookings'])->group(function () {
        Route::get('/bookings', [BookingAdminController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/{id}', [BookingAdminController::class, 'show'])->name('admin.bookings.show');
        Route::post('/bookings/{id}/update-status', [BookingAdminController::class, 'updateStatus'])->name('admin.bookings.updateStatus');
        Route::delete('/bookings/{id}', [BookingAdminController::class, 'delete'])->name('admin.bookings.delete');
    });

    Route::middleware(['auth:admin', 'permission:booking_tickets'])->group(function () {
        Route::get('/tickets', [TicketAdminController::class, 'index'])->name('admin.tickets.index');
        Route::get('/tickets/{id}', [TicketAdminController::class, 'show'])->name('admin.tickets.show');
        Route::post('/tickets/{id}/update-status', [TicketAdminController::class, 'updateStatus'])->name('admin.tickets.updateStatus');
        Route::delete('/tickets/{id}', [TicketAdminController::class, 'delete'])->name('admin.tickets.delete');
    });

    Route::middleware(['auth:admin', 'permission:manage_payments'])->group(function () {
        Route::get('/payments', [PaymentAdminController::class, 'index'])->name('admin.payments.index');
        Route::post('/payments/{id}/confirm-cod', [PaymentAdminController::class, 'confirmCOD'])->name('admin.payments.confirm-cod');
        Route::get('/payments/{id}', [PaymentAdminController::class, 'show'])->name('admin.payments.show');
    });

    Route::middleware(['auth:admin', 'permission:manage_schedules'])->group(function () {
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

    Route::middleware(['auth:admin', 'permission:manage_contacts'])->group(function () {
        Route::get('/contact', [ContactAdminController::class, 'index'])->name('admin.contact.index');
        Route::post('/contact/reply', [ContactAdminController::class, 'replyContact']);
    });

    Route::middleware(['auth:admin', 'permission:manage_promotions'])->group(function () {
        Route::get('/promotions', [PromotionAdminController::class, 'index'])->name('admin.promotions.index');
        Route::post('/promotions/toggle-status/{id}', [PromotionAdminController::class, 'toggleStatus'])->name('admin.promotions.toggleStatus');
        Route::get('/promotions/add', [PromotionAdminController::class, 'showFormAdd'])->name('admin.promotions.add');
        Route::post('/promotions/add', [PromotionAdminController::class, 'add'])->name('admin.promotions.store');
        Route::post('/promotions/update/{id}', [PromotionAdminController::class, 'update'])->name('admin.promotions.update');
        Route::post('/promotions/delete/{id}', [PromotionAdminController::class, 'delete'])->name('admin.promotions.delete');
    });
});
