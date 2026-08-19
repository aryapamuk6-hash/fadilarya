<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'scope',
        'user_id',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
        'min_purchase' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        if (! $this->is_active) {
            return false;
        }

        if (
            $this->expired_at &&
            now()->gt($this->expired_at)
        ) {
            return false;
        }

        return true;
    }

    public function isApplicableToUser(?User $user): bool
    {
        if (! $this->isValid()) {
            return false;
        }

        if ($this->scope === 'all') {
            return true;
        }

        if ($this->scope === 'user' && $user) {
            return (int) $this->user_id === (int) $user->id;
        }

        return false;
    }
}