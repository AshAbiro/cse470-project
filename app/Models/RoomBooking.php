<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room; // Added for relationship
use App\Models\User; // Added for relationship

class RoomBooking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_group_id',
        'room_id',
        'check_in_time',
        'check_out_time',
        'total_price',
        'status',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
