<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodMaintenanceReport extends Model
{
    protected $fillable = ['dish_id', 'reason', 'status'];

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
