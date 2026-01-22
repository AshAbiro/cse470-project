<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_uid' => $this->ticket_uid,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'min_height' => $this->min_height,
            'thrill_level' => $this->thrill_level,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
