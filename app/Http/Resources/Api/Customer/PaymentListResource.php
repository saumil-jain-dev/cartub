<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return  [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'service_name' => $this->service->name ?? null,
            'service_type' => $this->service->type ?? null,
            'vehicle_name' => $this->vehicle->name ?? null,
            'vehicle_make' => $this->vehicle->make ?? null,
            'vehicle_model' => $this->vehicle->model ?? null,
            'vehicle_year' => $this->vehicle->year ?? null,
            'vehicle_color' => $this->vehicle->color ?? null,
            'vehicle_license_plate' => $this->vehicle->license_plate ?? null,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment->created_at ?? null,
            
        ];
    }
}
