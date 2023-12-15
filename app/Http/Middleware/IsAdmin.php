<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin) {
            return $next($request);
        }

        abort(403, 'Accès interdit. Vous devez être administrateur.');
    }
}
