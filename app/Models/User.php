<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone',
        'avatar',
        'address',
        'birthday',
        'gender',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
    public function isBanned()
    {
        return $this->status === 'banned';
    }
    public function isDeleted()
    {
        return $this->status === 'deleted';
    }
}
