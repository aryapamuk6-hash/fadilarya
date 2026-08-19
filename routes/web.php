<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\SellerApplicationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StoreController as PublicStoreController;
use App\Http\Controllers\WishlistController;

use App\Http\Controllers\Admin\SellerApplicationController as AdminSellerApplicationController;
use App\Http\Controllers\Admin\TopUpController as AdminTopUpController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SellerManagementController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;

use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\Seller\WithdrawalController as SellerWithdrawalController;
/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'banners' => \App\Models\Banner::where('is_active', true)->latest()->get(),
    ]);
})->name('welcome');

Route::get(
    '/marketplace',
    [MarketplaceController::class, 'index']
)->name('marketplace');

Route::get(
    '/marketplace/{product}',
    [MarketplaceController::class, 'show']
)->name('marketplace.show');

Route::get(
    '/store/{store:slug}',
    [PublicStoreController::class, 'show']
)->name('store.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

Route::get(
    '/seller/apply',
    [SellerApplicationController::class, 'create']
)->name('seller.apply');

Route::post(
    '/seller/apply',
    [SellerApplicationController::class, 'store']
)->name('seller.apply.store');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->seller_status === 'suspended') {
            return redirect()->route('welcome')->with(
                'error',
                'Hak seller Anda sedang dicabut oleh admin.'
            );
        }

        return redirect()->route(match ($user->role) {
            'admin' => 'admin.dashboard',
            'seller' => 'seller.dashboard',
            default => 'welcome',
        });
    })->name('dashboard');

    Route::get(
        '/wishlist',
        [WishlistController::class, 'index']
    )->name('wishlist.index');

Route::post(
    '/wishlist/{product}',
    [WishlistController::class, 'store']
)->name('wishlist.store');

Route::delete(
    '/wishlist/{product}',
    [WishlistController::class, 'destroy']
)->name('wishlist.destroy');

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

   Route::get('/user/dashboard', function () {
    return redirect()->route('welcome');
})->name('user.dashboard');

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::post(
        '/notifications/{notification}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.readAll');

    /*
    |--------------------------------------------------------------------------
    | TOP UP
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/topup',
        [TopUpController::class, 'index']
    )->name('topup.index');

    Route::post(
        '/topup',
        [TopUpController::class, 'store']
    )->name('topup.store');

    Route::get(
        '/topup/history',
        [TopUpController::class, 'history']
    )->name('topup.history');

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/cart',
        [CartController::class, 'index']
    )->name('cart.index');

    Route::post(
        '/cart/add/{product}',
        [CartController::class, 'add']
    )->name('cart.add');

    Route::patch(
        '/cart/update/{cartItem}',
        [CartController::class, 'update']
    )->name('cart.update');

    Route::delete(
        '/cart/remove/{cartItem}',
        [CartController::class, 'remove']
    )->name('cart.remove');

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/checkout',
        [CheckoutController::class, 'store']
    )->name('checkout.store');

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/orders',
        [OrderController::class, 'index']
    )->name('orders.index');

    /*
    |--------------------------------------------------------------------------
    | REVIEWS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/orders/{order}/review',
        [ReviewController::class, 'create']
    )->name('reviews.create');

    Route::post(
        '/orders/{order}/review',
        [ReviewController::class, 'store']
    )->name('reviews.store');

    /*
    |--------------------------------------------------------------------------
    | CHAT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/chat',
        [ChatController::class, 'index']
    )->name('chat.index');

    Route::post(
        '/chat/start/{product}',
        [ChatController::class, 'start']
    )->name('chat.start');

    Route::post(
        '/chat/start-order/{order}',
        [ChatController::class, 'startFromOrder']
    )->name('chat.start-order');

    Route::post(
        '/chat/start/{product}/buyer/{buyer}',
        [ChatController::class, 'startWithBuyer']
    )->name('chat.startWith');

    Route::get(
        '/chat/{conversation}',
        [ChatController::class, 'show']
    )->name('chat.show');

    Route::post(
        '/chat/{conversation}/send',
        [ChatController::class, 'send']
    )->name('chat.send');

    /*
|--------------------------------------------------------------------------
| SELLER
|--------------------------------------------------------------------------
*/

Route::middleware('role:seller')->group(function () {

Route::get('/seller/dashboard', function () {

    $store = auth()->user()->store;

    $stats = [

        'products' => $store
            ->products()
            ->count(),

        'activeProducts' => $store
            ->products()
            ->where('status', 'active')
            ->count(),

        'soldOutProducts' => $store
            ->products()
            ->where('status', 'sold_out')
            ->count(),

        'soldItems' => $store
            ->products()
            ->sum('sold_count'),

        'revenue' => $store
            ->products()
            ->sum(
                \DB::raw(
                    'price * sold_count'
                )
            ),

        

    ];

    $announcement =
        \App\Models\Announcement::where(
            'is_active',
            true
        )
        ->latest()
        ->first();

    $topProduct = $store
        ->products()
        ->orderByDesc('sold_count')
        ->first();

    $currentMonthRevenue = $store
    ->products()
    ->sum(
        \DB::raw(
            'price * sold_count'
        )
    );

$pendingOrders =
    \App\Models\OrderItem::whereHas(
        'product',
        fn ($q) =>
            $q->where(
                'store_id',
                $store->id
            )
    )
    ->whereHas(
        'order',
        fn ($q) =>
            $q->where(
                'status',
                'pending'
            )
    )
    ->count();

$completedOrders =
    \App\Models\OrderItem::whereHas(
        'product',
        fn ($q) =>
            $q->where(
                'store_id',
                $store->id
            )
    )
    ->whereHas(
        'order',
        fn ($q) =>
            $q->where(
                'status',
                'completed'
            )
    )
    ->count();

    return Inertia::render(
        'DashboardSeller',
        [
            'announcement' => $announcement,
            'stats' => $stats,
            'topProduct' => $topProduct,
            'pendingOrders' => $pendingOrders,
'completedOrders' => $completedOrders,
'currentMonthRevenue' => $currentMonthRevenue,
'store' => $store,
        ]
    );

})->name('seller.dashboard');

    Route::get(
        '/seller/products',
        [ProductController::class, 'index']
    )->name('seller.products.index');

    Route::get(
        '/seller/products/create',
        [ProductController::class, 'create']
    )->name('seller.products.create');

    Route::get(
    '/seller/products/{product}',
    [ProductController::class, 'show']
)->name('seller.products.show');

    Route::get(
    '/seller/products/{product}/edit',
    [ProductController::class, 'edit']
)->name('seller.products.edit');

Route::put(
    '/seller/products/{product}',
    [ProductController::class, 'update'
])->name('seller.products.update');

    Route::post(
        '/seller/products',
        [ProductController::class, 'store']
    )->name('seller.products.store');

    Route::delete(
        '/seller/products/{product}',
        [ProductController::class, 'destroy']
    )->name('seller.products.destroy');

    Route::post(
        '/seller/products/{product}/delete',
        [ProductController::class, 'destroy']
    )->name('seller.products.delete');

    Route::get(
        '/seller/orders',
        [SellerOrderController::class, 'index']
    )->name('seller.orders.index');

    Route::get(
        '/seller/withdrawals',
        [SellerWithdrawalController::class, 'index']
    )->name('seller.withdrawals.index');

    Route::post(
        '/seller/withdrawals',
        [SellerWithdrawalController::class, 'store']
    )->name('seller.withdrawals.store');

    Route::post(
        '/seller/orders/{orderItem}/ship',
        [SellerOrderController::class, 'ship']
    )->name('seller.orders.ship');

    Route::post(
        '/seller/orders/{orderItem}/complete',
        [SellerOrderController::class, 'complete']
    )->name('seller.orders.complete');

    Route::get(
    '/seller/store',
    [SellerStoreController::class, 'edit']
)->name('seller.store.edit');

Route::post(
    '/seller/store',
    [SellerStoreController::class, 'update']
)->name('seller.store.update');

});
    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get(
    '/admin/dashboard',
    [DashboardController::class, 'index']
)->name('admin.dashboard');

        Route::get(
            '/admin/statistics',
            [DashboardController::class, 'statistics']
        )->name('admin.statistics');

        Route::get(
            '/admin/seller-applications',
            [AdminSellerApplicationController::class, 'index']
        )->name('admin.seller.applications');

        Route::post(
            '/admin/seller-applications/{sellerApplication}/approve',
            [AdminSellerApplicationController::class, 'approve']
        )->name('admin.seller.approve');

        Route::post(
            '/admin/seller-applications/{sellerApplication}/reject',
            [AdminSellerApplicationController::class, 'reject']
        )->name('admin.seller.reject');

        Route::get(
            '/admin/topups',
            [AdminTopUpController::class, 'index']
        )->name('admin.topups.index');

        Route::post(
            '/admin/topups/{topUp}/approve',
            [AdminTopUpController::class, 'approve']
        )->name('admin.topups.approve');

        Route::post(
            '/admin/topups/{topUp}/reject',
            [AdminTopUpController::class, 'reject']
        )->name('admin.topups.reject');

        Route::delete(
            '/admin/topups/{topUp}',
            [AdminTopUpController::class, 'destroy']
        )->name('admin.topups.destroy');

        Route::get(
    '/admin/sellers',
    [SellerManagementController::class, 'index']
)->name('admin.sellers.index');

Route::post(
    '/admin/sellers/{user}/warning',
    [SellerManagementController::class, 'warning']
)->name('admin.sellers.warning');

Route::post(
    '/admin/sellers/{user}/suspend',
    [SellerManagementController::class, 'suspend']
)->name('admin.sellers.suspend');

Route::post(
    '/admin/sellers/{user}/unsuspend',
    [SellerManagementController::class, 'unsuspend']
)->name('admin.sellers.unsuspend');

Route::get(
    '/admin/announcements',
    [AnnouncementController::class, 'index']
)->name('admin.announcements.index');

Route::post(
    '/admin/announcements',
    [AnnouncementController::class, 'store']
)->name('admin.announcements.store');

Route::get(
    '/admin/vouchers',
    [AdminVoucherController::class, 'index']
)->name('admin.vouchers.index');

Route::post(
    '/admin/vouchers',
    [AdminVoucherController::class, 'store']
)->name('admin.vouchers.store');

Route::get(
    '/admin/categories',
    [CategoryController::class, 'index']
)->name('admin.categories.index');

Route::post(
    '/admin/categories',
    [CategoryController::class, 'store']
)->name('admin.categories.store');

Route::patch(
    '/admin/categories/{category}',
    [CategoryController::class, 'update']
)->name('admin.categories.update');

Route::delete(
    '/admin/categories/{category}',
    [CategoryController::class, 'destroy']
)->name('admin.categories.destroy');

    Route::get(
    '/admin/products',
    [AdminProductController::class, 'index']
)->name('admin.products.index');

Route::delete(
    '/admin/products/{product}',
    [AdminProductController::class, 'destroy']
)->name('admin.products.destroy');

Route::post(
    '/admin/products/{product}/delete',
    [AdminProductController::class, 'destroy']
)->name('admin.products.delete');

Route::post(
    '/admin/products/{product}/hide'
    ,
    [AdminProductController::class, 'hide']
)->name('admin.products.hide');

Route::post(
    '/admin/products/{product}/show'
    ,
    [AdminProductController::class, 'show']
)->name('admin.products.show');

        Route::get(
            '/admin/banners',
            [BannerController::class, 'index']
        )->name('admin.banners.index');

        Route::post(
            '/admin/banners',
            [BannerController::class, 'store']
        )->name('admin.banners.store');

        Route::delete(
            '/admin/banners/{banner}',
            [BannerController::class, 'destroy']
        )->name('admin.banners.destroy');

        Route::patch(
            '/admin/banners/{banner}/toggle',
            [BannerController::class, 'toggle']
        )->name('admin.banners.toggle');

        Route::get(
            '/admin/withdrawals',
            [AdminWithdrawalController::class, 'index']
        )->name('admin.withdrawals.index');

        Route::post(
            '/admin/withdrawals/{withdrawal}/approve',
            [AdminWithdrawalController::class, 'approve']
        )->name('admin.withdrawals.approve');

        Route::post(
            '/admin/withdrawals/{withdrawal}/reject',
            [AdminWithdrawalController::class, 'reject']
        )->name('admin.withdrawals.reject');

    });

});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});

require __DIR__ . '/auth.php';