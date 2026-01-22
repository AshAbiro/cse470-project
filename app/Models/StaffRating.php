<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffRating extends Model
{
    protected $fillable = [
        'user_id',
        'staff_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
