<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $tipo)
{
    if (Auth::check()) {
        $user = Auth::user();
       

        // Verifica el tipo de usuario
        if ($user->tipo != $tipo) {
            return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta página.');
        }

        return $next($request); // Permite el acceso
    }

    return redirect()->route('login');
}
}
