<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\BookingDetailsRequest;
use App\Http\Requests\Api\Customer\BookingListingRequest;
use App\Http\Requests\Api\Customer\BookingRattingRequest;
use App\Http\Requests\Api\Customer\BookingRequest;
use App\Http\Requests\Api\Customer\BookingTipRequest;
use App\Http\Requests\Api\Customer\ValidateCouponRequest;
use App\Http\Resources\Api\Customer\BookingDetailsResource;
use App\Http\Resources\Api\Customer\BookingListResource;
use App\Http\Resources\Api\Customer\BookingResource;
use Illuminate\Http\Request;
use App\Services\Api\BookingService;
use Exception;

class BookingController extends Controller
{
    //
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function applyCoupon(ValidateCouponRequest $request){
        try {
            $coupon = $this->bookingService->applyCoupon($request);
            if($coupon){
                return success(
                    $coupon,
                    trans('messages.coupon_applied'),
                    config('code.SUCCESS_CODE')
                );
            } else {
                return fail([], trans('messages.invalid_coupon'), config('code.NO_RECORD_CODE'));
            }
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));

        }
    }

    public function createBooking(BookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request);
            return success(
                new BookingResource($booking),
                trans('messages.create', ['attribute' => 'Booking']),
                config('code.SUCCESS_CODE')
            );
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function listBookings(BookingListingRequest $request)
    {
        try {
            $bookings = $this->bookingService->listBookings($request);
            if($bookings){
                return success(
                    pagination(BookingListResource::class, $bookings),
                    trans('messages.list', ['attribute' => 'Booking']),
                    config('code.SUCCESS_CODE')
                );
            }
        } catch(Exception $e){
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function bookingDetails(BookingDetailsRequest $request)
    {
        try {
            $booking = $this->bookingService->bookingDetails($request);
            if($booking){
                return success(
                    new BookingDetailsResource($booking),
                    trans('messages.view', ['attribute' => 'Booking']),
                    config('code.SUCCESS_CODE')
                );
            } else {
                return fail([], trans('messages.not_found', ['attribute' => 'Booking']), config('code.NO_RECORD_CODE'));
            }
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function addRatting(BookingRattingRequest $request)
    {
        try {
            $this->bookingService->addRatting($request);
            return success(
                $request->all(),trans('messages.create', ['attribute' => 'Ratting']),
                config('code.SUCCESS_CODE')
            );
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE')); 
        }
    }

    public function addTip(BookingTipRequest $request)
    {
        try {
            $this->bookingService->addTip($request);
            return success(
                $request->all(),trans('messages.create', ['attribute' => 'Booking Tip']),
                config('code.SUCCESS_CODE')
            );
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

}
