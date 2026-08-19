<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Tests\TestCase;

class CommissionVoucherRulesTest extends TestCase
{
    public function test_commission_follows_threshold_rule(): void
    {
        $this->assertSame(2000.0, Order::calculateCommission(9000));
        $this->assertSame(2000.0, Order::calculateCommission(10000));
        $this->assertSame(5000.0, Order::calculateCommission(10500));
        $this->assertSame(10000.0, Order::calculateCommission(12000));
    }

    public function test_voucher_scope_is_valid_for_specific_user(): void
    {
        $user = User::factory()->create();

        $globalVoucher = Voucher::make([
            'code' => 'GLOBAL10',
            'type' => 'fixed',
            'value' => 10000,
            'scope' => 'all',
            'user_id' => null,
            'is_active' => true,
        ]);

        $userVoucher = Voucher::make([
            'code' => 'USER10',
            'type' => 'fixed',
            'value' => 10000,
            'scope' => 'user',
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        $this->assertTrue($globalVoucher->isApplicableToUser($user));
        $this->assertTrue($userVoucher->isApplicableToUser($user));

        $anotherUser = User::factory()->create();
        $this->assertTrue($globalVoucher->isApplicableToUser($anotherUser));
        $this->assertFalse($userVoucher->isApplicableToUser($anotherUser));
    }
}
