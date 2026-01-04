<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckKycStatusMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        // Check if user has completed KYC verification
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.start')
                ->with('error', 'Please complete your KYC verification to access this feature.');
        }

        return $next($request);
    }

}
