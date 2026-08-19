<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerApplication extends Model
{
    protected $fillable = [
    'user_id',
    'store_name',
    'description',
    'phone',
    'id_card',
    'selfie',
    'seller_status',
    'admin_notes',
    'reviewed_at',
    'reviewed_by',
];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }
}