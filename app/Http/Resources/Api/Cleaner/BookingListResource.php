<?php

namespace App\Http\Resources\Api\Cleaner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'scheduled_date' => $this->scheduled_date,
            'scheduled_time' => $this->scheduled_time,
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
            'wash_type' => $this->whenLoaded('washType', function () {
                return [
                    'id' => $this->washType->id,
                    'name' => $this->washType->name,
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
            'created_at' => $this->created_at,
        ];
    }
}
