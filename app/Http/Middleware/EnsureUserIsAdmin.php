<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || (int) auth()->user()->role_id !== 1) {
            return redirect()->route('home')
                ->with('error', 'Aapko admin dashboard access karne ki ijazat nahi hai.');
        }

        return $next($request);
    }
}
