<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(
        Request $request,
        Order $order
    ) {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->review) {
            return back()->with(
                'error',
                'Pesanan ini sudah diberi review.'
            );
        }

        $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $orderItem = $order->items()->first();

        if (! $orderItem || ! $orderItem->product) {
            abort(422, 'Produk pada pesanan tidak ditemukan.');
        }

        $storeId = $orderItem->product->store_id;
        $productId = $orderItem->product_id;

        Review::create([
            'user_id' => auth()->id(),
            'store_id' => $storeId,
            'product_id' => $productId,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $store = \App\Models\Store::find($storeId);

        $store->rating = round(
            $store->reviews()->avg('rating'),
            1
        );

        $store->save();

        // Notify the store owner about the new review
        if ($store->user_id && $store->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'user_id' => $store->user_id,
                'title' => 'Produk Anda mendapat review',
                'message' => auth()->user()->name . " telah memberi review pada toko Anda.",
                'type' => 'review',
                'is_read' => false,
            ]);
        }

        return back()->with(
            'success',
            'Review berhasil dikirim.'
        );
    }
}