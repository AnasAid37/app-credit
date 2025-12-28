<?php

namespace App\Http\Middleware;

use Closure;

class CheckAccess
{
    public function handle($request, Closure $next, $role = 'user')
    {
        $user = auth()->user();

        // ✅ Admin a accès à tout
        if ($user->is_admin) {
            return $next($request);
        }

        // ✅ Pour les pages normales : vérifier l'abonnement
        if ($role === 'user' && !$user->canUseApp()) {
            return redirect()->route('subscribe.show')
                ->with('error', 'Votre compte n\'est pas activé ou a expiré');
        }

        // ✅ Pour les pages Admin : interdit
        if ($role === 'admin') {
            abort(403, 'Non autorisé');
        }

        return $next($request);
    }
}