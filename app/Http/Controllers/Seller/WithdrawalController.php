<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WithdrawalController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Seller/Withdrawals/Index',
            [
                'balance' => auth()->user()->balance,

                'withdrawals' => Withdrawal::where(
                    'user_id',
                    auth()->id()
                )
                ->latest()
                ->get(),
            ]
        );
    }

    public function store(
        Request $request
    ) {

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:10000',
            ],

            'bank_name' => [
                'required',
            ],

            'account_number' => [
                'required',
            ],

            'account_name' => [
                'required',
            ],
        ]);

        $user = auth()->user();
        $requestAmount = (float) $request->amount;
        $commissionDeduction = \App\Models\Order::calculateCommission($requestAmount);
        $netAmount = max(0, $requestAmount - $commissionDeduction);

        if ($requestAmount > $user->balance) {
            return back()->with(
                'error',
                'Saldo tidak cukup'
            );
        }

        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $requestAmount,
            'commission_deduction' => $commissionDeduction,
            'net_amount' => $netAmount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'pending',
            'proof' => 'Komisi Lumira: Rp ' . number_format($commissionDeduction, 0, ',', '.') . '\nNominal cair: Rp ' . number_format($netAmount, 0, ',', '.'),
        ]);

        return back()->with(
            'success',
            'Pengajuan withdrawal berhasil. Komisi Lumira telah dihitung otomatis.'
        );
    }
}
