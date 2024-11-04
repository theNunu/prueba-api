<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtiene el usuario autenticado
        $user = Auth::user();

        // Verifica si el usuario está autenticado y si tiene el rol de administrador
        if (!$user || $user->role_id !== 1) { // Suponiendo que 1 es el ID del rol de administrador
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        return $next($request);
    }
}