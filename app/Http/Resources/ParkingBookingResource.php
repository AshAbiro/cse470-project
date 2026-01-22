<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkingBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'parking_slot_id' => $this->parking_slot_id,
            'date' => $this->date,
            'time_slot' => $this->time_slot,
            'status' => $this->status,
            'price' => $this->price,
            'slot' => $this->whenLoaded('slot'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
