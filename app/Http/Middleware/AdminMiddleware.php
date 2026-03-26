<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario tiene sesión iniciada
        if (Auth::check()) {
            
            // 2. Si su rol ES administrador, lo dejamos pasar
            if (Auth::user()->rol === 'admin') {
                return $next($request);
            }

            // 3. Si NO es administrador, le cerramos la sesión y lo pateamos al login
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors([
                'email' => 'Acceso denegado 🛑. El Panel Web es exclusivo para Administradores. Por favor, inicia sesión desde la App de Escritorio en tu mostrador.'
            ]);
        }

        return redirect('/login');
    }
}