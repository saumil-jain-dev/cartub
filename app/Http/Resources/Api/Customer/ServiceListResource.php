<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration' => $this->duration_minutes,
            'type' => $this->type,
            // 'image' => $this->image ? asset('storage/' . $this->image) : null, // Assuming image is stored in public storage
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
