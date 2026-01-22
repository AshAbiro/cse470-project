<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DishBooking extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['user_id', 'booking_group_id', 'dish_id', 'quantity', 'total_price', 'booking_date', 'status'];
}
