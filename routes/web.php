<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Booking\BookingController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Rolepermission\RolePermissionController;
use App\Http\Controllers\Admin\User\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.post');

Route::middleware(['redirect.if.unauthenticated'])->prefix('admin')->group(function () {
    
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    //Dashboard Route
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'index')->name('dashboard.dashboard');
            Route::get('live-wash-status', 'liveWashStatus')->name('dashboard.live-wash-status');
            Route::get('/bookings/by-status',  'getByStatus')->name('dashboard.bookings.by-status');
            Route::get('today-wash', 'todayWash')->name('dashboard.today-wash');
        });
    });
    //Role Permission Management
    Route::controller(RolePermissionController::class)->group(function () {
        Route::prefix('roles-permission')->group(function () {

            Route::get('/','index')->name('roles-permission.index');
            Route::get('create','create')->name('roles-permission.create');
            Route::post('store','store')->name('roles-permission.store');
            Route::get('edit','edit')->name('roles-permission.edit');
            Route::post('update','update')->name('roles-permission.update');
            Route::delete('{id}','destroy')->name('roles-permission.destroy');

        });
    });

    //Booking Route
    Route::controller(BookingController::class)->group(function () {
        Route::prefix('bookings')->group(function () {

            Route::get('/','index')->name('bookings.index');
            Route::get('details/{id}','show')->name('bookings.show');
            Route::delete('{id}','destroy')->name('bookings.destroy');
            Route::get('{booking}/available-cleaners', 'availableCleaners')->name('bookings.available-cleaners');
            Route::post('assign-booking','assignBooking')->name('bookings.assign-cleaner');
        });
    });

    //Customer(User) Route
    Route::controller(UserController::class)->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/','index')->name('users.index');
        });
    });
});