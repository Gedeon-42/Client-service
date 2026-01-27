<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'is_completed', 'due_date', 'user_id', 'completed_at'];
    const TASK_COMPLETED = true;

    protected $casts = [
        'completed_at' => 'datetime',
    ];
}
