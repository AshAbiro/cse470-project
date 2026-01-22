<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ride;

class MaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'damage_time',
        'reason',
        'repair_price',
        'status',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
