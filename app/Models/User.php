<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenumber',
        'status',
        'address',
        'role',
    ];

    protected $primaryKey = 'user_id';

    protected $hidden = [
        'password',
        'remember_token',
        'jobSeeker',
        'employer',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function employer()
    {
        return $this->hasOne(Employer::class, 'user_id');
    }

    public function jobSeeker()
    {
        return $this->hasOne(JobSeeker::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
