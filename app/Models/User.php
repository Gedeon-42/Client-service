<?php

namespace App\Models;

use App\Models\Task;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use RwandaBuild\MurugoAuth\Traits\MurugoAuthHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, MurugoAuthHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'murugo_user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function routeNotificationForOneSignal()
    {
        return [
            'include_external_user_ids' => [(string) $this->id],
        ];
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
