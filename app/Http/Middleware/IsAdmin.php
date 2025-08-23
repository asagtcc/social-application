<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $admin = auth()->user();
   
        if (! $admin || $admin->type !== "admin" || $admin->is_active != 1) {
            return response()->json([
                'message' => 'ليس لديك صلاحية لدخول لوحة التحكم',
            ], 409);
        }

        return $next($request);
    }
}
