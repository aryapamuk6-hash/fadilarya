<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Wishlist;

class MarketplaceController extends Controller
{

public function index(Request $request)
{
    $query = Product::with([
    'store',
    'category',
])
->withCount('wishlists')
->where('status', 'active');


    if ($request->filled('search')) {

        $query->where(
            'name',
            'like',
            '%' . $request->search . '%'
        );
    }

    if ($request->filled('category')) {

        $query->where(
            'category_id',
            $request->category
        );
    }

    switch ($request->sort) {

        case 'price_low':
            $query->orderBy('price');
            break;

        case 'price_high':
            $query->orderByDesc('price');
            break;

        case 'best_seller':
            $query->orderByDesc('sold_count');
            break;

        case 'oldest':
            $query->oldest();
            break;

        default:
            $query->latest();
    }

    $topWishlistProducts = Product::with([
    'store',
    'category',
])
->withCount('wishlists')
->where('status', 'active')
->orderByDesc('wishlists_count')
->take(4)
->get();

    return Inertia::render(
        'Marketplace/Index',
        [
            'products' => $query
                ->paginate(12)
                ->withQueryString(),

            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'sort' => $request->sort,
            ],

            'categories' => Category::all(),

            'stats' => [
                'products' => Product::count(),
                'categories' => Category::count(),
                'activeProducts' => Product::where(
                    'status',
                    'active'
                )->count(),
            ],

            'topWishlistProducts' =>
    $topWishlistProducts,
        ]
    );
}
    public function show(Product $product)
{
    if ($product->is_hidden) {
        abort(404);
    }

    $product->load([
    'images',
    'category',
    'store.reviews.user',
]);
    $isWishlisted = false;

    if (auth()->check()) {

        $isWishlisted = Wishlist::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'product_id',
            $product->id
        )
        ->exists();
    }

    return Inertia::render(
        'Marketplace/Show',
        [
            'product' => $product,
            'isWishlisted' => $isWishlisted,
        ]
    );
}
}