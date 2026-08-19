<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Cart;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Review;
use App\Models\SellerApplication;
use App\Models\Store;
use App\Models\TopUp;
use App\Models\Wishlist;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'avatar',
    'balance',

    'warning_count',

    'seller_status',

    'is_suspended',

    'suspension_reason',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            'balance' => 'decimal:2',

            'is_suspended' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Seller Application
    |--------------------------------------------------------------------------
    */

    public function sellerApplication()
    {
        return $this->hasOne(
            SellerApplication::class
        );
    }

    public function reviewedApplications()
    {
        return $this->hasMany(
            SellerApplication::class,
            'reviewed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        return $this->hasOne(
            Store::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    public function cart()
    {
        return $this->hasOne(
            Cart::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    public function orders()
    {
        return $this->hasMany(
            Order::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Chat
    |--------------------------------------------------------------------------
    */

    public function buyerConversations()
    {
        return $this->hasMany(
            Conversation::class,
            'buyer_id'
        );
    }

    public function sellerConversations()
    {
        return $this->hasMany(
            Conversation::class,
            'seller_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(
            Message::class,
            'sender_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Top Up
    |--------------------------------------------------------------------------
    */

    public function topUps()
    {
        return $this->hasMany(
            TopUp::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */

    public function reviews()
    {
        return $this->hasMany(
            Review::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    public function notifications()
    {
        return $this->hasMany(
            Notification::class
        )->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /*
    |--------------------------------------------------------------------------
    | Seller Management
    |--------------------------------------------------------------------------
    */

    public function isSuspended(): bool
    {
        return (bool) $this->is_suspended;
    }

    public function wishlists()
{
    return $this->hasMany(
        Wishlist::class
    );
}

public function wishlistProducts()
{
    return $this->belongsToMany(
        Product::class,
        'wishlists'
    );
}

public function withdrawals()
{
    return $this->hasMany(
        Withdrawal::class
    );
}

}