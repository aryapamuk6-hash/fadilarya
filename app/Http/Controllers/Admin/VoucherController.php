<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoucherController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Admin/Vouchers/Index',
            [
                'vouchers' => Voucher::with('user')->latest()->get(),
                'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            ]
        );
    }

    public function store(
        Request $request
    ) {

        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:vouchers,code'],
            'type' => ['required', 'in:percentage,fixed,free_shipping'],
            'value' => ['required', 'numeric', 'min:0', 'max_if:type,percentage,100'],
            'min_purchase' => ['nullable', 'numeric', 'min:0'],
            'scope' => ['required', 'in:all,user'],
            'user_id' => ['nullable', 'exists:users,id'],
            'expired_at' => ['nullable', 'date'],
        ]);

        if ($request->scope === 'user' && ! $request->user_id) {
            return back()->with('error', 'Pilih user yang berhak memakai voucher tertentu.');
        }

        Voucher::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_purchase' => $request->min_purchase ?? 0,
            'scope' => $request->scope,
            'user_id' => $request->scope === 'user' ? $request->user_id : null,
            'expired_at' => $request->expired_at,
            'is_active' => true,
        ]);

        return back()->with('success', 'Voucher berhasil dibuat.');
    }
}