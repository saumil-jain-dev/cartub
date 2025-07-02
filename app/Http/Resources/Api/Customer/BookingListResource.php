<?php

namespace App\Http\Resources\Api\Customer;

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
            'booking_address' => $this->address,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
