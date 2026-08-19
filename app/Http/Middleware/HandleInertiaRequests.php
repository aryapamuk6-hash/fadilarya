<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Schema;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
{
    return [
        ...parent::share($request),

        'auth' => [
            'user' => $request->user(),
        ],

        'flash' => [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
        ],

        'wishlistCount' => function () {
            // Avoid querying DB during console/migrations or when table doesn't exist
            if (app()->runningInConsole() || ! Schema::hasTable('wishlists')) {
                return 0;
            }

            return auth()->check()
                ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
                : 0;
        },
        'notificationsCount' => function () {
            if (app()->runningInConsole() || ! Schema::hasTable('notifications')) {
                return 0;
            }

            return auth()->check()
                ? \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count()
                : 0;
        },
    ];
}
}
