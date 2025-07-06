<?php

namespace App\Http\Controllers\Api\Cleaner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cleaner\BookingDetailsRequest;
use App\Http\Requests\Api\Cleaner\UpdateBookingStatusRequest;
use App\Http\Requests\Api\Cleaner\UpdateLocationRequest;
use App\Http\Resources\Api\Cleaner\BookingDetailsResource;
use App\Http\Resources\Api\Cleaner\BookingListResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\Api\Cleaner\BookingService;
use Exception;

class BookingController extends Controller
{
    //
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function assignBookingList(Request $request){
        try {
            $bookings = $this->bookingService->assignBookingList($request);
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

    public function updateBookingStatus(UpdateBookingStatusRequest $request){
        try {
            $bookings = $this->bookingService->updateBookingStatus($request);
            if($bookings){
                return success(
                    $request->all(),
                    trans('messages.update', ['attribute' => 'Booking']),
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

    public function getBookingsList(Request $request)
    {
        try {
            $bookings = $this->bookingService->getBookingsList($request);
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

    public function assignBooking(Request $request){
        try{
            $bookingUpdate = Booking::where('id',$request->booking_id)->update(['cleaner_id' => $request->cleaner_id ]);
            return success(
                    $request->all(),
                    trans('messages.update', ['attribute' => 'Booking']),
                    config('code.SUCCESS_CODE')
                );
        } catch(Exception $e){
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }
}
