<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_uid' => $this->ticket_uid,
            'room_number' => $this->room_number,
            'floor' => $this->floor,
            'type' => $this->type,
            'price_per_12h' => $this->price_per_12h,
            'features' => $this->features,
            'rating' => $this->rating,
            'image_path' => $this->image_path,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
