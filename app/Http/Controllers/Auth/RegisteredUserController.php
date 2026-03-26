<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Taller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str; // <-- IMPORTANTE: Para generar el token
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. CREAMOS EL TALLER NUEVO CON SU PRUEBA DE 15 DÍAS
        $taller = Taller::create([
            'plan_id' => 1, // Le damos el plan básico por defecto
            'nombre_negocio' => 'Taller de ' . $request->name, // Nombre temporal que él podrá cambiar después
            'fecha_vencimiento_licencia' => Carbon::now()->addDays(15), // ¡Los 15 días gratis!
            'estado_licencia' => 'prueba',
            'token_licencia' => Str::upper(Str::random(16)), // Genera un token tipo "A1B2C3D4E5F6G7H8"
            'activo' => 1
        ]);

        // 2. CREAMOS AL USUARIO Y LO HACEMOS ADMIN DE ESE TALLER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'taller_id' => $taller->id, // Lo vinculamos a su nuevo taller
            'rol' => 'admin', // Lo hacemos el jefe
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}
