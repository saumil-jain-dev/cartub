<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Rolepermission\RolePermissionController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.post');

Route::middleware(['redirect.if.unauthenticated'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    //Role Permission Management
    Route::controller(RolePermissionController::class)->group(function () {
        Route::prefix('roles-permission')->group(function () {

            Route::get('/','index')->name('roles-permission.index');
            Route::get('create','create')->name('roles-permission.create');
            Route::post('store','store')->name('roles-permission.store');
            Route::post('update','update')->name('roles-permission.update');
        });
    });
});