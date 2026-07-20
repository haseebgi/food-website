<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'phone',
    'role_id',
    'password',
    'status',
])]

#[Hidden([
    'password',
    'remember_token',
])]

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * A User belongs to one Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * A User can have many Orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Attribute Casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}