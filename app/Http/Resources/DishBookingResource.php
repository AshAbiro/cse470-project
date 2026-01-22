<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DishBookingResource extends JsonResource
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
            'dish_id' => $this->dish_id,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'booking_date' => $this->booking_date,
            'status' => $this->status,
            'dish' => $this->whenLoaded('dish'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
