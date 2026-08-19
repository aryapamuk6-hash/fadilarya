<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function show(Store $store)
    {
        $store->load([
            'user',
            'products' => function ($query) {
                $query
                    ->where('status', 'active')
                    ->latest();
            },
            'reviews.user',
        ]);

        return Inertia::render(
            'Store/Show',
            [
                'store' => $store,
            ]
        );
    }
}