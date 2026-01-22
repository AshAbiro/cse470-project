<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'ticket_uid',
        'room_number',
        'floor',
        'type',
        'price_per_12h',
        'features',
        'rating',
        'image_path',
        'status',
    ];
    public function roomMaintenanceReports()
    {
        return $this->hasMany(RoomMaintenanceReport::class);
    }

    public function roomRatings()
    {
        return $this->hasMany(RoomRating::class);
    }

    public function averageRating()
    {
        return $this->roomRatings()->avg('rating') ?? 0;
    }
}
