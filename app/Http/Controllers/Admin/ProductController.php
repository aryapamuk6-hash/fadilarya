<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'store',
            'category',
        ])
        ->latest()
        ->get();

        return Inertia::render(
            'Admin/Products/Index',
            [
                'products' => $products,
            ]
        );
    }

    public function hide(Product $product)
    {
        $product->update([
            'is_hidden' => true,
        ]);

        return back()->with(
            'success',
            'Produk berhasil disembunyikan'
        );
    }

    public function show(Product $product)
    {
        $product->update([
            'is_hidden' => false,
        ]);

        return back()->with(
            'success',
            'Produk berhasil ditampilkan'
        );
    }

    public function destroy($productId)
    {
        $product = Product::with('images')->find($productId);

        if (! $product) {
            return back()->with('error', 'Produk sudah tidak tersedia atau telah dihapus.');
        }

        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return back()->with(
            'success',
            'Produk berhasil dihapus'
        );
    }
}