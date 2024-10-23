<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (! auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
