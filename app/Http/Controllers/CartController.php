<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with([
            'items.product.store'
        ])->firstOrCreate([
            'user_id' => auth()->id()
        ]);

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
        ]);
    }

    public function add(Product $product)
    {
        if ($product->status !== 'active' || $product->stock < 1) {
            return back()->with('error', 'Barang telah habis dan tidak dapat dibeli.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            if ($item->quantity >= $product->stock) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Produk Masuk Keranjang',
            'message' => $product->name . ' berhasil dimasukkan ke keranjang.',
            'type' => 'cart',
        ]);

        return back()->with(
            'success',
            'Produk berhasil ditambahkan ke keranjang'
        );
    }

    public function remove($id)
    {
        $cart = Cart::where(
            'user_id',
            auth()->id()
        )->first();

        if ($cart) {
            $cart->items()
                ->where('id', $id)
                ->delete();
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ]);

        $cart = Cart::where(
            'user_id',
            auth()->id()
        )->first();

        if ($cart) {
            $item = $cart->items()->with('product')->where('id', $id)->first();

            if (! $item) {
                return back()->with('error', 'Item keranjang tidak ditemukan.');
            }

            if ($item->product->status !== 'active' || $request->quantity > $item->product->stock) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }

            $item->update(['quantity' => $request->quantity]);
        }

        return back();
    }
}