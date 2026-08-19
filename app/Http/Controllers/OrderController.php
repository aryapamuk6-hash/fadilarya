<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
    'items.product',
    'review'
])
->where('user_id', auth()->id())
->latest()
->get();

        return inertia(
            'Orders/Index',
            [
                'orders' => $orders
            ]
        );
    }
}