<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class SellerOrderController extends Controller
{
    public function index()
    {
        $store = auth()->user()?->store;

        if (! $store) {
            abort(403, 'Seller belum memiliki toko.');
        }

        $orders = OrderItem::with([
            'order.user',
            'product',
        ])
            ->whereHas('product', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->latest()
            ->get();

        return inertia('Seller/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function ship(OrderItem $orderItem)
    {
        $store = auth()->user()?->store;

        if (! $store || $orderItem->product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if (auth()->user()->isSuspended()) {
            return back()->with('error', 'Akun seller sedang ditangguhkan.');
        }

        if ($orderItem->order->status === 'completed') {
            return back()->with('error', 'Pesanan sudah selesai.');
        }

        if ($orderItem->order->status === 'shipped') {
            return back()->with('error', 'Pesanan sudah dikirim.');
        }

        $orderItem->order->update([
            'status' => 'shipped',
        ]);

        Notification::create([
            'user_id' => $orderItem->order->user_id,
            'title' => 'Pesanan Dikirim',
            'message' => 'Pesanan #' . $orderItem->order->id . ' sedang dikirim seller.',
            'type' => 'shipping',
        ]);

        return back()->with('success', 'Pesanan dikirim');
    }

    public function complete(OrderItem $orderItem)
    {
        $store = auth()->user()?->store;

        if (! $store || $orderItem->product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if (auth()->user()->isSuspended()) {
            return back()->with('error', 'Akun seller sedang ditangguhkan.');
        }

        if ($orderItem->order->status === 'completed') {
            return back()->with('error', 'Pesanan sudah selesai.');
        }

        if ($orderItem->order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dikirim, tidak dapat diselesaikan.');
        }

        $order = $orderItem->order;
        $order->update([
            'status' => 'completed',
        ]);

        $seller = $orderItem->product->store->user;
        $itemTotal = $orderItem->price * $orderItem->quantity;
        $orderTotal = $order->items()->sum(DB::raw('price * quantity'));

        $itemShare = $orderTotal > 0 ? ($itemTotal / $orderTotal) : 1;
        $sellerShare = $order->seller_income ?? $order->total_price;
        $grossIncome = $sellerShare * $itemShare;

        $seller->increment('balance', $grossIncome);

        Notification::create([
            'user_id' => $orderItem->order->user_id,
            'title' => 'Pesanan Selesai',
            'message' => 'Pesanan #' . $orderItem->order->id . ' telah selesai.',
            'type' => 'completed',
        ]);

        return back()->with('success', 'Pesanan selesai');
    }
}
