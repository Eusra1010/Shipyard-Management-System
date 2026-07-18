<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSupervisor
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role;

        if ($role !== 'supervisor' && $role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}
