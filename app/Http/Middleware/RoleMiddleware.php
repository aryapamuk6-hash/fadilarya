<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        if (!$request->user()) {
            abort(403);
        }

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Seller Suspended
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'seller' &&
            (
                $user->seller_status === 'suspended' ||
                ($user->is_suspended ?? false)
            )
        ) {

            return redirect()
                ->route('user.dashboard')
                ->with(
                    'error',
                    'Hak seller Anda telah dicabut oleh admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Role Check
        |--------------------------------------------------------------------------
        */

        if ($user->role !== $role) {
            abort(403);
        }

        return $next($request);
    }
}