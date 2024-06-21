<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackProductView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->route('product')) {
            $product = $request->route('product');
            $userId = Auth::id();
            RecentlyViewedProduct::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $product->id],
                ['created_at' => now()]
            );
        }

        return $response;
    }
}
