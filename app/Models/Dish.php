<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity_info', 'description', 'price', 'image_path', 'is_available'];

    public function maintenanceReports()
    {
        return $this->hasMany(FoodMaintenanceReport::class);
    }
}
