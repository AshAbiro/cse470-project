<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StaffTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'admin_id',
        'task_type',
        'item_name',
        'description',
        'status',
        'priority',
        'due_at',
        'staff_response',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
