<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'damaged_items', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
