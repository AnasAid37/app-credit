<?php

namespace App\Http\Middleware;

use Closure;

class CheckAccess
{
    public function handle($request, Closure $next, $role = 'user')
    {
        $user = auth()->user();

        // ✅ Admin يدخل لكل شي
        if ($user->is_admin) {
            return $next($request);
        }

        // ✅ للصفحات العادية: تحقق من الاشتراك
        if ($role === 'user' && !$user->canUseApp()) {
            return redirect()->route('subscribe.show')
                ->with('error', 'حسابك غير مفعل أو منتهي الصلاحية');
        }

        // ✅ لصفحات Admin: ممنوع
        if ($role === 'admin') {
            abort(403, 'غير مصرح');
        }

        return $next($request);
    }
}