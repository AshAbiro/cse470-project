<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ticket_uid',
        'price',
        'description',
        'image_path',
        'min_height',
        'thrill_level',
        'is_active',
    ];

    public function rideRatings()
    {
        return $this->hasMany(RideRating::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function averageRating()
    {
        return $this->rideRatings()->avg('rating') ?? 0;
    }
}
