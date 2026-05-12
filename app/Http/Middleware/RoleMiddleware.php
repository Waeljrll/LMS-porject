<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. لو اليوزر مش مسجل دخول، ارميه لصفحة اللوجن
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. فحص الرول: لو رول اليوزر موجودة ضمن الرولز المسموحة عدي الطلب
        // استخدمت ...$roles عشان تقدر تبعت أكتر من رول للميدل وير واحد
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // 3. لو الرول مش مطابقة، اظهر صفحة 403 (غير مصرح)
        abort(403, 'عفواً، لا تملك صلاحية الدخول لهذه الصفحة.');
    }
}
