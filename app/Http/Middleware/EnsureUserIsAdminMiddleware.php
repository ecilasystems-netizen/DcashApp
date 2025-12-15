<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user() || (!Auth::user()->is_admin)) {
            return redirect()->route('admin.login')->with('error',
                'You must be logged in as an admin or agent to access this page.');
        }
        return $next($request);
    }
}
