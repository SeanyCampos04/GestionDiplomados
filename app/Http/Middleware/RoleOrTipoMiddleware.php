<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleOrTipoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @param  string  $tipo
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role, $tipo)
    {
        $user = Auth::user();

        // Verifica si el usuario tiene el rol o el tipo
        if ($user->roles->contains('nombre', $role) || $user->tipo == $tipo) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta página.');
    }
}
