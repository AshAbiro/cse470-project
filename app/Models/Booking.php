<?php

namespace App\Models;

use Illuminate\Database\Eloquent\MethodClosure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_group_id',
        'ticket_type_id',
        'ride_id',
        'quantity',
        'total_price',
        'booking_date',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
