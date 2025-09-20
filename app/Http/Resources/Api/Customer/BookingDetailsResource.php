<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    
        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'scheduled_date' => $this->scheduled_date,
            'scheduled_time' => $this->scheduled_time,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'coupon_id' => $this->coupon_id,
            'gross_amount' => $this->gross_amount,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,
            'notes' => $this->notes,
            'cleaner_note' => $this->cleaner_note,
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'name' => $this->customer->name,
                    'phone' => $this->customer->phone,
                ];
            }),
            'cleaner' => $this->whenLoaded('cleaner', function () {
                if ($this->status != 'pending') {
                    return [
                        'id' => $this->cleaner->id,
                        'name' => $this->cleaner->name,
                        'phone' => $this->customer->phone,
                        'profile_picture' => getImage($this->cleaner->profile_picture),
                        'latitude' => $this->cleaner_location->latitude ?? null,
                        'longitude' => $this->cleaner_location->longitude ?? null,
                        'job_duration' => $this->job_duration ?? null,
                        'rating' => $this->rating->rating ?? 0,
                        'comment' => $this->rating->comment ?? null,
                        'tip_amount' => $this->tip->tip ?? 0,
                    ];
                } else {
                    return [
                        'id' => null,
                        'name' => null,
                        'phone' => null,
                        'profile_picture' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'job_duration' =>  null,
                        'rating' => $this->rating->rating ?? 0,
                        'comment' => null,
                        'tip_amount' => $this->tip->tip ?? 0,
                    ];
                }
            }),
            'vehicle' => $this->whenLoaded('vehicle', function () {
                return [
                    'id' => $this->vehicle->id,
                    'make' => $this->vehicle->make,
                    'model' => $this->vehicle->model,
                    'year' => $this->vehicle->year,
                    'color' => $this->vehicle->color,
                    'license_plate' => $this->vehicle->license_plate,
                ];
            }),
            'service' => $this->whenLoaded('service', function () {
                return [
                    'id' => $this->service->id,
                    'name' => $this->service->name,
                    'duration' => $this->service->duration_minutes,
                    'type' => $this->service->type,
                ];
            }),
            'add_ons' => $this->addOnsNames,
            'coupon' => $this->whenLoaded('coupon', function () {
                return [
                    'id' => $this->coupon->id,
                    'code' => $this->coupon->code,
                ];
            }),
            'before_photo' => $this->whenLoaded('beforePhoto', function () {
                return $this->beforePhoto->map(fn($photo) => getImage($photo->photo_path));
            }),

            'after_photo' => $this->whenLoaded('afterPhoto', function () {
                return $this->afterPhoto->map(fn($photo) => getImage($photo->photo_path));
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
