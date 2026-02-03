<?php

namespace App\Http\Middleware;

 Doreen;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !$request->user()->role) {
            abort(403, 'Unauthorized. No role assigned.');
        }

        if (in_array($request->user()->role->slug, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You do not have the required role.');
    }
}
