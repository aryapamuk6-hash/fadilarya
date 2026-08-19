<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Voucher;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => [
                'required',
                'in:balance,cod',
            ],
            'voucher_code' => [
                'nullable',
            ],
        ]);

        $user = auth()->user();

        $cart = Cart::with(
            'items.product.store'
        )
        ->where(
            'user_id',
            $user->id
        )
        ->first();

        if (
            !$cart ||
            $cart->items->count() === 0
        ) {
            return back()->with(
                'error',
                'Keranjang kosong'
            );
        }

        foreach ($cart->items as $item) {
            if ($item->product->status !== 'active' || $item->product->stock < $item->quantity) {
                return back()->with('error', 'Stok produk ' . $item->product->name . ' tidak mencukupi atau telah habis.');
            }
        }

        $total = 0;

        foreach ($cart->items as $item) {

            $total +=
                $item->product->price *
                $item->quantity;

        }

       $voucher = null;
$discount = 0;

if ($request->filled('voucher_code')) {

    $voucher = Voucher::where(
        'code',
        strtoupper(
            trim(
                $request->voucher_code
            )
        )
    )->first();

    if (! $voucher) {

        return back()->with(
            'error',
            'Kode voucher tidak ditemukan.'
        );
    }

    if (! $voucher->is_active) {

        return back()->with(
            'error',
            'Voucher sudah tidak aktif.'
        );
    }

    if (! $voucher->isApplicableToUser($user)) {
        return back()->with(
            'error',
            'Voucher ini tidak berlaku untuk akun Anda.'
        );
    }

    if ($total < (float) ($voucher->min_purchase ?? 0)) {
        return back()->with(
            'error',
            'Voucher ini berlaku untuk minimum belanja Rp ' . number_format($voucher->min_purchase, 0, ',', '.') . '.'
        );
    }

    if (
        $voucher->expired_at &&
        now()->gt(
            $voucher->expired_at
        )
    ) {

        return back()->with(
            'error',
            'Voucher sudah kadaluarsa.'
        );
    }

    if ($voucher->type === 'free_shipping') {
        $discount = 0;
    } elseif ($voucher->type === 'percentage') {

        $discount =
            ($total * $voucher->value)
            / 100;

    } else {

        $discount =
            $voucher->value;
    }

    if ($discount > $total) {
        $discount = $total;
    }
}

$finalTotal =
    $total - $discount;

$voucherAnnouncement = null;

if ($voucher) {
    if ($voucher->type === 'free_shipping') {
        $voucherAnnouncement = 'Voucher ' . $voucher->code . ' berhasil digunakan. Anda mendapatkan gratis ongkir.';
    } elseif ($voucher->type === 'percentage') {
        $voucherAnnouncement = 'Voucher ' . $voucher->code . ' berhasil digunakan. Anda mendapatkan diskon ' . rtrim(rtrim(number_format((float) $voucher->value, 2, '.', ''), '0'), '.') . '% sebesar Rp ' . number_format($discount, 0, ',', '.') . '.';
    } else {
        $voucherAnnouncement = 'Voucher ' . $voucher->code . ' berhasil digunakan. Anda mendapatkan potongan Rp ' . number_format($discount, 0, ',', '.') . '.';
    }
}

       if (
    $request->payment_method === 'balance' &&
    $user->balance < $finalTotal
) {
            return back()->with(
                'error',
                'Saldo tidak cukup'
            );
        }

        DB::transaction(function () use (
    $cart,
    $user,
    $total,
    $finalTotal,
    $discount,
    $voucher,
    $request
) {

            if (
                $request->payment_method === 'balance'
            ) {

               $user->decrement(
    'balance',
    $finalTotal
);

                $paymentStatus = 'paid';
                $orderStatus = 'paid';

            } else {

                $paymentStatus = 'unpaid';
                $orderStatus = 'pending';

            }

                        $commissionAmount = Order::calculateCommission($finalTotal);
            $sellerIncome = max(0, $finalTotal - $commissionAmount);

            $order = Order::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher?->id,
                'discount' => $discount,
                'total_price' => $finalTotal,
                'status' => $orderStatus,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'commission_amount' => $commissionAmount,
                'seller_income' => $sellerIncome,
            ]);

            foreach ($cart->items as $item) {

                $product = \App\Models\Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $product || $product->status !== 'active' || $product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => 'Stok produk ' . ($product?->name ?? 'tidak dikenal') . ' tidak mencukupi atau telah habis.',
                    ]);
                }

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ]);

                Notification::create([
                    'user_id' => $product->store->user_id,
                    'title' => 'Pesanan Baru',
                    'message' =>
                        $user->name .
                        ' membeli produk ' .
                        $product->name,
                    'type' => 'order',
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Pembelian Berhasil',
                    'message' => 'Pesanan produk ' . $product->name . ' berhasil dibuat.',
                    'type' => 'order',
                ]);

                $product->decrement(
                    'stock',
                    $item->quantity
                );

                $product->increment(
                    'sold_count',
                    $item->quantity
                );

                if ($product->stock <= 0) {
                    $product->update(['status' => 'sold_out']);
                }

                $product
                    ->store
                    ->increment(
                        'total_sales',
                        $item->quantity
                    );
            }

            $cart->items()->delete();
        });

        return redirect()
            ->route('cart.index')
            ->with('success', $voucherAnnouncement
                ? 'Pesanan berhasil dibuat. ' . $voucherAnnouncement
                : 'Pesanan berhasil dibuat.');
    }
}