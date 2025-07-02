<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtpResource extends JsonResource
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
            'phone' => $this->phone,
            'otp' => $this->otp,
            'otp_expires_at' => $this->otp_expires_at,
            'role' => $this->role,
        ];
    }
}
