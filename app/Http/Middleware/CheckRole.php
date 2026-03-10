<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: middleware('role:admin')  or  middleware('role:admin,scanner')
     */
    public function handle(Request $request, Closure $next, string...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esa sección.');
        }

        return $next($request);
    }
}
