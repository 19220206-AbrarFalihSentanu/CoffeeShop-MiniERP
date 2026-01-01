<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: Route::middleware(['auth', 'role:owner,admin'])
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = auth()->user()->role->name;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
