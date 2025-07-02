<?php

namespace App\Http\Controllers\Api\Cleaner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cleaner\UpdateBookingStatusRequest;
use App\Http\Resources\Api\Cleaner\BookingListResource;
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
}
