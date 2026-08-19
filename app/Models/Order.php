<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const COMMISSION_FLAT_LOW = 2000;
    public const COMMISSION_FLAT_HIGH = 10000;

    protected $fillable = [
        'user_id',
        'voucher_id',
        'discount',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'commission_amount',
        'seller_income',
    ];

    public static function calculateCommission(float|int $amount): float
    {
        $amount = (float) $amount;

        if ($amount <= 10000) {
            return 2000.0;
        }

        if ($amount >= 11000) {
            return 10000.0;
        }

        return 5000.0;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review()
{
    return $this->hasOne(Review::class);
}

public function orderItems()
{
    return $this->hasMany(
        OrderItem::class
    );
}

public function voucher()
{
    return $this->belongsTo(
        Voucher::class
    );
}
}