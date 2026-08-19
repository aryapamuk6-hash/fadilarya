<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'commission_deduction',
        'net_amount',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'admin_note',
        'proof',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}
