<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = ['title', 'description', 'is_completed', 'due_date', 'user_id', 'completed_at','reminder_sent_at'];
    const TASK_COMPLETED = true;
    protected $casts = [
        'due_date' => 'datetime',
        'reminder_enabled' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
