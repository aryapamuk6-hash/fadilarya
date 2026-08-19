<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WithdrawalController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Admin/Withdrawals/Index',
            [
                'withdrawals' =>
                    Withdrawal::with('user')
                    ->latest()
                    ->get(),
            ]
        );
    }

    public function approve(
        Withdrawal $withdrawal
    ) {

        if (
            $withdrawal->status !==
            'pending'
        ) {
            return back();
        }

        DB::transaction(function () use (
            $withdrawal
        ) {
            $netAmount = (float) ($withdrawal->net_amount ?? $withdrawal->amount);

            $withdrawal->user
                ->decrement(
                    'balance',
                    $netAmount
                );

            $withdrawal->update([
                'status' => 'approved',
                'proof' => $withdrawal->proof . '\nStatus: Disetujui oleh admin',
            ]);

        });

        return back()->with(
            'success',
            'Withdrawal berhasil disetujui'
        );
    }

    public function reject(
        Withdrawal $withdrawal
    ) {

        $withdrawal->update([
            'status' => 'rejected',
        ]);

        return back()->with(
            'success',
            'Withdrawal ditolak'
        );
    }
}
