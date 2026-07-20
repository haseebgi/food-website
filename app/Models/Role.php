<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
])]

class Role extends Model
{
    /**
     * A Role can have many Users.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}