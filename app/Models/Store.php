<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'rating',
        'total_sales',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function conversations()
{
    return $this->hasManyThrough(
        Conversation::class,
        Product::class,
        'store_id',
        'product_id'
    );
}

public function reviews()
{
    return $this->hasMany(Review::class);
}

}