<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!$request->user() || !$request->user()->role) {
            abort(403, 'Unauthorized. No role assigned.');
        }

        if ($request->user()->role->{$permission} === true) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You do not have the required permission: ' . $permission);
    }
}
