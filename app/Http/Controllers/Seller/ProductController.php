<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Traits\HandlesUploads;
use Inertia\Inertia;

class ProductController extends Controller
{
    use HandlesUploads;
    public function index()
    {
        $products = Product::with('category')
            ->where('store_id', auth()->user()->store->id)
            ->latest()
            ->get();

        return Inertia::render('Seller/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Seller/Products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isSuspended()) {
            return back()->with(
                'error',
                'Akun seller Anda sedang ditangguhkan oleh admin.'
            );
        }

        $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
        ]);

        $image = $this->saveToPublic($request->file('image'), 'products');

        $product = Product::create([
            'store_id' => auth()->user()->store->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => (int) $request->input('stock'),
            'image' => $image,
            'status' => (int) $request->input('stock') > 0 ? 'active' : 'sold_out',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->saveToPublic($file, 'products/gallery');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }

        User::where('role', 'admin')->each(function (User $admin) use ($product) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Produk Seller Baru',
                'message' => auth()->user()->name . ' menambahkan produk ' . $product->name . '.',
                'type' => 'product',
            ]);
        });

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        abort_unless($product->store_id === auth()->user()->store->id, 404);
        $product->load('images');

        return Inertia::render('Seller/Products/Edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->store_id === auth()->user()->store->id, 404);
        $product->load([
            'images',
            'category',
            'store',
            'orderItems.order.user',
        ]);

        $buyers = $product->orderItems
            ->map(fn ($item) => $item->order->user)
            ->unique('id')
            ->values();

        return Inertia::render('Seller/Products/Show', [
            'product' => $product,
            'buyers' => $buyers,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->store_id === auth()->user()->store->id, 404);

        $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required'],
            'image' => ['nullable', 'image', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $this->saveToPublic($request->file('image'), 'products');
            $product->image = $image;
        }

        $status = $request->status;

        if ($request->stock <= 0) {
            $status = 'sold_out';
        }

        $data = [
    'name' => $request->name,
    'category_id' => $request->category_id,
    'description' => $request->description,
    'price' => $request->price,
    'stock' => (int) $request->input('stock'),
    'status' => $status,
];

if ($image) {
    $data['image'] = $image;
}

$product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->saveToPublic($file, 'products/gallery');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }

        User::where('role', 'admin')->each(function (User $admin) use ($product) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Produk Seller Diperbarui',
                'message' => auth()->user()->name . ' memperbarui produk ' . $product->name . '.',
                'type' => 'product',
            ]);
        });

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($productId)
    {
        $product = Product::with('images')->find($productId);

        if (! $product) {
            return back()->with('error', 'Produk sudah tidak tersedia atau telah dihapus.');
        }

        abort_unless($product->store_id === auth()->user()->store->id, 404);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
