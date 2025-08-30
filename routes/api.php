<?php

use App\Http\Controllers\Api\Cleaner\BookingController as CleanerBookingController;
use App\Http\Controllers\Api\Customer\BookingController;
use App\Http\Controllers\Api\Customer\VehicleController;
use App\Http\Controllers\Api\V1\Cleaner\AuthenticationController as CleanerAuthenticationController;
use App\Http\Controllers\Api\V1\Customer\AuthenticationController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    //Customer Routes
    Route::prefix('customer')->group(function(){
        Route::controller(AuthenticationController::class)->group(function () {

            Route::post('register', 'register');
            Route::post('/otp/send', 'sendOtp');
            Route::post('/otp/verify', 'verifyOtp');
            Route::post('ongoing-booking', 'ongoing');

        });
        Route::middleware(['auth:sanctum','role:customer'])->group( function (): void {
            Route::controller(AuthenticationController::class)->group(function () {

                Route::get('profile', 'profile');
                Route::post('profile/update', 'updateProfile');
                Route::post('service/list', 'listServices');
                Route::post('wash-type/list', 'listWashTypes');
                Route::post('delete-account', 'deleteAccount');
                Route::post('logout', 'logout');

                //notification routes
                Route::post('notification/list', 'listNotifications');
                Route::post('notification/mark-as-read', 'markNotificationAsRead');

                //Payment History
                Route::post('payment/history', 'listPaymentHistory');
            });

             //Vehicle Routes
            Route::controller(VehicleController::class)->group(function () {
                Route::prefix('vehicle')->group(function () {
                    Route::post('add', 'addVehicle');
                    Route::post('list', 'listVehicles');
                    Route::post('delete', 'deleteVehicle');
                    Route::post('search','vehicleSearch');
                });
            });

            //Booking Routes
            Route::controller(BookingController::class)->group(function () {
                Route::post('apply-coupon', 'applyCoupon');
                Route::prefix('booking')->group(function () {
                    Route::post('create-payment-intent','createIntent');
                    Route::post('check-payment-status','checkPaymentStatus');
                    Route::post('create', 'createBooking');
                    Route::post('list', 'listBookings');
                    Route::post('details', 'bookingDetails');
                    Route::post('add-ratting', 'addRatting');
                    Route::post('add-tip', 'addTip');
                });
            });


        });
    });

    //Cleaner Routes
    Route::prefix('cleaner')->group(function(){
        Route::controller(CleanerAuthenticationController::class)->group(function () {

            Route::post('register','register');
            Route::post('login','login');
        });
        Route::middleware(['auth:sanctum','role:cleaner'])->group( function (): void {
            Route::controller(CleanerAuthenticationController::class)->group(function () {

                Route::post('dashboard','getDashboardData');
                Route::post('update-availability','updateAvailability');
                Route::post('update-location','updateLocation');
                Route::get('profile','profile');
                Route::post('profile/update','updateProfile');
                Route::post('logout','logout');
                Route::post('delete-account', 'deleteAccount');

                //notification routes
                Route::post('notification/list', 'listNotifications');
                Route::post('notification/mark-as-read', 'markNotificationAsRead');

                Route::post('payment/history', 'listPaymentHistory');
            });

            Route::controller(CleanerBookingController::class)->group(function () {
                Route::prefix('booking')->group(function () {
                    Route::post('assign-list', 'assignBookingList');
                    Route::post('list', 'getBookingsList');
                    Route::post('actions', 'updateBookingStatus');
                    Route::post('details', 'bookingDetails');
                    Route::post('assign-booking','assignBooking');
                    Route::post('add-wash-time','addCleanerWashTime');
                });
            });
        });
    });
});
