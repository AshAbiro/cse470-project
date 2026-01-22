<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'booking_group_id' => $this->booking_group_id,
            'user_id' => $this->user_id,
            'room_id' => $this->room_id,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'room' => $this->whenLoaded('room'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
