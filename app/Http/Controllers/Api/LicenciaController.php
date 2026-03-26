<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Taller; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LicenciaController extends Controller
{
    public function activar(Request $request)
    {
        // 1. Validar lo que nos manda la App
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Buscar al usuario
        $user = User::where('email', $request->email)->first();

        // 3. Verificar que exista y la contraseña sea correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas o cuenta no encontrada.'
            ], 401);
        }

        // 4. Verificar que sea el DUEÑO (Admin) el que intenta activar
        // Importante: Asegúrate que en tu DB el rol se guarde exactamente como 'admin'
        if (strtolower($user->rol) !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Acceso denegado. Solo el administrador puede activar la licencia.'
            ], 403);
        }

        // 5. Verificar que el usuario tenga un taller asignado
        if (!$user->taller_id) {
            return response()->json([
                'status' => false,
                'message' => 'Este usuario no tiene un taller vinculado.'
            ], 404);
        }

        // 6. Verificar el estado de la licencia del Taller
        $taller = Taller::find($user->taller_id);
        $hoy = now();

        if (!$taller || !$taller->activo || $taller->fecha_vencimiento_licencia < $hoy->format('Y-m-d')) {
            return response()->json([
                'status' => false,
                'message' => 'Licencia vencida o taller inactivo. Contacta a soporte para renovar.'
            ], 403);
        }

        // 7. Generamos un Token de sesión más robusto
        // Usamos un random string para que cada activación genere un token único
        $tokenGenerado = hash('sha256', $user->email . $taller->id . Str::random(10) . now());

        return response()->json([
            'status' => true,
            'message' => '¡Licencia verificada y activada!',
            'datos_licencia' => [
                'taller_id' => $taller->id,
                'nombre_negocio' => $taller->nombre_negocio,
                'token_seguridad' => $tokenGenerado,
                'vencimiento' => $taller->fecha_vencimiento_licencia,
                'usuario' => $user->name
            ]
        ], 200);
    }
}