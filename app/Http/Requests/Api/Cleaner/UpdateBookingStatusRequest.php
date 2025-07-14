<?php

namespace App\Http\Requests\Api\Cleaner;

use App\Models\Booking;
use App\Models\BookingCancellation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateBookingStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'booking_id' => 'required|numeric|exists:bookings,id',
            'action' => [
                'required',
                Rule::in(['accept', 'in_route','start_job','finish_job', 'complete', 'cancel','mark_as_arrived']),
            ],
            'before_image' => 'nullable|array|min:1',
            'before_image.*' => 'file|max:10240',
            'after_image' => 'nullable|array|min:1',
            'after_image.*' => 'file|max:10240',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $before_image = $this->file('before_image');
            $after_image = $this->file('after_image');
            $data = $this->all();
            $bookingId = $data['booking_id'];
            $action = $data['action'];
            $userId = Auth::id();

            $booking = Booking::find($bookingId);

            if (!$booking) {
                return;
            }

            if (in_array($action, ['accept', 'cancel'])) {
                if ($booking->cleaner_id != $userId) {
                    $validator->errors()->add('booking_id', 'This booking is not assigned to you.');
                }

                if ($booking->status != 'pending') {
                    $validator->errors()->add('booking_id', 'Booking status must be pending to perform this action.');
                }
            }

            if ($action == 'accept') {
                $alreadyCancelled = BookingCancellation::where('booking_id', $bookingId)
                    ->where('cleaner_id', $userId)
                    ->exists();

                if ($alreadyCancelled) {
                    $validator->errors()->add('booking_id', 'You have already cancelled this booking.');
                }
            }

            if ($action == 'in_route') {
                if ($booking->cleaner_id != $userId) {
                    $validator->errors()->add('booking_id', 'This booking is not assigned to you.');
                }
    
                if ($booking->status != 'accepted') {
                    $validator->errors()->add('booking_id', 'Booking must be accepted before marking as is route.');
                }
            }

            if ($action == 'mark_as_arrived') {
                if ($booking->cleaner_id != $userId) {
                    $validator->errors()->add('booking_id', 'This booking is not assigned to you.');
                }
    
                if ($booking->status != 'in_route') {
                    $validator->errors()->add('booking_id', 'Booking must be in route before marking as arrived.');
                }
            }

            if ($action == 'start_job') {
                if ($booking->cleaner_id != $userId) {
                    $validator->errors()->add('booking_id', 'This booking is not assigned to you.');
                }
            
                if ($booking->status != 'mark_as_arrived') {
                    $validator->errors()->add('booking_id', 'Booking must be marked as arrived before starting the job.');
                }
                if (!$before_image) {
                    $validator->errors()->add('before_image', 'Before image is required when starting the job.');
                }
                if ($before_image) {
                    if(count($before_image) > 5) {
                        $validator->errors()->add('before_image', 'You can upload a maximum of 5 images.');
                    }
                    $hasValidMedia = false;
    
                    foreach ($before_image as $file) {
                        $mime = $file->getMimeType();
                        $extension = strtolower($file->getClientOriginalExtension());
                        $size = $file->getSize();
    
                        // ✅ Image Validation
                        if (str_starts_with($mime, 'image/')) {
                            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'svg', 'gif'])) {
                                $validator->errors()->add('before_image', 'Only JPG, JPEG, PNG, SVG, and GIF images are allowed.');
                            }
                            if ($size > 10 * 1024 * 1024) { // 10MB
                                $validator->errors()->add('before_image', 'Each image must be less than or equal to 10MB.');
                            }
                            $hasValidMedia = true;
                        } else {
                            $validator->errors()->add('before_image', 'Only image files are allowed.');
                        }
                    }
    
                    // ✅ Ensure at least one valid media file is uploaded
                    if (!$hasValidMedia) {
                        $validator->errors()->add('before_image', 'At least one valid image is required.');
                    }
                }
            }
            if ($action == 'finish_job') {
                if ($booking->cleaner_id != $userId) {
                    $validator->errors()->add('booking_id', 'This booking is not assigned to you.');
                }
    
                if ($booking->status != 'in_progress') {
                    $validator->errors()->add('booking_id', 'Booking must be in_progress before marking as arrived.');
                }
            }

            if ($action == 'complete') {
                if ($booking->cleaner_id != $userId) {
                    $validator->errors()->add('booking_id', 'This booking is not assigned to you.');
                }
            
                if ($booking->status != 'in_progress') {
                    $validator->errors()->add('booking_id', 'Booking must be marked as in progress before starting the job.');
                }
                if (!$after_image) {
                    $validator->errors()->add('after_image', 'After image is required when complete the booking.');
                }
                if ($after_image) {
                    if(count($after_image) > 5) {
                        $validator->errors()->add('after_image', 'You can upload a maximum of 5 images.');
                    }
                    $hasValidMedia = false;
    
                    foreach ($after_image as $file) {
                        $mime = $file->getMimeType();
                        $extension = strtolower($file->getClientOriginalExtension());
                        $size = $file->getSize();
    
                        // ✅ Image Validation
                        if (str_starts_with($mime, 'image/')) {
                            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'svg', 'gif'])) {
                                $validator->errors()->add('after_image', 'Only JPG, JPEG, PNG, SVG, and GIF images are allowed.');
                            }
                            if ($size > 10 * 1024 * 1024) { // 10MB
                                $validator->errors()->add('after_image', 'Each image must be less than or equal to 10MB.');
                            }
                            $hasValidMedia = true;
                        } else {
                            $validator->errors()->add('after_image', 'Only image files are allowed.');
                        }
                    }
    
                    // ✅ Ensure at least one valid media file is uploaded
                    if (!$hasValidMedia) {
                        $validator->errors()->add('after_image', 'At least one valid image is required.');
                    }
                }
            }
        });
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            fail([], error_parse($validator->errors()), config('code.VALIDATION_ERROR_CODE'))
        );
    }
}
