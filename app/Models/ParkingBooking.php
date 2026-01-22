<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingBooking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'parking_slot_id', 'date', 'time_slot', 'status', 'price'];

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'parking_slot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
