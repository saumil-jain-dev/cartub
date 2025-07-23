<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Booking\BookingController;
use App\Http\Controllers\Admin\Cleaner\CleanerController;
use App\Http\Controllers\Admin\Coupons\CouponController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Feedback\FeedbackController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Rolepermission\RolePermissionController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Vehicle\VehicleController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.post');
Route::get('test-notification', function () {
    $to = 'fnRYEkQKQKypKuvqBETHaA:APA91bH8oCPfS7Dsfc-pBRbFTVT2Momav3VEDlr7urWFdAmO4Hg1utvOVI4XSezp64v42QVhm7vHxwynGjyMtFJFxWnBluSIKXZMM8Dh2mYNcWX2b0iHHsI'; // Replace with actual device token
    $title = 'Test Notification';
    $message = 'This is a test notification message.';
    $extra = ['key' => 'value']; // Optional extra data

    $result = \App\Traits\NotificationTrait::push_notification($to, $title, $message, $extra);
    
    return response()->json(['success' => $result]);
})->name('test.notification');

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
            Route::get('cancel-booking/{id}','cancelBooking')->name('bookings.cancel');
        });
    });

    //Customer(User) Route
    Route::controller(UserController::class)->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/','index')->name('users.index');
            Route::get('/profile/{id}','getProfile')->name('users.profile');
            Route::get('edit','edit')->name('users.edit');
            Route::post('update','update')->name('users.update');
            Route::delete('{id}','destroy')->name('users.destroy');
        });
    });

    //Cleaner Route
    Route::controller(CleanerController::class)->group(function () {
        Route::prefix('cleaners')->group(function () {
            Route::get('/','index')->name('cleaners.index');
            Route::get('create','create')->name('cleaners.create');
            Route::post('store','store')->name('cleaners.store');
            Route::get('edit/{id}','edit')->name('cleaners.edit');
            Route::post('update','update')->name('cleaners.update');
            Route::delete('{id}','destroy')->name('cleaners.destroy');
            Route::get('performance-reports','performanceReports')->name('cleaners.performance-reports');
        });
    });

    //Vehicle Route
    Route::controller(VehicleController::class)->group(function () {
        Route::prefix('vehicle')->group(function () {
            Route::get('customer-vehicles-list','index')->name('vehicle.index');
            Route::get('wash-types','washType')->name('vehicle.wash-type');
            Route::get('wash-type/create','washTypeAdd')->name('vehicle.wash-type-create');
            Route::post('wash-type/store','washTypeStore')->name('vehicle.wash-types-store');
            Route::post('wash-type/update','washTypeUpdate')->name('vehicle.wash-types-edit');
            Route::get('wash-type/destroy/{id}','washTypeDestroy')->name('vehicle.wash-types-destroy');

            Route::get('wash-packages','washPackage')->name('vehicle.wash-packages');
            Route::get('wash-package/create','washPackageAdd')->name('vehicle.wash-packages-create');
            Route::post('wash-package/store','washPackageStore')->name('vehicle.wash-packages-store');
            Route::post('wash-package/update','washPackageUpdate')->name('vehicle.wash-packages-edit');
            Route::get('wash-package/destroy/{id}','washPackageDestroy')->name('vehicle.wash-packages-destroy');
        });
    });

    //Coupons Route
    Route::controller(CouponController::class)->group(function () {
        Route::prefix('coupons')->group(function () {
            Route::get('/','index')->name('coupons.index');
            Route::get('create','create')->name('coupons.create');
            Route::post('store','store')->name('coupons.store');
            Route::post('check-coupon-code','checkCode')->name('coupons.checkCode');
            Route::get('edit/{id}','edit')->name('coupons.edit');
            Route::delete('{id}','destroy')->name('coupons.destroy');
        });
    });

    //Payment Route
    Route::get('payment-history',[PaymentController::class,'index'])->name('payment.index');

    //Customer Feedback Route
    Route::get('customer-feedback', [FeedbackController::class,'index'])->name('customer-feedback.index');
});