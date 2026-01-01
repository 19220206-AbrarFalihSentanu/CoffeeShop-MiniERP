<?php

// File: app/Http/Middleware/RedirectIfAuthenticated.php
// Update file yang sudah ada

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Redirect berdasarkan role
                if ($user->isOwner()) {
                    return redirect()->route('owner.dashboard');
                } elseif ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->isCustomer()) {
                    return redirect()->route('customer.dashboard');
                }

                // Default redirect jika role tidak dikenali
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
