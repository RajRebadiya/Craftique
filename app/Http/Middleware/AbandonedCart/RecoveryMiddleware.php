<?php

namespace App\Http\Middleware\AbandonedCart;

use App\Models\AbandonedCart\AbandonedCart;
use Closure;
use Illuminate\Http\Request;

class RecoveryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ac_recovery_id')) {
            $abandonedCart = AbandonedCart::where('uuid', $request->ac_recovery_id)->first();

            if ($abandonedCart) {
                $abandonedCart->update([
                    'status' => AbandonedCart::STATUS_BACK_TO_CART,
                ]);

                return $next($request)
                    ->withCookie(
                        cookie('ac_recovery_id', $request->get('ac_recovery_id'), 60 * 24 * 7)
                    );
            }
        }

        return $next($request);
    }
}
