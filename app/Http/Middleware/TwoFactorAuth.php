<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuth
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->profile->two_factor_enabled && !$request->session()->get('2fa_passed')) {
            return redirect()->route('2fa.form');
        }
        return $next($request);
    }
}