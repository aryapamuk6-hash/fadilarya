<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wishlist;
use App\Models\OrderItem;

class Product extends Model
{
    protected $fillable = [
    'store_id',
    'category_id',
    'name',
    'slug',
    'description',
    'price',
    'stock',
    'sold_count',
    'image',
    'status',
    'is_hidden',
];

    public function wishlists()
{
    return $this->hasMany(
        Wishlist::class
    );
}

public function wishlistedBy()
{
    return $this->belongsToMany(
        User::class,
        'wishlists'
    );
}

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function conversations()
{
    return $this->hasMany(
        Conversation::class
    );
}

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}