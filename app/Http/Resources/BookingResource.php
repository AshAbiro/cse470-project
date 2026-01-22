<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'ticket_type_id' => $this->ticket_type_id,
            'ride_id' => $this->ride_id,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'booking_date' => $this->booking_date,
            'status' => $this->status,
            'ride' => $this->whenLoaded('ride'),
            'ticket_type' => $this->whenLoaded('ticketType'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
