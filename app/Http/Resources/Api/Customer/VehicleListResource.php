<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleListResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'license_plate' => $this->license_plate,
            'fetched_via_dvla' => $this->fetched_via_dvla,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
