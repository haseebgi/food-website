<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'slug',
    'image',
    'description',
    'status',
])]
class Category extends Model
{
    use HasFactory;

    /**
     * A Category has many Products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}