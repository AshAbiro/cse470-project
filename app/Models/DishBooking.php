<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DishBooking extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['user_id', 'booking_group_id', 'dish_id', 'quantity', 'total_price', 'booking_date', 'status'];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
