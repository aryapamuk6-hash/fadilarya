<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with([
            'product.store',
            'product.category',
        ])
        ->where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return Inertia::render(
            'Wishlist/Index',
            [
                'wishlists' => $wishlists,
            ]
        );
    }

    public function store(Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        // Notify the product owner that someone added the product to wishlist
        if ($product->store && $product->store->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'user_id' => $product->store->user_id,
                'title' => 'Produk Anda disimpan ke wishlist',
                'message' => auth()->user()->name . " menambahkan produk Anda ke wishlist.",
                'type' => 'wishlist',
                'is_read' => false,
            ]);
        }

        return back()->with(
            'success',
            'Produk ditambahkan ke wishlist'
        );
    }

    public function destroy(Product $product)
    {
        Wishlist::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'product_id',
            $product->id
        )
        ->delete();

        return back()->with(
            'success',
            'Produk dihapus dari wishlist'
        );
    }
}