<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == $role) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login to access this page.');
        }
    }
}
