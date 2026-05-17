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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        abort(403, 'عفواً، لا تملك صلاحية الدخول لهذه الصفحة.');
    }
}
