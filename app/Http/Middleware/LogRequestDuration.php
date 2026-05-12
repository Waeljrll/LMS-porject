<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequestDuration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $duration = number_format(microtime(true) - LARAVEL_START, 4);

        Log::info("Request Log:", [
            'method' => $request->getMethod(),
            'url'    => $request->fullUrl(),
            'duration' => $duration . ' seconds',
            'ip'     => $request->ip(),
        ]);

        return $response;
    }
}
