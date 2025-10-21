<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (empty($roles)) {
            return $next($request);
        }

        if (!$request->user()) {
            return redirect('/login');
        }

        if ($request->user()->hasAnyRole(...$roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Acceso denegado.'], 403);
        }

        return redirect('/')->with('error', 'Acceso denegado');
    }
}
