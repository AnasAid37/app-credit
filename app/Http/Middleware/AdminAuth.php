<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')
                ->with('error', 'Vous devez vous connecter');
        }
        
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('login.form')
                ->with('error', 'Accès non autorisé - Admin requis');
        }

        return $next($request);
    }
}